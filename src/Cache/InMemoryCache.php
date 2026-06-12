<?php

namespace OxygenSuite\OxygenErgani\Cache;

use DateInterval;
use DateTime;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

class InMemoryCache implements CacheInterface
{
    /** @var array<string, array{data: mixed, expiry: int|null}> */
    private array $store = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if (! isset($this->store[$key])) {
            return $default;
        }

        $entry = $this->store[$key];

        if ($entry['expiry'] !== null && $entry['expiry'] < time()) {
            unset($this->store[$key]);

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

        $this->store[$key] = [
            'data' => $value,
            'expiry' => $seconds !== null ? time() + $seconds : null,
        ];

        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->store[$key]);

        return true;
    }

    public function clear(): bool
    {
        $this->store = [];

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
     * Remove all expired cache entries.
     *
     * @return int Number of expired entries removed
     */
    public function clearExpired(): int
    {
        $removed = 0;
        $now = time();

        foreach ($this->store as $key => $entry) {
            if ($entry['expiry'] !== null && $entry['expiry'] < $now) {
                unset($this->store[$key]);
                $removed++;
            }
        }

        return $removed;
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
