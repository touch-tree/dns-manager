<?php

namespace Framework\Foundation;

use Error;

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
    private static array $config = [];

    /**
     * Load the configuration values from the file.
     *
     * @param string $path
     * @return void
     *
     * @throws Error Unable to find config file.
     */
    public static function resolve(string $path)
    {
        if (!file_exists($path)) {
            throw new Error('Unable to import configuration due to not being able to find file: ' . $path);
        }

        $config = @include $path;

        foreach ($config as $key => $value) {
            if (!empty($value)) {
                $config[$key] = $value;
            }
        }

        self::$config = array_merge(self::$config, $config);
    }

    /**
     * Get the entire configuration array.
     *
     * @return array
     */
    public static function all(): array
    {
        return self::$config;
    }

    /**
     * Set multiple configuration values at runtime.
     *
     * @param array $values
     */
    public static function set_many(array $values)
    {
        self::$config = array_merge(self::$config, $values);
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
        return self::$config[$key] ?? $default;
    }

    /**
     * Check if a configuration key exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has(string $key): bool
    {
        return isset(self::$config[$key]);
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
        self::$config[$key] = $value;

        return new self();
    }
}