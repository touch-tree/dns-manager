<?php

namespace App\Framework\Support;

/**
 * This class represents a collection of items, supporting both indexed and associative arrays.
 *
 * @template T
 * @package App\Framework\Support
 */
class Collection
{
    /**
     * The collection of items.
     *
     * @var array<T>
     */
    private array $items;

    /**
     * Collection constructor.
     *
     * @param array<T> $items The initial items for the collection.
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Get all items in the collection.
     *
     * @return array<T> The items in the collection.
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Get an item by key from the collection.
     *
     * @param string|int $key The key of the item to retrieve.
     * @param T $default The default value if the key does not exist.
     * @return T The item value or the default value if the key does not exist.
     */
    public function get($key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }

    /**
     * Set an item in the collection with the specified key.
     *
     * @param string|int $key The key of the item to set.
     * @param T $value The value of the item.
     * @return Collection<T>
     */
    public function set($key, $value): Collection
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param string|int $key The key of the item to remove.
     * @return Collection<T>
     */
    public function forget($key): Collection
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     * Apply a callback to each item in the collection and return a new collection.
     *
     * @param callable(T): mixed $callback The callback function to apply to each item.
     * @return Collection<T> A new collection with the results of the callback.
     */
    public function map(callable $callback): Collection
    {
        return new self(array_map($callback, $this->items));
    }

    /**
     * Filter the collection using a callback and return a new collection.
     *
     * @param callable(T): bool $callback The callback function to use for filtering.
     * @return Collection<T> A new collection containing only the items that passed the filter.
     */
    public function filter(callable $callback): Collection
    {
        return new self(array_filter($this->items, $callback));
    }

    /**
     * Convert the collection to a plain array.
     *
     * @return array<T> The collection items as a plain array.
     */
    public function to_array(): array
    {
        return $this->items;
    }
}
