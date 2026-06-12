<?php

namespace OxygenSuite\OxygenErgani\Traits;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Throwable;

trait HasAttributes
{
    /** @var array<string, mixed> */
    protected array $attributes = [];

    /**
     * Retrieves the attributes of the model.
     *
     * @return array<string, mixed>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    /**
     * Retrieves the value associated with the given key from the data array.
     * If the key does not exist, returns the provided default value.
     *
     * @param string $key The key to look up in the data array.
     * @param string|null $default The default value to return if the key is not found.
     *
     * @return mixed The value associated with the given key or the default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * Sets the value associated with the given key in the data array.
     * If the key does not exist, it will be created.
     *
     * @param string $key The key to associate with the value.
     * @param mixed $value The value to associate with the key.
     *
     * @return $this
     */
    public function set(string $key, mixed $value): static
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Retrieves a datetime value associated with the given key.
     * If the key does not exist, returns the provided default value or null.
     *
     * @param string $key The key to look up in the data array.
     * @param string|null $format The format to use when creating the DateTime object.
     * @param string|null $timezone The timezone to use when creating the DateTime object.
     * @param mixed $default The default value to return if the key is not found or invalid.
     *
     * @return DateTimeImmutable|null The DateTime object created from the value, or null if not found or invalid.
     */
    public function date(string $key, ?string $format = null, ?string $timezone = null, mixed $default = null): ?DateTimeInterface
    {
        $datetime = $this->string($key, $default);
        if ($datetime === null) {
            return $default;
        }

        try {
            $tz = $timezone !== null ? new DateTimeZone($timezone) : null;

            if ($format === null) {
                return new DateTimeImmutable($datetime, $tz);
            }

            $result = DateTimeImmutable::createFromFormat($format, $datetime, $tz);

            return $result !== false ? $result : $default;
        } catch (Throwable) {
            return $default;
        }
    }

    /**
     * Retrieves a string value associated with the given key. If the value is not a string, returns null.
     *
     * @param string $key The key to look up in the data source.
     * @param mixed $default The default value to return if the key is not found.
     *
     * @return string|null The string value associated with the given key, or null if it is not a string.
     */
    public function string(string $key, mixed $default = null): ?string
    {
        $value = $this->get($key, $default);

        return is_string($value) ? $value : null;
    }

    /**
     * Retrieves a boolean value associated with the specified key or null on failure.
     * If the key does not exist, the provided default value will be used.
     *
     * @param string $key The key for the value to be retrieved.
     * @param mixed $default The default value to return if the key does not exist.
     *
     * @return ?bool Returns the boolean value if valid, null if the value cannot be determined as boolean.
     */
    public function bool(string $key, mixed $default = null): ?bool
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Retrieves an integer value associated with the specified key or null on failure.
     * If the key does not exist, the provided default value will be used.
     *
     * @param string $key The key for the value to be retrieved.
     * @param mixed $default The default value to return if the key does not exist.
     *
     * @return ?int Returns the integer value if valid, null if the value cannot be determined as an integer.
     */
    public function int(string $key, mixed $default = null): ?int
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Retrieves a floating-point value associated with the specified key or null on failure.
     * If the key does not exist, the provided default value will be used.
     *
     * @param string $key The key for the value to be retrieved.
     * @param mixed $default The default value to return if the key does not exist.
     *
     * @return ?float Returns the floating-point value if valid, null if the value cannot be determined as a float.
     */
    public function float(string $key, mixed $default = null): ?float
    {
        return filter_var($this->get($key, $default), FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
    }

    /**
     * Retrieves a float value from a Greek-formatted decimal string (e.g., "1.500,00").
     * Handles both Greek format strings and raw numeric values.
     *
     * @param string $key The key for the value to be retrieved.
     * @param mixed $default The default value to return if the key does not exist.
     *
     * @return ?float Returns the float value, or null if empty/not set.
     */
    public function greekFloat(string $key, mixed $default = null): ?float
    {
        $value = $this->get($key, $default);

        if ($value === null || $value === '') {
            return null;
        }

        if (is_string($value)) {
            // Parse Greek format: "1.500,00" → 1500.00
            $value = str_replace('.', '', $value);  // Remove thousands separator
            $value = str_replace(',', '.', $value); // Convert decimal comma to dot
        }

        return (float) $value;
    }

    /**
     * Transforms an array of values associated with the specified key to a new array
     * of objects of the given type.
     *
     * @template T of object
     *
     * @param string $key The key for the array to be retrieved.
     * @param class-string<T> $type The fully qualified class name to which the values should be mapped.
     *
     * @return array<int, T>
     */
    public function morphToArray(string $key, string $type): array
    {
        $value = $this->array($key, []);

        return array_map(fn($value) => new $type($value), $value);
    }

    /**
     * Retrieves an array value associated with the specified key or returns the default value if the key does not exist.
     * If the retrieved value is not an array, the provided default value will be returned.
     *
     * @param string $key The key for the value to be retrieved.
     * @param mixed $default The default value to return if the key does not exist or the value is not an array.
     *
     * @return array<mixed>|null Returns the array value if valid, or the default value if the key does not exist or the value is not an array.
     */
    public function array(string $key, mixed $default = null): ?array
    {
        $value = $this->get($key, $default);

        return is_array($value) ? $value : $default;
    }

    /**
     * Retrieves the current date and time as a DateTime object.
     *
     * @param string $tz
     *
     * @return DateTime|null
     */
    public function now(string $tz = 'UTC'): ?DateTime
    {
        try {
            return new DateTime('now', new DateTimeZone($tz));
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Returns the attributes of the model as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }
}
