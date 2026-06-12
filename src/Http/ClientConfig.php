<?php

namespace OxygenSuite\OxygenErgani\Http;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class ClientConfig
{
    /** @var array<string, mixed> */
    private array $options = [
        'timeout' => 10,
    ];

    /**
     * Create a new client configuration instance.
     *
     * @param array<string, mixed> $options Configuration options
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Set the timeout of the request in seconds.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout(int $timeout): static
    {
        $this->options['timeout'] = $timeout;

        return $this;
    }

    /**
     * Get the timeout of the request in seconds.
     *
     * @return int|null
     */
    public function getTimeout(): ?int
    {
        return $this->options['timeout'] ?? null;
    }

    /**
     * <ul>Describes the SSL certificate verification behavior of a request.
     *
     * <li>Set to <code>true</code> to enable SSL certificate verification and use the default CA bundle provided by operating system.</li>
     * <li>Set to <code>false</code> to disable certificate verification (this is insecure!).</li>
     * <li>Set to a string to provide the path to a CA bundle to enable verification using a custom certificate.</li>
     * </ul>
     *
     * @return $this
     */
    public function setVerifyClient(bool|string $verify = true): static
    {
        $this->options['verify'] = $verify;

        return $this;
    }

    public function getVerifyClient(): bool|string|null
    {
        return $this->options['verify'] ?? null;
    }

    /**
     * Sets the content type of the request to JSON.
     */
    public function asJson(): static
    {
        $this->options['headers']['Content-Type'] = 'application/json';

        return $this;
    }

    /**
     * Accepts JSON responses from the server.
     *
     * @return $this
     */
    public function acceptJson(): static
    {
        $this->options['headers']['Accept'] = 'application/json';

        return $this;
    }

    /**
     * Get the bearer token for the request.
     *
     * @return string|null
     */
    public function getBearerToken(): ?string
    {
        $bearer = $this->options['headers']['Authorization'] ?? '';
        if (empty(trim($bearer))) {
            return null;
        }

        return str_replace('Bearer ', '', $bearer);
    }

    public function hasAccessToken(): bool
    {
        return !empty($this->getBearerToken());
    }

    /**
     * Set the bearer token for the request.
     *
     * @param string $token
     *
     * @return $this
     */
    public function setBearerToken(string $token): static
    {
        $this->options['headers']['Authorization'] = 'Bearer ' . $token;

        return $this;
    }

    /**
     * Set the request handler for the client. Useful for testing purposes.
     *
     * @param MockHandler|null $handler
     *
     * @return $this
     */
    public function setHandler(?MockHandler $handler): static
    {
        if ($handler) {
            $this->options['handler'] = HandlerStack::create($handler);
        } else {
            unset($this->options['handler']);
        }

        return $this;
    }

    /**
     * Retrieves the options.
     *
     * @return array<string, mixed> An array containing the options.
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
