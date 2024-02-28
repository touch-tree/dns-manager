<?php

namespace App\Framework\Http;

use App\Framework\Base\ParameterBag;

/**
 * This class provides methods to retrieve, set, check, and remove HTTP headers.
 * It ensures consistency by normalizing header keys to lowercase, making it
 * suitable for applications that need a uniform approach to header manipulation.
 *
 * @package App\Framework\Http
 */
class HeaderBag extends ParameterBag
{
    /**
     * HeaderBag constructor.
     *
     * @param array $headers
     */
    public function __construct(array $headers = [])
    {
        parent::__construct($headers);
    }

    /**
     * Get all headers.
     *
     * @return array An associative array of headers.
     */
    public function all(): array
    {
        return parent::all();
    }

    /**
     * Get the value of a header.
     *
     * @param string $key The header key.
     * @param mixed $default The default value if the header is not set.
     * @return mixed The header value or the default value if the header is not set.
     */
    public function get(string $key, $default = null)
    {
        return parent::get($key, $default);
    }

    /**
     * Set a header.
     *
     * @param string $key The header key.
     * @param mixed $value The header value.
     */
    public function set(string $key, $value)
    {
        parent::set($key, $value);
    }

    /**
     * Check if a header exists.
     *
     * @param string $key The header key.
     * @return bool True if the header exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return parent::has($key);
    }

    /**
     * Remove a header.
     *
     * @param string $key The header key.
     */
    public function remove(string $key)
    {
        parent::remove($key);
    }
}