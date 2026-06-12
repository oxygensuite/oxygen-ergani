<?php

namespace OxygenSuite\OxygenErgani\Storage;

use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

interface TokenManager
{
    /**
     * Retrieves the access token if it exists.
     * This token may be used for authentication or API requests.
     *
     * @return string|null Returns the access token as a string, or null if no token is found.
     */
    public function getAccessToken(): ?string;

    /**
     * Retrieves the refresh token if it exists.
     *
     * @return string|null Returns the refresh token as a string or null if no token is available.
     */
    public function getRefreshToken(): ?string;

    /**
     * Checks if the access token has expired.
     *
     * @return bool Returns true if the access token has expired, false otherwise.
     */
    public function isAccessTokenExpired(): bool;

    /**
     * Checks if the refresh token has expired.
     *
     * @return bool Returns true if the refresh token has expired, false otherwise.
     */
    public function isRefreshTokenExpired(): bool;

    /**
     * Sets the access token, refresh token, and their expiration times.
     *
     * @param AuthenticationToken $token
     *
     * @return static
     */
    public function setAuthToken(AuthenticationToken $token): static;

    /**
     * Authenticates the user and returns the access token.
     *
     * @return string|null
     */
    public function authenticate(): ?string;

    /**
     * Called when authentication fails.
     *
     * @return static
     */
    public function failedAuthentication(): static;

    /**
     * Retrieves the authentication token.
     *
     * @return AuthenticationToken|null
     */
    public function authToken(): ?AuthenticationToken;
}
