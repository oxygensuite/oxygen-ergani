<?php

namespace OxygenSuite\OxygenErgani\Responses;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Abstract collection class with convenient lookup methods.
 *
 * @template TKey of string|int
 * @template TValue of object
 *
 * @implements IteratorAggregate<TKey, TValue>
 * @implements ArrayAccess<TKey, TValue|null>
 */
abstract class Collection implements Countable, IteratorAggregate, ArrayAccess
{
    /** @var array<TKey, TValue> Items keyed for O(1) lookup */
    protected array $items = [];

    /**
     * Get the key for an item.
     *
     * @param TValue $item
     *
     * @return TKey|null
     */
    abstract protected function getItemKey(object $item): string|int|null;

    /**
     * @param array<int|string, TValue> $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $key = $this->getItemKey($item);
            if ($key !== null) {
                $this->items[$key] = $item;
            }
        }
    }

    /**
     * Filter the collection using a callback.
     *
     * @param callable(TValue): bool $callback
     *
     * @return static A new collection with items that pass the filter
     */
    public function filter(callable $callback): static
    {
        $filtered = array_filter($this->items, $callback);

        $collection = clone $this;
        $collection->items = $filtered;

        return $collection;
    }

    /**
     * Get all keys.
     *
     * @return array<int, TKey>
     */
    public function keys(): array
    {
        return array_keys($this->items);
    }

    /**
     * Get all items as an array.
     *
     * @return array<TKey, TValue>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get the first item in the collection.
     *
     * @return TValue|null
     */
    public function first(): ?object
    {
        $first = reset($this->items);

        return $first !== false ? $first : null;
    }

    /**
     * Get the last item in the collection.
     *
     * @return TValue|null
     */
    public function last(): ?object
    {
        $last = end($this->items);

        return $last !== false ? $last : null;
    }

    /**
     * Check if the collection is empty.
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Check if the collection is not empty.
     */
    public function isNotEmpty(): bool
    {
        return !empty($this->items);
    }

    /**
     * Get the number of items in the collection.
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Get an iterator for the collection.
     *
     * @return Traversable<TKey, TValue>
     */
    public function getIterator(): Traversable
    {
        yield from $this->items;
    }

    /**
     * Check if an offset exists (ArrayAccess).
     *
     * @param TKey $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Check if an item with the given key exists.
     *
     * @param TKey $key
     */
    public function has(string|int $key): bool
    {
        return isset($this->items[$key]);
    }

    /**
     * Get an item by offset (ArrayAccess).
     *
     * @param TKey $offset
     *
     * @return TValue|null
     */
    public function offsetGet(mixed $offset): ?object
    {
        return $this->find($offset);
    }

    /**
     * Find an item by its key.
     *
     * @param TKey $key
     *
     * @return TValue|null
     */
    public function find(string|int $key): ?object
    {
        return $this->items[$key] ?? null;
    }

    /**
     * Set is not supported - collection is read-only.
     *
     * @param TKey $offset
     * @param TValue $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        // Collection is read-only after construction
    }

    /**
     * Unset is not supported - collection is read-only.
     *
     * @param TKey $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        // Collection is read-only after construction
    }
}
