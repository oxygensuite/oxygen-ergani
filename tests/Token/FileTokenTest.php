<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Token;

use DateTimeImmutable;
use Exception;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use Tests\TestCase;

class FileTokenTest extends TestCase
{
    protected function setUp(): void
    {
        FileToken::forgetAllTokens();
        Token::setCurrentTokenManager(null);
    }

    protected function tearDown(): void
    {
        FileToken::forgetAllTokens();
        FileToken::resetDirectory();
        Token::setCurrentTokenManager(null);
    }

    public function test_token_is_cached_on_test(): void
    {
        Client::setDefaultEnvironment(Environment::TEST);

        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('tomorrow');

        $fileToken = new FileToken('username', 'password');
        $fileToken->setAuthToken($token);

        $this->assertFileExists($fileToken->path());
        $this->assertSame(hash('sha256', 'username-password-TEST'), $fileToken->getFilename());
        $this->assertSame(hash('sha256', 'username-password-TEST'), $fileToken->generateFilename());

        $cachedToken = json_decode(file_get_contents($fileToken->path()), true)['token'];
        $this->assertIsArray($cachedToken);
        $this->assertSame('test-access-token', $cachedToken['accessToken']);
        $this->assertSame('test-refresh-token', $cachedToken['refreshToken']);
        $this->assertSame($token->accessTokenExpiresAt->getTimestamp(), $cachedToken['accessTokenExpiresAt']);
        $this->assertSame($token->refreshTokenExpiresAt->getTimestamp(), $cachedToken['refreshTokenExpiresAt']);

        $this->assertNotNull($fileToken->authToken());
    }

    public function test_token_is_cached_on_production(): void
    {
        Client::setDefaultEnvironment(Environment::PRODUCTION);

        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('tomorrow');

        $fileToken = new FileToken('username', 'password');
        $fileToken->setAuthToken($token);

        $this->assertFileExists($fileToken->path());
        $this->assertSame(hash('sha256', 'username-password-PRODUCTION'), $fileToken->getFilename());
        $this->assertSame(hash('sha256', 'username-password-PRODUCTION'), $fileToken->generateFilename());

        $cachedToken = json_decode(file_get_contents($fileToken->path()), true)['token'];
        $this->assertIsArray($cachedToken);
        $this->assertSame('test-access-token', $cachedToken['accessToken']);
        $this->assertSame('test-refresh-token', $cachedToken['refreshToken']);
        $this->assertSame($token->accessTokenExpiresAt->getTimestamp(), $cachedToken['accessTokenExpiresAt']);
        $this->assertSame($token->refreshTokenExpiresAt->getTimestamp(), $cachedToken['refreshTokenExpiresAt']);

        $this->assertNotNull($fileToken->authToken());
    }

    public function test_cache_is_deleted_on_failed_authentication(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('yesterday');

        $fileToken = FileToken::fake('username', 'password');
        $fileToken->setAuthToken($token);
        $this->assertFileExists($fileToken->path());

        $this->assertNotNull($fileToken->authToken());
        $fileToken->setLoginHandler($this->mockResponse(401)); // Could not authenticate

        try {
            $fileToken->authenticate();
        } catch (Exception) {
        }

        $this->assertTrue($fileToken->loginCalled());

        $this->assertFileDoesNotExist($fileToken->path());
        $this->assertNull($fileToken->authToken());
    }

    /**
     * Expected behavior:
     * 1. Sees that access token doesn't exist
     * 2. Calls login
     */
    public function test_first_time_usage_with_successful_auth(): void
    {
        $fake = FileToken::fake('username', 'password');
        $fake->setLoginHandler($this->mockResponse(200, 'new-authentication.json'));
        Token::setCurrentTokenManager($fake);

        $wrkCard = new WorkCard();
        $wrkCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wrkCard->schema();

        $this->assertFalse($fake->refreshCalled());
        $this->assertTrue($fake->loginCalled());
    }

