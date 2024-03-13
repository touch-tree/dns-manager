<?php

namespace Framework\Support;

/**
 * The Collection class represents a generic collection of items.
 *
 * This class provides a set of methods to manipulate and interact with the collection's items,
 * such as retrieving, setting, and removing items, as well as applying transformations and filters.
 * It supports iteration, mapping, filtering, and retrieval of the first and last items in the collection.
 *
 * @template T
 * @package Framework\Support
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
     * Get the first item in the collection.
     *
     * @return T|null The first item in the collection or null if the collection is empty.
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * Get the last item in the collection.
     *
     * @return T|null The last item in the collection or null if the collection is empty.
     */
    public function last()
    {
        return end($this->items);
    }
}
