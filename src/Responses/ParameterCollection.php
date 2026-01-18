<?php

namespace OxygenSuite\OxygenErgani\Responses;

/**
 * A collection of ParameterResponse objects with convenient lookup methods.
 *
 * @extends Collection<string, ParameterResponse>
 */
class ParameterCollection extends Collection
{
    protected function getItemKey(object $item): ?string
    {
        /** @var ParameterResponse $item */
        return $item->code;
    }

    /**
     * Search for parameters by description (case-insensitive partial match).
     *
     * @param string $query The search query
     *
     * @return self A new collection with matching parameters
     */
    public function search(string $query): self
    {
        $query = mb_strtolower($query);

        return $this->filter(
            fn(ParameterResponse $param)
                => $param->description !== null
                && mb_strpos(mb_strtolower($param->description), $query) !== false,
        );
    }

    /**
     * Get an array suitable for HTML dropdowns/selects.
     *
     * @return array<string, string> [code => description]
     */
    public function toDropdown(): array
    {
        return array_map(fn($item) => $item->description ?? '', $this->items);
    }

    /**
     * Get all parameter codes.
     *
     * @return array<int, string>
     */
    public function codes(): array
    {
        return $this->keys();
    }

    /**
     * @param string $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        /** @infection-ignore-all Equivalent mutant: PHP auto-coerces to string */
        return $this->has((string) $offset);
    }

    /**
     * @param string $offset
     */
    public function offsetGet(mixed $offset): ?ParameterResponse
    {
        /** @infection-ignore-all Equivalent mutant: PHP auto-coerces to string */
        return $this->find((string) $offset);
    }

    public function find(string|int $key): ?ParameterResponse
    {
        return $this->items[$key] ?? null;
    }

    public function first(): ?ParameterResponse
    {
        $first = reset($this->items);

        return $first !== false ? $first : null;
    }

    public function last(): ?ParameterResponse
    {
        $last = end($this->items);

        return $last !== false ? $last : null;
    }
}
