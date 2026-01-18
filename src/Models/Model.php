<?php

namespace OxygenSuite\OxygenErgani\Models;

use BackedEnum;
use OxygenSuite\OxygenErgani\Traits\HasAttributes;

/**
 * @phpstan-consistent-constructor
 */
class Model
{
    use HasAttributes;

    /** @var array<int, string> */
    protected array $expectedOrder = [];

    /** @var array<string, mixed> Custom defaults for specific keys (overrides empty string) */
    protected array $defaults = [];

    /** @var array<string, string> Cast types for output formatting (e.g., 'greek_float') */
    protected array $casts = [];

    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * Create a new model instance.
     *
     * @param array<string, mixed> $attributes The model attributes
     *
     * @return static
     */
    public static function make(array $attributes = []): static
    {
        return new static($attributes);
    }

    /**
     * Fill all expected fields with defaults.
     *
     * Any field in $expectedOrder that hasn't been set will be filled with
     * an empty string, unless a custom default is specified in $defaults.
     *
     */
    public function withDefaults(): static
    {
        foreach ($this->expectedOrder as $key) {
            if (! array_key_exists($key, $this->attributes)) {
                $this->attributes[$key] = $this->defaults[$key] ?? '';
            }
        }

        return $this;
    }

    /**
     * Returns the model attributes sorted according to the expected order.
     *
     * @return array<string, mixed>
     */
    public function toSortedArray(): array
    {
        if (empty($this->expectedOrder)) {
            return $this->toArray();
        }

        $sortedAttributes = [];
        foreach ($this->expectedOrder as $key) {
            if (array_key_exists($key, $this->attributes)) {
                $value = $this->attributes[$key];

                // Apply output cast if defined
                if (isset($this->casts[$key])) {
                    $value = $this->castForOutput($value, $this->casts[$key]);
                }

                $sortedAttributes[$key] = $value;
            }
        }

        return $this->processValue($sortedAttributes, true);
    }

    /**
     * Cast a value for API output.
     *
     * Supports parameterized casts like 'greek_float:1' where the number
     * after the colon specifies precision (default: 2).
     */
    protected function castForOutput(mixed $value, string $cast): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        // Parse cast type and parameters (e.g., 'greek_float:1')
        $parts = explode(':', $cast);
        $castType = $parts[0];

        return match ($castType) {
            'greek_float' => $this->formatGreekFloat($value, (int) ($parts[1] ?? 2)),
            default => $value,
        };
    }

    /**
     * Format a numeric value as Greek decimal (dot thousands, comma decimal).
     */
    protected function formatGreekFloat(mixed $value, int $precision): string|int|float
    {
        if (! is_numeric($value)) {
            return $value;
        }

        return number_format((float) $value, $precision, ',', '.');
    }

    /**
     * Converts the object and its attributes into an associative array.
     *
     * @return array<string, mixed> An array representation of the object attributes.
     */
    public function toArray(): array
    {
        return $this->processValue($this->attributes());
    }

    /**
     * Processes a value to convert it to array format.
     *
     * @param mixed $value The value to process
     * @param bool  $sort  Whether to sort the value
     *
     * @return mixed The processed value
     */
    protected function processValue(mixed $value, bool $sort = false): mixed
    {
        return match (true) {
            $value instanceof self => $sort ? $value->toSortedArray() : $value->toArray(),
            $value instanceof BackedEnum => $value->value,
            is_array($value) => array_map(fn($item) => $this->processValue($item, $sort), $value),
            default => $value,
        };
    }
}
