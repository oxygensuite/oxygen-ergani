<?php

namespace OxygenSuite\OxygenErgani\Http\Auth;

use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Enums\UserType;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Http\ClientConfig;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

class AuthenticationLogin extends Client
{
    private const URI = 'Authentication';

    public function __construct(?Environment $environment = null, ?ClientConfig $config = null)
    {
        parent::__construct('', $environment, $config);
    }

    /**
     * Authenticates the user.
     *
     * Access token is used to authenticate the user for subsequent
     * requests, while the refresh token is used to obtain a new access token
     *
     * @param string          $username
     * @param string          $password
     * @param UserType|string $userType
     *
     * @return AuthenticationToken
     * @throws ErganiException
     */
    public function handle(string $username, string $password, UserType|string $userType = UserType::EXTERNAL): AuthenticationToken
    {
        return $this->post(self::URI, [
            'Username' => $username,
            'Password' => $password,
            'Usertype' => $userType instanceof UserType ? $userType->value : $userType,
        ])->morphTo(AuthenticationToken::class);
    }

    protected function requiresAuthentication(): bool
    {
        return false;
    }
}
