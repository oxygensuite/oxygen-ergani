<?php

namespace OxygenSuite\OxygenErgani\Http\Auth;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\TokenExpiredException;
use OxygenSuite\OxygenErgani\Http\Client;

class AuthenticationLogout extends Client
{
    private const URI = 'Authentication/Logout';

    /**
     * Deletes the refresh token from the api server.
     *
     * @param string $refreshToken
     *
     * @return bool
     * @throws ErganiException
     * @throws TokenExpiredException
     */
    public function handle(string $refreshToken): bool
    {
        $body = json_encode($refreshToken);

        if ($body === false) {
            return false;
        }

        return $this->asJson()
            ->post(self::URI, $body)
            ->isSuccessful();
    }
}
