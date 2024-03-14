<?php

namespace Framework\Support;

use ArrayAccess;

/**
 * The Collector class provides utility functions for navigating associative arrays using dot notation.
 *
 * This class offers methods to efficiently access, set, check, add, remove, and manipulate array elements
 * within nested arrays using dot notation. It also provides methods for extracting subsets of arrays,
 * plucking values, and collapsing arrays.
 *
 * @package Framework\Support
 */
class Collector
{
    /**
     * Get an item from an array using 'dot' notation.
     *
     * @param array $array The array from which to retrieve the item.
     * @param string $key The key in dot notation.
     * @param mixed $default [optional] The default value to return if the key is not found.
     * @return mixed|null The value corresponding to the key, or the default value if the key is not found.
     */
    public static function get(array $array, string $key, $default = null)
    {
        if (!static::accessible($array)) {
            return $default;
        }

        if (static::exists($array, $key)) {
            return $array[$key];
        }

        foreach (self::explode_key($key) as $segment) {
            if (static::accessible($array) && static::exists($array, $segment)) {
                $array = $array[$segment];
                continue;
            }

            return $default;
        }

        return $array;
    }

    /**
     * Determine if the given key exists in the provided array.
     *
     * @param array $array The array to check.
     * @param string $key The key to check for.
     * @return bool true if the key exists in the array, false otherwise.
     */
    protected static function exists(array $array, string $key): bool
    {
        return array_key_exists($key, $array);
    }

    /**
     * Determine if the given value is array accessible.
     *
     * @param mixed $value The value to check.
     * @return bool true if the value is array accessible, false otherwise.
     */
    protected static function accessible($value): bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Set an array item to a given value using 'dot' notation.
     *
     * @param array $array The array to modify.
     * @param string $key The key in dot notation.
     * @param mixed $value The value to set.
     * @return $this
     */
    public static function set(array &$array, string $key, $value): Collector
    {
        foreach (self::explode_key($key) as $segment) {
            if (!isset($array[$segment]) || !is_array($array[$segment])) {
                $array[$segment] = [];
            }

            $array = &$array[$segment];
        }

        $array = $value;

        return new self();
    }

    /**
     * Check if the given key exists in the provided array.
     *
     * @param array $array The array to check.
     * @param string $key The key to check for.
     * @return bool true if the key exists in the array, false otherwise.
     */
    public static function has(array $array, string $key): bool
    {
        return !is_null(static::get($array, $key));
    }

    /**
     * Add an element to an array using 'dot' notation if it doesn't exist.
     *
     * @param array $array The array to modify.
     * @param string $key The key in dot notation.
     * @param mixed $value The value to add.
     * @return array The modified array.
     */
    public static function add(array $array, string $key, $value): array
    {
        if (!static::has($array, $key)) {
            $array = static::set($array, $key, $value);
        }

        return $array;
    }

    /**
     * Get every item of the array except for a specified array of items.
     *
     * @param array $array The array to filter.
     * @param array|string $keys The keys to exclude.
     * @return array The filtered array.
     */
    public static function except(array $array, $keys): array
    {
        return array_diff_key($array, array_flip((array)$keys));
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array $array The array to filter.
     * @param array|string $keys The keys to include.
     * @return array The filtered array.
     */
    public static function only(array $array, $keys): array
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param array $array The array to pluck from.
     * @param string|array $value The value(s) to pluck.
     * @param string|null $key [optional] The key to use as the array keys.
     * @return array The plucked array.
     */
    public static function pluck(array $array, $value, string $key = null): array
    {
        $results = [];

        foreach ($array as $item) {
            $item_value = is_array($item) ? static::get($item, $value) : null;

            if (is_null($key)) {
                $results[] = $item_value;
            } else {
                $item_key = is_array($item) ? static::get($item, $key) : null;
                $results[$item_key] = $item_value;
            }
        }

        return $results;
    }

    /**
     * Remove an array item from a given key using "dot" notation.
     *
     * @param array $array The array to modify.
     * @param string $key The key in dot notation.
     * @return array The modified array.
     */
    public static function forget(array &$array, string $key): array
    {
        $keys = self::explode_key($key);
        $last_key = array_pop($keys);

        foreach ($keys as $segment) {
            if (!isset($array[$segment]) || !is_array($array[$segment])) {
                return $array;
            }

            $array = &$array[$segment];
        }

        unset($array[$last_key]);

        return $array;
    }

    /**
     * Collapse an array of arrays into a single array.
     *
     * @param array $array The array to collapse.
     * @return array The collapsed array.
     */
    public static function collapse(array $array): array
    {
        $results = [];

        foreach ($array as $values) {
            if (is_array($values)) {
                $results = array_merge($results, $values);
            }
        }

        return $results;
    }

    /**
     * Explode a key string into an array of segments using 'dot' notation.
     *
     * @param string $key The key to explode.
     * @return array The exploded key segments.
     */
    private static function explode_key(string $key): array
    {
        return explode('.', $key);
    }
}