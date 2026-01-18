<?php

namespace OxygenSuite\OxygenErgani\Storage;

use DateTime;
use DateTimeImmutable;
use Error;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;

class FileToken extends Token
{
    private static ?string $customDirectory = null;

    private ?string $instanceDirectory = null;
    private string $filename;
    private ?AuthenticationToken $token = null;

    /**
     * @param string                    $username
     * @param string                    $password
     * @param array{cache_dir?: string} $options
     */
    public function __construct(string $username, string $password, array $options = [])
    {
        parent::__construct($username, $password);

        if (isset($options['cache_dir'])) {
            $this->instanceDirectory = rtrim($options['cache_dir'], '/\\');
        }
    }

    public function getAccessToken(): ?string
    {
        if (empty($this->token)) {
            $this->readFromFile();
        }

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

        $this->saveToFile();

        return $this;
    }

    public function failedAuthentication(): static
    {
        $this->token = null;
        $this->deleteFile();

        return $this;
    }

    public function authToken(): ?AuthenticationToken
    {
        return $this->token;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function generateFilename(): string
    {
        $defaultEnv = Client::getDefaultEnvironment();
        $env = $defaultEnv !== null ? $defaultEnv->name : '';

        return hash('sha256', $this->username . '-' . $this->password . '-' . $env);
    }

    public function saveToFile(): void
    {
        $directory = $this->getDirectory();

        if (!is_dir($directory)) {
            mkdir($directory, 0700, true);
        }

        $token = $this->token;
        $path = $this->path();

        file_put_contents($path, json_encode([
            'token' => [
                'accessToken' => $token->accessToken ?? null,
                'accessTokenExpiresAt' => $token->accessTokenExpiresAt?->getTimestamp() ?? null,
                'refreshToken' => $token->refreshToken ?? null,
                'refreshTokenExpiresAt' => $token->refreshTokenExpiresAt?->getTimestamp() ?? null,
            ],
        ]));

        chmod($path, 0600);
    }

    public function readFromFile(): void
    {
        if (! $this->fileExists()) {
            return;
        }

        $contents = file_get_contents($this->path());
        if ($contents === false) {
            return;
        }

        $data = json_decode($contents, true);
        if (empty($data)) {
            $this->deleteFile();

            throw new Error('Corrupted token file.');
        }

        $token = $data['token'] ?? [];
        $this->token = new AuthenticationToken();
        $this->token->accessToken = $token['accessToken'] ?? null;
        $this->token->accessTokenExpiresAt = (new DateTimeImmutable())->setTimestamp($token['accessTokenExpiresAt'] ?? null);
        $this->token->refreshToken = $token['refreshToken'] ?? null;
        $this->token->refreshTokenExpiresAt = (new DateTimeImmutable())->setTimestamp($token['refreshTokenExpiresAt'] ?? null);
    }

    public function deleteFile(): void
    {
        if ($this->fileExists()) {
            unlink($this->path());
        }
    }

    public function fileExists(): bool
    {
        $path = $this->path();

        return file_exists($path) && is_file($path);
    }

    /**
     * Set a custom directory for storing token files.
     *
     * For security, it's recommended to set this to a path outside
     * the web root (e.g., /var/app/storage/tokens).
     */
    public static function setDirectory(string $directory): void
    {
        self::$customDirectory = rtrim($directory, '/\\');
    }

    /**
     * Reset the directory to the default (.cache in package root).
     *
     * Primarily used for testing.
     */
    public static function resetDirectory(): void
    {
        self::$customDirectory = null;
    }

    public static function dir(): string
    {
        return self::$customDirectory ?? dirname(__DIR__, 2) . '/.cache';
    }

    /**
     * Get the directory for this instance.
     *
     * Priority: instance option > static setDirectory() > default
     */
    public function getDirectory(): string
    {
        return $this->instanceDirectory ?? self::dir();
    }

    public function path(): string
    {
        if (empty($this->filename)) {
            $this->filename = $this->generateFilename();
        }

        return $this->getDirectory() . '/' . $this->filename . '.json';
    }

    /**
     * Clears all cached tokens from the current directory.
     *
     * Safety: When using the default directory, only deletes if it ends with
     * '.cache'. When using a custom directory (via setDirectory), the safety
     * check is bypassed as the user has explicitly configured the path.
     */
    public static function forgetAllTokens(): void
    {
        $dir = self::dir();

        // Safety check: for default directory, ensure it ends with '.cache'
        // to prevent accidental deletion of project files during testing.
        // Custom directories bypass this check as they're explicitly configured.
        if (self::$customDirectory === null && !str_ends_with($dir, '.cache')) {
            return;
        }

        $files = glob($dir . '/*.json');
        if ($files === false) {
            return;
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * @param string                    $username
     * @param string                    $password
     * @param array{cache_dir?: string} $options
     */
    public static function fake(string $username, string $password, array $options = []): FakeFileToken
    {
        return new FakeFileToken($username, $password, $options);
    }
}
