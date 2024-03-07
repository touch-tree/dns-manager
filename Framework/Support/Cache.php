<?php

namespace Framework\Support;

/**
 * The Cache class provides caching using the APCu extension.
 *
 * This class allows you to store and retrieve data from the APCu cache in shared memory.
 *
 * This class should be used in environments where the APCu extension is available and enabled.
 * Ensure that APCu is installed and configured in your PHP environment before using this class.
 *
 * @package Framework\Support
 */
class Cache
{
    /**
     * Get an item from the cache.
     *
     * @param string $key The unique identifier for the cached item.
     * @param mixed $default The default value to return if the item is not found in the cache.
     * @return mixed The cached item value or the default value if the item is not found.
     */
    public static function get(string $key, $default = null)
    {
        $result = apcu_fetch($key, $success);

        return $success ? $result : $default;
    }

    /**
     * Store an item in the cache.
     *
     * @param string $key The unique identifier for the cached item.
     * @param mixed $value The value to be stored in the cache.
     * @param int $ttl The time-to-live for the cached item in seconds.
     * @return void
     */
    public static function put(string $key, $value, int $ttl)
    {
        apcu_store($key, $value, $ttl);
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param string $key The unique identifier for the cached item.
     * @return bool True if the item exists in the cache, false otherwise.
     */
    public static function has(string $key): bool
    {
        return apcu_exists($key);
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key The unique identifier for the cached item to be removed.
     * @return bool True if the item was successfully removed, false otherwise.
     */
    public static function forget(string $key): bool
    {
        return apcu_delete($key);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param string $key The unique identifier for the cached item.
     * @param int $value The value to increment the cached item by.
     * @return int|bool The new value of the cached item or false on failure.
     */
    public static function increment(string $key, int $value = 1)
    {
        return apcu_inc($key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param string $key The unique identifier for the cached item.
     * @param int $value The value to decrement the cached item by.
     * @return int|bool The new value of the cached item or false on failure.
     */
    public static function decrement(string $key, int $value = 1)
    {
        return apcu_dec($key, $value);
    }
}
