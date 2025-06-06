<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Tests\Auth;

use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use Tests\TestCase;

class AuthenticationLoginTest extends TestCase
{
    public function test_authentication(): void
    {
        $auth = new AuthenticationLogin();
        $auth->getConfig()->setHandler($this->mockResponse(200, 'authentication.json'));
        $response = $auth->handle('username', 'password');

        $this->assertNotNull($response);
        $this->assertSame('test-access-token', $response->accessToken);
        $this->assertSame('test-refresh-token', $response->refreshToken);
        $this->assertDates('2025-02-21T14:44:28.2731304+02:00', $response->refreshTokenExpiresAt);
    }

    public function test_failed_authentication(): void
    {
        $this->expectException(AuthenticationException::class);

        $auth = new AuthenticationLogin();
        $auth->getConfig()->setHandler($this->mockResponse(401));
        $response = $auth->handle('wrong-username', 'wrong-password');

        $this->assertNotNull($response);
        $this->assertSame('test-access-token', $response->accessToken);
        $this->assertSame('test-refresh-token', $response->refreshToken);
        $this->assertDates('2025-02-21T14:44:28.2731304+02:00', $response->refreshTokenExpiresAt);
    }
}
