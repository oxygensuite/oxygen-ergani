<?php

namespace OxygenSuite\OxygenErgani\Responses;

use DateInterval;
use DateTimeImmutable;
use Throwable;

class AuthenticationToken extends Response
{
    public ?string $accessToken = null;
    private ?int $accessTokenExpirationSeconds = null;
    public ?string $refreshToken = null;
    public ?DateTimeImmutable $refreshTokenExpiresAt = null;
    public ?DateTimeImmutable $accessTokenExpiresAt = null;

    protected function processData(): void
    {
        $this->accessToken = $this->string('accessToken');
        $this->accessTokenExpirationSeconds = $this->int('accessTokenExpired');
        $this->refreshToken = $this->string('refreshToken');

        $this->initAccessTokenExpirationDate();
        $this->refreshTokenExpiresAt = $this->date('refreshTokenExpired');
    }

    protected function initAccessTokenExpirationDate(): void
    {
        try {
            $now = new DateTimeImmutable();
            $this->accessTokenExpiresAt = $now->add(new DateInterval('PT' . $this->accessTokenExpirationSeconds . 'S'));
        } catch (Throwable) {
            $this->refreshTokenExpiresAt = null;
        }
    }
}
