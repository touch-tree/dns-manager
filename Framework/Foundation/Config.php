<?php

namespace Framework\Foundation;

use Error;
use Framework\Support\Collector;

/**
 * The Config class provides a simple configuration management system.
 *
 * @package Framework\Foundation
 */
class Config
{
    /**
     * The array of configuration values.
     *
     * @var array
     */
    private static array $items = [];

    /**
     * Get the entire configuration array.
     *
     * @return array
     */
    public static function all(): array
    {
        return self::$items;
    }

    /**
     * Set multiple configuration values at runtime.
     *
     * @param array $keys
     */
    public static function set_many(array $keys)
    {
        foreach ($keys as $key => $value) {
            Collector::set(self::$items, $key, $value);
        }
    }

    /**
     * Get the value of a configuration key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        return Collector::get(self::$items, $key, $default);
    }

    /**
     * Check if a configuration key exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return Collector::has(self::$items, $key);
    }

    /**
     * Set a configuration value at runtime.
     *
     * @param string $key
     * @param mixed $value
     * @return Config
     */
    public static function set(string $key, $value): Config
    {
        Collector::set(self::$items, $key, $value);

        return new self();
    }
}