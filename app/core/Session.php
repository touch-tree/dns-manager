<?php

namespace App\Core;

/**
 * The Session class provides a simple interface for working with session data.
 * It includes methods for flashing data, retrieving data, and checking if a
 * key exists in the session.
 *
 * @package App\Core
 */
class Session
{
    /**
     * Flash a key-value pair to the session.
     *
     * @param string $key The key to flash to the session.
     * @param mixed $value The value to associate with the key.
     * @return Session The current Session instance.
     */
    public function flash(string $key, $value): Session
    {
        $_SESSION[$key] = $value;

        return $this;
    }

    /**
     * Get the value of a key from the session and remove it.
     *
     * @param string $key The key to retrieve and remove from the session.
     * @param mixed $default The default value to return if the key is not found.
     * @return mixed|null The value associated with the key, or null if the key is not found.
     */
    public function pull(string $key, $default = null)
    {
        $value = $this->get($key, $default);
        unset($_SESSION[$key]);

        return $value;
    }

    /**
     * Get the value of a key from the session.
     *
     * @param string $key The key to retrieve from the session.
     * @param mixed $default The default value to return if the key is not found.
     * @return mixed The value associated with the key, or the default value if the key is not found.
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a key-value pair or multiple key-value pairs in the session.
     *
     * @param string|array $key The key or array of key-value pairs to set in the session.
     * @param mixed $value The value to associate with the key if a single key is provided.
     * @return Session The current Session instance.
     */
    public function put($key, $value = null): Session
    {
        if (!is_array($key)) {
            $_SESSION[$key] = $value;

            return $this;
        }

        foreach ($key as $k => $v) {
            $_SESSION[$k] = $v;
        }

        return $this;
    }

    /**
     * Determine if a key exists in the session.
     *
     * @param string $key The key to check for existence in the session.
     * @return bool True if the key exists in the session, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove a key from the session.
     *
     * @param string $key The key to remove from the session.
     * @return Session The current Session instance.
     */
    public function forget(string $key): Session
    {
        unset($_SESSION[$key]);

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
}