<?php

namespace OxygenSuite\OxygenErgani\Responses;

/**
 * A collection of BranchResponse objects with convenient lookup methods.
 *
 * @extends Collection<string, BranchResponse>
 */
class BranchCollection extends Collection
{
    protected function getItemKey(object $item): ?string
    {
        /** @var BranchResponse $item */
        return $item->aa;
    }

    /**
     * Search for branches by address (case-insensitive partial match).
     *
     * @param string $query The search query
     *
     * @return self A new collection with matching branches
     */
    public function search(string $query): self
    {
        $query = mb_strtolower($query);

        return $this->filter(
            fn(BranchResponse $branch)
                => $branch->address !== null
                && mb_strpos(mb_strtolower($branch->address), $query) !== false,
        );
    }

    /**
     * Get an array suitable for HTML dropdowns/selects.
     *
     * @return array<string, string> [aa => address]
     */
    public function toDropdown(): array
    {
        return array_map(fn($item) => $item->address ?? '', $this->items);
    }

    /**
     * @param string $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->has((string) $offset);
    }

    /**
     * @param string $offset
     */
    public function offsetGet(mixed $offset): ?BranchResponse
    {
        return $this->find((string) $offset);
    }

    public function find(string|int $key): ?BranchResponse
    {
        return $this->items[$key] ?? null;
    }

    public function first(): ?BranchResponse
    {
        $first = reset($this->items);

        return $first !== false ? $first : null;
    }

    public function last(): ?BranchResponse
    {
        $last = end($this->items);

        return $last !== false ? $last : null;
    }
}
