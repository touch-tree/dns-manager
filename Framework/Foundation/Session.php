<?php

namespace Framework\Foundation;

use Error;
use Framework\Support\Collector;

/**
 * The Session class provides a simple interface for working with session data.
 * It includes methods for flashing data, retrieving data, and checking if a key exists in the session.
 *
 * @package Framework\Foundation
 */
class Session
{
    /**
     * Flash a key-value pair to the session using 'dot' notation.
     *
     * @param string $key The key to flash to the session.
     * @param mixed $value The value to associate with the key.
     * @return Session The current Session instance.
     */
    public function flash(string $key, $value): Session
    {
        Collector::set($_SESSION, 'flash.' . $key, $value);

        return $this;
    }

    /**
     * Get the value of a key from the session and remove it using 'dot' notation.
     *
     * @param string $key The key to retrieve and remove from the session.
     * @param mixed $default [optional] The default value to return if the key is not found.
     * @return mixed|null The value associated with the key, or null if the key is not found.
     */
    public function pull(string $key, $default = null)
    {
        $value = $this->get($key, $default);

        Collector::forget($_SESSION, $key);

        return $value;
    }

    /**
     * Get the value of a key from the session using 'dot' notation.
     *
     * @param string $key The key to retrieve from the session.
     * @param mixed $default [optional] The default value to return if the key is not found.
     * @return mixed The value associated with the key, or the default value if the key is not found.
     */
    public function get(string $key, $default = null)
    {
        return Collector::get($_SESSION, $key, $default);
    }

    /**
     * Push a value onto the end of an array using 'dot' notation.
     *
     * @param string $key The key in 'dot' notation.
     * @param mixed $value The value to push onto the array.
     * @return Session The current Session instance.
     *
     * @throws Error If the session value at the specified key is not an array.
     */
    public function push(string $key, $value): Session
    {
        if (!is_array($this->get($key))) {
            throw new Error('Session value at ' . $key . ' is not an array');
        }

        Collector::push($_SESSION, $key, $value);

        return $this;
    }

    /**
     * Set a key-value pair or multiple key-value pairs in the session using 'dot' notation.
     *
     * @param string|array $key The key or array of key-value pairs to set in the session.
     * @param mixed $value [optional] The value to associate with the key if a single key is provided.
     * @return Session The current Session instance.
     */
    public function put($key, $value = null): Session
    {
        if (is_string($key) && !is_null($value)) {
            Collector::set($_SESSION, $key, $value);
        }

        if (is_array($key) && is_null($value)) {
            foreach ($key as $k => $v) {
                Collector::set($_SESSION, $k, $v);
            }
        }

        return $this;
    }

    /**
     * Determine if a key exists in the session using 'dot' notation.
     *
     * @param string $key The key to check for existence in the session.
     * @return bool true if the key exists in the session, false otherwise.
     */
    public function has(string $key): bool
    {
        return Collector::has($_SESSION, $key);
    }

    /**
     * Remove one or more array items from the session using 'dot' notation.
     *
     * @param string|array $key The key or array of keys to remove from the session.
     * @return Session The current Session instance.
     */
    public function forget($key): Session
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                Collector::forget($_SESSION, $k);
            }
        }

        if (is_string($key)) {
            Collector::forget($_SESSION, $key);
        }

        return $this;
    }

    /**
     * Regenerate the session ID.
     *
     * @return Session The current Session instance.
     */
    public function regenerate(): Session
    {
        session_regenerate_id();

        return $this;
    }

    /**
     * Retrieve all session data as an associative array.
     *
     * @return array The associative array containing all session data.
     */
    public function all(): array
    {
        return $_SESSION;
    }

    /**
     * Start session.
     *
     * @return bool true when the session started successfully, else false.
     */
    public static function start(): bool
    {
        return session_start();
    }
}
