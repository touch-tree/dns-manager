<?php

namespace Framework\Foundation;

use Framework\Support\Collector;

/**
 * The Config class provides a simple configuration management system.
 *
 * This class allows setting, getting, and checking the existence of configuration values.
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
     * @return array The array of configuration values.
     */
    public static function all(): array
    {
        return self::$items;
    }

    /**
     * Set multiple configuration values at runtime.
     *
     * @param array $keys An associative array of configuration keys and their values.
     * @return void
     */
    public static function set_many(array $keys): void
    {
        foreach ($keys as $key => $value) {
            Collector::set(self::$items, $key, $value);
        }
    }

    /**
     * Get the value of a configuration key.
     *
     * @param string $key The configuration key.
     * @param mixed $default [optional] The default value to return if the key does not exist.
     * @return mixed The value of the configuration key, or the default value if the key does not exist.
     */
    public static function get(string $key, $default = null)
    {
        return Collector::get(self::$items, $key, $default);
    }

    /**
     * Check if a configuration key exists.
     *
     * @param string $key The configuration key.
     * @return bool true if the configuration key exists, false otherwise.
     */
    public static function has(string $key): bool
    {
        return Collector::has(self::$items, $key);
    }

    /**
     * Set a configuration value at runtime.
     *
     * @param string $key The configuration key.
     * @param mixed $value The value to set for the configuration key.
     * @return Config The Config instance for method chaining.
     */
    public static function set(string $key, $value): Config
    {
        Collector::set(self::$items, $key, $value);

        return new self();
    }
}
