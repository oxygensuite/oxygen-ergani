<?php

namespace OxygenSuite\OxygenErgani\Storage;

use DateTime;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

class InMemoryToken extends Token
{
    private ?AuthenticationToken $token = null;

    public function getAccessToken(): ?string
    {
        return $this->token?->accessToken;
    }

    public function getRefreshToken(): ?string
    {
        return $this->token?->refreshToken;
    }

    public function isAccessTokenExpired(): bool
    {
        return $this->token?->accessTokenExpiresAt < new DateTime();
    }

    public function isRefreshTokenExpired(): bool
    {
        return $this->token?->refreshTokenExpiresAt < new DateTime();
    }

    public function setAuthToken(AuthenticationToken $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function failedAuthentication(): static
    {
        $this->token = null;

        return $this;
    }

    public function authToken(): ?AuthenticationToken
    {
        return $this->token;
    }

    public static function fake(string $username, string $password): FakeInMemoryToken
    {
        return new FakeInMemoryToken($username, $password);
    }
}
