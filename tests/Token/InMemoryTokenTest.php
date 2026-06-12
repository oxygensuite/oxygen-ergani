<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Token;

use DateTimeImmutable;
use Exception;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Storage\InMemoryToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use Tests\TestCase;

class InMemoryTokenTest extends TestCase
{
    public function test_token_is_cached(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('tomorrow');

        $memoryToken = new InMemoryToken('username', 'password');
        $memoryToken->setAuthToken($token);

        $this->assertNotNull($memoryToken->authToken());
    }

    public function test_cache_is_deleted_on_failed_authentication(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $token->refreshToken = 'test-refresh-token';
        $token->refreshTokenExpiresAt = new DateTimeImmutable('tomorrow');

        $memoryToken = InMemoryToken::fake('username', 'password');
        $memoryToken->setAuthToken($token);

        $memoryToken->setLoginHandler($this->mockResponse(401)); // Could not authenticate
        $memoryToken->setRefreshHandler($this->mockResponse(401)); // Could not refresh token

        $this->assertNotNull($memoryToken->authToken());

        try {
            $memoryToken->authenticate();
        } catch (Exception) {
        }

        $this->assertNull($memoryToken->authToken());
    }

    /**
     * Expected behavior:
     * 1. Sees that access token is not expired
     * 2. Does not call refresh token
     * 3. Does not call login
     */
    public function test_access_token_not_expired(): void
    {
        $token = new AuthenticationToken();
        $token->accessToken = 'test-access-token';
        $token->accessTokenExpiresAt = new DateTimeImmutable('tomorrow');

        $fake = InMemoryToken::fake('username', 'password');
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
     * 2. Sees that refresh token is not expired
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

        // Create cached token
        $fake = InMemoryToken::fake('username', 'password');
        $fake->setAuthToken($token);
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
     * 2. Sees that refresh token is not expired
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

        // Create cached token
        $fake = InMemoryToken::fake('username', 'password');
        $fake->setAuthToken($token);
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

        // Create cached token
        $fake = InMemoryToken::fake('username', 'password');
        $fake->setAuthToken($token);
        $fake->setLoginHandler($this->mockResponse(200, 'new-authentication.json'));
        Token::setCurrentTokenManager($fake);

        $wrkCard = new WorkCard();
        $wrkCard->getConfig()->setHandler($this->mockResponse(200, 'work-card.json'));
        $wrkCard->schema();

        $this->assertFalse($fake->refreshCalled());
        $this->assertTrue($fake->loginCalled());
    }
}
