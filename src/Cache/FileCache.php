<?php

namespace OxygenSuite\OxygenErgani\Cache;

use DateInterval;
use DateTime;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class FileCache implements CacheInterface
{
    private static ?string $customDirectory = null;

    private ?string $instanceDirectory = null;

    /**
     * @param array{cache_dir?: string} $options
     */
    public function __construct(array $options = [])
    {
        if (isset($options['cache_dir'])) {
            $this->instanceDirectory = rtrim($options['cache_dir'], '/\\');
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $path = $this->path($key);

        if (! file_exists($path) || ! is_file($path)) {
            return $default;
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return $default;
        }

        $entry = @unserialize($contents);
        if (! is_array($entry) || ! array_key_exists('data', $entry)) {
            $this->deleteFile($path);

            return $default;
        }

        if ($entry['expiry'] !== null && $entry['expiry'] < time()) {
            $this->deleteFile($path);

            return $default;
        }

        return $entry['data'];
    }

    public function set(string $key, mixed $value, int|DateInterval|null $ttl = null): bool
    {
        $seconds = $this->ttlToSeconds($ttl);

        if ($seconds !== null && $seconds <= 0) {
            return $this->delete($key);
        }

        $directory = $this->getDirectory();

        if (! is_dir($directory)) {
            mkdir($directory, 0700, true);
        }

        $entry = [
            'data' => $value,
            'expiry' => $seconds !== null ? time() + $seconds : null,
        ];

        $path = $this->path($key);
        $result = file_put_contents($path, serialize($entry));

        if ($result !== false) {
            chmod($path, 0600);
        }

        return $result !== false;
    }

    public function delete(string $key): bool
    {
        $this->deleteFile($this->path($key));

        return true;
    }

    public function clear(): bool
    {
        $directory = $this->getDirectory();

        if (! is_dir($directory)) {
            return true;
        }

        $files = glob($directory . '/*.cache');
        if ($files === false) {
            return true;
        }

        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }

    public function has(string $key): bool
    {
        return $this->get($key, $this) !== $this;
    }

    /**
     * @param iterable<string> $keys
     * @param mixed $default
     *
     * @return iterable<string, mixed>
     * @throws InvalidArgumentException
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    /**
     * @param iterable<string, mixed> $values
     * @param null|int|DateInterval $ttl
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public function setMultiple(iterable $values, int|DateInterval|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    /**
     * @param iterable<string> $keys
     *
     * @throws InvalidArgumentException
     */
    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    /**
     * Set a custom directory for storing cache files.
     *
     * For security, it's recommended to set this to a path outside
     * the web root.
     */
    public static function setDirectory(string $directory): void
    {
        self::$customDirectory = rtrim($directory, '/\\');
    }

    /**
     * Reset the directory to the default.
     *
     * Primarily used for testing.
     */
    public static function resetDirectory(): void
    {
        self::$customDirectory = null;
    }

    public static function dir(): string
    {
        return self::$customDirectory ?? dirname(__DIR__, 2) . '/.cache/data';
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

    /**
     * Remove all expired cache entries from the directory.
     *
     * @return int Number of expired entries removed
     */
    public function clearExpired(): int
    {
        $directory = $this->getDirectory();

        if (! is_dir($directory)) {
            return 0;
        }

        $files = glob($directory . '/*.cache');
        if ($files === false) {
            return 0;
        }

        $removed = 0;
        $now = time();

        foreach ($files as $file) {
            if (! is_file($file)) {
                continue;
            }

            $contents = file_get_contents($file);
            if ($contents === false) {
                continue;
            }

            $entry = @unserialize($contents);

            if (! is_array($entry) || ! array_key_exists('data', $entry)) {
                unlink($file);
                $removed++;

                continue;
            }

            if ($entry['expiry'] !== null && $entry['expiry'] < $now) {
                unlink($file);
                $removed++;
            }
        }

        return $removed;
    }

    private function path(string $key): string
    {
        return $this->getDirectory() . '/' . hash('sha256', $key) . '.cache';
    }

    private function deleteFile(string $path): void
    {
        if (file_exists($path) && is_file($path)) {
            unlink($path);
        }
    }

    private function ttlToSeconds(int|DateInterval|null $ttl): ?int
    {
        if ($ttl === null) {
            return null;
        }

        if ($ttl instanceof DateInterval) {
            return (int) (new DateTime())->setTimestamp(0)->add($ttl)->getTimestamp();
        }

        return $ttl;
    }
}