    /**
     * Expected behavior:
     * 1. Sees that access token doesn't exist
     * 2. Calls login
     * 3. Fails authentication
     */
    public function test_first_time_usage_with_failed_auth(): void
    {
        $this->expectException(AuthenticationException::class);

        $fake = FileToken::fake('wrong-username', 'wrong-password');
        $fake->setLoginHandler($this->mockResponse(401));
        Token::setCurrentTokenManager($fake);

        $wrkCard = new WorkCard();
        $wrkCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wrkCard->schema();

        $this->assertFalse($fake->refreshCalled());
        $this->assertTrue($fake->loginCalled());
    }

    /**
     * Expected behavior:
     * 1. Sees that the access token is not expired
     * 2. Does not call refresh token
     * 3. Does not call login
     */
    public function test_access_token_not_expired(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('tomorrow');

        $fake = FileToken::fake('username', 'password');
        $fake->setAuthToken($token);
        Token::setCurrentTokenManager($fake);

        $wrkCard = new WorkCard();
        $wrkCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wrkCard->schema();

        $this->assertFalse($fake->refreshCalled());
        $this->assertFalse($fake->loginCalled());
    }

    /**
     * Expected behavior:
     * 1. Sees that access token is expired
     * 2. Sees that the refresh token is not expired
     * 3. Calls refresh token
     * 4. Does not call login
     */
    public function test_access_token_expired_and_refresh_token_not_expired(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('tomorrow');

        // Create a cached token
        $fileToken = new FileToken('username', 'password');
        $fileToken->setAuthToken($token);

        $fake = FileToken::fake('username', 'password');
        $fake->setRefreshHandler($this->mockResponse(200, 'new-authentication.json'));
        Token::setCurrentTokenManager($fake);

        $wrkCard = new WorkCard();
        $wrkCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wrkCard->schema();

        $this->assertTrue($fake->refreshCalled());
        $this->assertFalse($fake->loginCalled());
    }

    /**
     * Expected behavior:
     * 1. Sees that access token is expired
     * 2. Sees that the refresh token is not expired
     * 3. Calls refresh token and receives 401 Unauthenticated
     * 4. Calls login
     */
    public function test_access_token_expired_and_refresh_token_not_expired_with_401(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('tomorrow');

        // Create a cached token
        $fileToken = new FileToken('username', 'password');
        $fileToken->setAuthToken($token);

        $fake = FileToken::fake('username', 'password');
        $fake->setLoginHandler($this->mockResponse(200, 'new-authentication.json'));
        $fake->setRefreshHandler($this->mockResponse(401));
        Token::setCurrentTokenManager($fake);

        $wrkCard = new WorkCard();
        $wrkCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wrkCard->schema();

        $this->assertTrue($fake->refreshCalled());
        $this->assertTrue($fake->loginCalled());

        $newToken = $fake->authToken();
        $this->assertNotNull($newToken);
        $this->assertSame('new-access-token', $newToken->accessToken);
        $this->assertSame('new-refresh-token', $newToken->refreshToken);
        $this->assertNotNull($newToken->accessTokenExpiresAt->getTimestamp());
        $this->assertSame((new DateTimeImmutable('2025-02-21T14:44:28.2731304+02:00'))->getTimestamp(), $newToken->refreshTokenExpiresAt->getTimestamp());
    }

    /**
     * Expected behavior:
     * 1. Sees that access token is expired
     * 2. Sees that refresh token is expired
     * 3. Calls login
     * 4. Does not call refresh token
     */
    public function test_access_token_expired_and_refresh_token_expired(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('yesterday');

        // Create a cached token
        $fileToken = new FileToken('username', 'password');
        $fileToken->setAuthToken($token);

        $fake = FileToken::fake('username', 'password');
        $fake->setLoginHandler($this->mockResponse(200, 'new-authentication.json'));
        Token::setCurrentTokenManager($fake);

        $wrkCard = new WorkCard();
        $wrkCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wrkCard->schema();

        $this->assertFalse($fake->refreshCalled());
        $this->assertTrue($fake->loginCalled());
    }

