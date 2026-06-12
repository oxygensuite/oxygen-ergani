<?php

namespace OxygenSuite\OxygenErgani\Http\Auth;

use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\RefreshTokenExpiredException;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

class AuthenticationRefresh extends Client
{
    private const URI = 'Authentication/Refresh';

    public function __construct(?Environment $environment = null, ?ClientConfig $config = null)
    {
        parent::__construct('', $environment, $config);
    }

    /**
     * Refreshes the access token and the refresh token.
     * Refresh token must be expired.
     *
     * @param string $accessToken
     * @param string $refreshToken
     *
     * @return AuthenticationToken
     * @throws RefreshTokenExpiredException
     * @throws ErganiException
     */
    public function handle(string $accessToken, string $refreshToken): AuthenticationToken
    {
        try {
            return $this->post(self::URI, [
                'AccessToken' => $accessToken,
                'RefreshToken' => $refreshToken,
            ])->morphTo(AuthenticationToken::class);
        } catch (AuthenticationException $exception) {
            throw new RefreshTokenExpiredException($exception->getMessage());
        }
    }

    protected function requiresAuthentication(): bool
    {
        return false;
    }
}
