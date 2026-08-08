<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Token;

use DateTimeImmutable;
use OxygenSuite\OxygenErgani\Enums\UserType;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Storage\InMemoryToken;
use Tests\TestCase;

class TokenDefaultConfigTest extends TestCase
{
    protected function tearDown(): void
    {
        ClientConfig::resetDefault();
    }

    public function test_internal_login_uses_global_default_config(): void
    {
        // The MockHandler is only reachable through the global default:
        // if Token::login() ignored it, the request would hit the network and fail.
        $mock = $this->mockResponse(200, 'new-authentication.json');
        ClientConfig::setDefault(
            (new ClientConfig())->setHandler($mock),
        );

        $token = new InMemoryToken('username', 'password');
        $accessToken = $token->authenticate();

        $this->assertSame('new-access-token', $accessToken);

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertSame('01', $body['Usertype']);
    }

    public function test_internal_login_sends_user_type_from_token_manager(): void
    {
        $mock = $this->mockResponse(200, 'new-authentication.json');
        ClientConfig::setDefault((new ClientConfig())->setHandler($mock));

        $token = new InMemoryToken('username', 'password', UserType::EFKA);
        $token->authenticate();

        $body = json_decode((string) $mock->getLastRequest()->getBody(), true);
        $this->assertSame('03', $body['Usertype']);
    }

    public function test_internal_refresh_uses_global_default_config(): void
    {
        // The MockHandler is only reachable through the global default:
        // if Token::refresh() ignored it, the request would hit the network and fail.
        ClientConfig::setDefault(
            (new ClientConfig())->setHandler($this->mockResponse(200, 'new-authentication.json')),
        );

        $token = new InMemoryToken('username', 'password');

        $expired = new AuthenticationToken();
        $expired->accessToken = 'test-access-token';
        $expired->accessTokenExpiresAt = new DateTimeImmutable('yesterday');
        $expired->refreshToken = 'test-refresh-token';
        $expired->refreshTokenExpiresAt = new DateTimeImmutable('tomorrow');
        $token->setAuthToken($expired);

        $accessToken = $token->authenticate();

        $this->assertSame('new-access-token', $accessToken);
    }
}
