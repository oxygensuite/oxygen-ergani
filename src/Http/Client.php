<?php

namespace OxygenSuite\OxygenErgani\Http;

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use InvalidArgumentException;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Exceptions\ConnectionException;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\TokenExpiredException;
use OxygenSuite\OxygenErgani\Storage\Token;
use Psr\Http\Message\ResponseInterface;

class Client
{
    private static Environment $defaultEnvironment = Environment::TEST;

    private ?string $accessToken;
    private Environment $environment;
    private ClientConfig $config;
    private ?ResponseInterface $response;

    /**
     * Create a new client instance. If no environment is provided, the client
     * will default to the test environment.
     *
     * @param string|null $accessToken Access token for the API
     * @param Environment|null $environment Environment for the client
     * @param ClientConfig|null $config Configuration for the client
     */
    public function __construct(?string $accessToken = null, ?Environment $environment = null, ?ClientConfig $config = null)
    {
        $this->accessToken = $accessToken;
        $this->environment = $environment ?? self::$defaultEnvironment;
        $this->config = $config ?? new ClientConfig();
    }

    public static function setDefaultEnvironment(Environment $environment): void
    {
        self::$defaultEnvironment = $environment;
    }

    public static function getDefaultEnvironment(): ?Environment
    {
        return self::$defaultEnvironment;
    }

    /**
     * Get the environment for the client.
     *
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * Set the environment for the client.
     *
     * @param Environment $environment
     *
     * @return static
     */
    public function setEnvironment(Environment $environment): static
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * Get the configuration for the client.
     *
     * @return ClientConfig
     */
    public function getConfig(): ClientConfig
    {
        return $this->config;
    }

    /**
     * Set the configuration for the client.
     *
     * @param ClientConfig $config
     *
     * @return static
     */
    public function setConfig(ClientConfig $config): static
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Sets the content type of the request to JSON.
     */
    public function asJson(): static
    {
        $this->config->asJson();

        return $this;
    }

    /**
     * Accepts JSON responses from the server.
     *
     * @return static
     */
    public function acceptJson(): static
    {
        $this->config->acceptJson();

        return $this;
    }

    /**
     * Returns the status code of the response.
     *
     * @return bool Whether the request was successful
     */
    public function isSuccessful(): bool
    {
        return $this->response?->getStatusCode() === 200;
    }

    /**
     * Make a GET request to the API.
     *
     * @param string $uri The URI to make the request to
     * @param array<string, mixed>|null $query The query parameters
     * @param string|array<string, mixed>|null $body The body of the request
     *
     * @return static
     * @throws ErganiException
     */
    protected function get(string $uri, ?array $query = null, string|array|null $body = null): static
    {
        return $this->request('GET', $uri, $body, $query);
    }

    /**
     * Make a POST request to the API.
     *
     * @param string $uri The URI to make the request to
     * @param array<string, mixed>|string|null $body The body of the request
     * @param array<string, mixed>|null $query The query parameters
     *
     * @return static
     * @throws ErganiException
     */
    protected function post(string $uri, array|string|null $body = null, ?array $query = null): static
    {
        return $this->request('POST', $uri, $body, $query);
    }

