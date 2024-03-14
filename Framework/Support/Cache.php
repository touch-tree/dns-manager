<?php

namespace Framework\Support;

/**
 * The Cache class provides file-based caching for storing and retrieving data from the filesystem.
 *
 * This class offers methods to interact with the cache, including storing, retrieving, checking existence,
 * and removing items from the cache. It supports setting a time-to-live (TTL) for cached items and provides methods
 * for incrementing and decrementing cached values.
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
     * @return mixed|null The cached item value or the default value if the item is not found.
     */
    public static function get(string $key, $default = null)
    {
        if (!file_exists($file = self::get_cache_file($key))) {
            return $default;
        }

        if (self::is_expired($cached = unserialize(file_get_contents($file)))) {
            self::forget($key);
            return $default;
        }

        return $cached['value'];
    }

    /**
     * Check if a cached item is expired.
     *
     * @param array $cached The cached item data.
     * @return bool true if the item is expired, false otherwise.
     */
    private static function is_expired(array $cached): bool
    {
        return $cached['expires'] > 0 && $cached['expires'] < time();
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
        $file = self::get_cache_file($key);

        if (!file_exists($file)) {
            touch($file);
        }

        file_put_contents($file, serialize(
            [
                'expires' => $ttl > 0 ? time() + $ttl : 0,
                'value' => $value,
            ]
        ));
    }

    /**
     * Determine if an item exists in the cache.
     *
     * @param string $key The unique identifier for the cached item.
     * @return bool true if the item exists in the cache, false otherwise.
     */
    public static function has(string $key): bool
    {
        return file_exists(self::get_cache_file($key));
    }

    /**
     * Remove an item from the cache.
     *
     * @param string $key The unique identifier for the cached item to be removed.
     * @return bool true if the item was successfully removed, false otherwise.
     */
    public static function forget(string $key): bool
    {
        if (file_exists($file = self::get_cache_file($key))) {
            return unlink($file);
        }

        return false;
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
        self::put($key, $new = self::get($key, 0) + $value, 0);

        return $new;
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
        return self::increment($key, -$value);
    }

    /**
     * Clear all entries from the cache.
     *
     * @return void
     */
    public static function clear()
    {
        foreach (glob(storage_path('Framework/cache') . '/*.cache') as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Get the filename for the cache key.
     *
     * @param string $key The cache key.
     * @return string The filename for the cache key.
     */
    protected static function get_cache_file(string $key): string
    {
        if (!file_exists($path = storage_path('Framework/cache'))) {
            mkdir($path, 0777, true);
        }

        return $path . '/' . md5($key) . '.cache';
    }
}