    /**
     * Ensures the default cache directory name ends with '.cache'.
     *
     * This is a safety contract: forgetAllTokens() relies on this naming
     * to prevent accidental deletion of project files. If you need to
     * change the directory name, update forgetAllTokens() safety check too.
     */
    public function test_cache_directory_ends_with_dot_cache(): void
    {
        $dir = FileToken::dir();

        $this->assertStringEndsWith('.cache', $dir, sprintf(
            'Cache directory must end with ".cache" for safety. Got: %s. '
            . 'See forgetAllTokens() safety check.',
            $dir,
        ));
    }

    public function test_custom_directory_can_be_set(): void
    {
        $customDir = sys_get_temp_dir() . '/oxygen-ergani-test-tokens';

        FileToken::setDirectory($customDir);

        $this->assertSame($customDir, FileToken::dir());

        // Verify tokens are stored in the custom directory
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new \DateTimeImmutable('tomorrow');

        $fileToken = new FileToken('username', 'password');
        $fileToken->setAuthToken($token);

        $this->assertStringStartsWith($customDir, $fileToken->path());
        $this->assertFileExists($fileToken->path());

        // Cleanup
        FileToken::forgetAllTokens();
        if (is_dir($customDir)) {
            rmdir($customDir);
        }
    }

    public function test_reset_directory_restores_default(): void
    {
        $defaultDir = FileToken::dir();

        FileToken::setDirectory('/custom/path');
        $this->assertSame('/custom/path', FileToken::dir());

        FileToken::resetDirectory();
        $this->assertSame($defaultDir, FileToken::dir());
    }

    public function test_instance_cache_dir_option(): void
    {
        $instanceDir = sys_get_temp_dir() . '/oxygen-ergani-instance-test';

        $fileToken = new FileToken('username', 'password', [
            'cache_dir' => $instanceDir,
        ]);

        $this->assertSame($instanceDir, $fileToken->getDirectory());
        $this->assertStringStartsWith($instanceDir, $fileToken->path());

        // Verify tokens are stored in the instance directory
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new \DateTimeImmutable('tomorrow');

        $fileToken->setAuthToken($token);
        $this->assertFileExists($fileToken->path());

        // Cleanup
        $fileToken->deleteFile();
        if (is_dir($instanceDir)) {
            rmdir($instanceDir);
        }
    }

    public function test_instance_cache_dir_overrides_static_directory(): void
    {
        $staticDir = sys_get_temp_dir() . '/oxygen-static-dir';
        $instanceDir = sys_get_temp_dir() . '/oxygen-instance-dir';

        FileToken::setDirectory($staticDir);

        $fileToken = new FileToken('username', 'password', [
            'cache_dir' => $instanceDir,
        ]);

        // Instance directory should take precedence over static
        $this->assertSame($instanceDir, $fileToken->getDirectory());
        $this->assertStringStartsWith($instanceDir, $fileToken->path());

        // Static dir() should still return the static directory
        $this->assertSame($staticDir, FileToken::dir());
    }

    public function test_instance_without_options_uses_static_directory(): void
    {
        $staticDir = sys_get_temp_dir() . '/oxygen-static-dir';

        FileToken::setDirectory($staticDir);

        $fileToken = new FileToken('username', 'password');

        $this->assertSame($staticDir, $fileToken->getDirectory());
    }

    public function test_default_directory_used_when_no_configuration(): void
    {
        // Ensure no custom directory is set
        FileToken::resetDirectory();

        $fileToken = new FileToken('username', 'password');
        $defaultDir = dirname(__DIR__, 2) . '/.cache';

        // Both static and instance should return the default
        $this->assertSame($defaultDir, FileToken::dir());
        $this->assertSame($defaultDir, $fileToken->getDirectory());
    }
}