    /**
     * Make a request to the API.
     *
     * @param string $method The HTTP method
     * @param string $uri The URI to make the request to
     * @param array<string, mixed>|string|null $body The body of the request
     * @param array<string, mixed>|null $query The query parameters
     *
     * @return static
     * @throws ErganiException
     */
    protected function request(string $method, string $uri, array|string|null $body = null, ?array $query = null): static
    {
        $this->validateUri($uri);
        $client = $this->createClient();
        $this->response = null;

        try {
            $this->response = $client->request($method, $uri, $this->buildRequestOptions($query, $body));
            $this->validateResponse();

            return $this;
        } catch (TokenExpiredException $e) {
            // Access token has expired, try to refresh it
            if (Token::hasTokenManager()) {
                Token::currentTokenManager()->authenticate();

                return $this->request($method, $uri, $body, $query);
            }

            throw $e;
        } catch (GuzzleException $e) {
            throw new ErganiException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Set the access token for the client. This overrides the current token manager.
     *
     * @return void
     * @throws AuthenticationException
     */
    protected function initAccessToken(): void
    {
        if (!$this->requiresAuthentication()) {
            return;
        }

        if (empty($this->accessToken) && empty(Token::currentTokenManager())) {
            throw new AuthenticationException('Access token is required');
        }

        // Set the access token if provided, overrides the current token manager
        if (!empty($this->accessToken)) {
            $this->config->setBearerToken($this->accessToken);

            return;
        }

        // Access token is not provided, try to get it from the current token manager
        if ($cachedAccessToken = Token::currentTokenManager()->authenticate()) {
            $this->config->setBearerToken($cachedAccessToken);
        }
    }

    /**
     * Validate the URI.
     *
     * @param string $uri
     *
     * @return void
     */
    protected function validateUri(string $uri): void
    {
        if (empty(trim($uri))) {
            throw new InvalidArgumentException('URI cannot be empty');
        }
    }

    /**
     * Creates a new instance of the Guzzle client with the
     * provided configuration.
     *
     * @return BaseClient
     * @throws AuthenticationException
     */
    protected function createClient(): BaseClient
    {
        $this->initAccessToken();

        return new BaseClient([
            'base_uri' => $this->environment->getApiUrl(),
            'http_errors' => false,
            ...$this->config->getOptions(),
        ]);
    }

    /**
     * Build the request options.
     *
     * @param array<string, mixed>|null $query
     * @param array<string, mixed>|string|null $body
     *
     * @return array<string, mixed>
     */
    protected function buildRequestOptions(?array $query = null, array|string|null $body = null): array
    {
        $options = [];

        if (!empty($query)) {
            $options[RequestOptions::QUERY] = $query;
        }

        if (is_array($body)) {
            $options[RequestOptions::JSON] = $body;
        } elseif (is_string($body)) {
            $options[RequestOptions::BODY] = $body;
        }

        return $options;
    }

    /**
     * Returns the status code of the response.
     *
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->response?->getStatusCode() ?? null;
    }

    /**
     * @throws ConnectionException
     * @throws AuthenticationException
     * @throws ErganiException
     */
    protected function validateResponse(): void
    {
        if ($this->isSuccessful()) {
            return;
        }

        $message = $this->response->getReasonPhrase();
        $code = $this->response->getStatusCode();

        // Handle connection issues
        if ($code === 0) {
            throw new ConnectionException($message, $code);
        }

        // Bad request
        if ($code === 400) {
            $bodyMessage = $this->extractMessageFromResponse() ?: $message;

            throw new ErganiException($bodyMessage, $code);
        }

        // Handle authentication issues
        if ($code === 401) {
            $this->handleAuthenticationError();
        }

        throw new ErganiException($message, $code);
    }

    /**
     * Handle authentication errors.
     *
     * @throws TokenExpiredException
     * @throws AuthenticationException
     */
    private function handleAuthenticationError(): never
    {
        $message = $this->extractMessageFromResponse();

        if ($this->apiTokenExpired()) {
            throw new TokenExpiredException($message, 401);
        }

        // The authentication with username and password failed.
        // This means that the username/password combination is no
        // longer valid, and thus we need to clear the active token
        Token::currentTokenManager()?->failedAuthentication();

        throw new AuthenticationException($message, 401);
    }

    /**
     * Extract the message from the response.
     *
     * @return string
     */
    private function extractMessageFromResponse(): string
    {
        $contents = $this->json();
        if (is_string($contents)) {
            return $contents;
        }

        return $contents['message'] ?? '';
    }

    /**
     * Check if the API token has expired.
     *
     * @return bool
     */
    protected function apiTokenExpired(): bool
    {
        return $this->response->getHeaderLine('api-token-expired') === 'true';
    }

    /**
     * Morphs the JSON response to an array of objects.
     *
     * @template T of object
     *
     * @param class-string<T> $morphClass The class to morph the JSON to
     *
     * @return array<int, T> The array of morphed objects
     */
    protected function morphToArray(string $morphClass): array
    {
        return array_map(fn($item) => new $morphClass($item), $this->json());
    }

    /**
     * Convert the response to JSON.
     *
     * @return mixed The JSON response or empty array if invalid
     */
    protected function json(): mixed
    {
        $json = json_decode($this->contents([]), true);

        return json_last_error() === JSON_ERROR_NONE ? $json : [];
    }

    /**
     * Get the response of the request.
     *
     * @param mixed|null $default The default value to return
     *
     * @return string The response content
     */
    protected function contents(mixed $default = null): string
    {
        return $this->response?->getBody()->getContents() ?? $default;
    }

    /**
     * Morphs the JSON response to an object.
     *
     * @param string $morphClass The class to morph the JSON to
     *
     * @return mixed The morphed object
     */
    protected function morphTo(string $morphClass): mixed
    {
        return new $morphClass($this->json());
    }

    protected function requiresAuthentication(): bool
    {
        return true;
    }
}
