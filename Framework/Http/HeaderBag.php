<?php

namespace Framework\Http;

use Framework\Foundation\ParameterBag;

/**
 * The HeaderBag class represents a collection of HTTP headers.
 *
 * This class provides methods to manipulate HTTP headers easily.
 *
 * @package Framework\Http
 */
class HeaderBag extends ParameterBag
{
    /**
     * HeaderBag constructor.
     *
     * @param array $headers [optional] An associative array of headers.
     */
    public function __construct(array $headers = [])
    {
        parent::__construct($headers);
    }

    /**
     * Retrieves all headers.
     *
     * @return array An associative array of headers.
     */
    public function all(): array
    {
        return parent::all();
    }

    /**
     * Retrieves the value of a header.
     *
     * @param string $key The header key.
     * @param mixed $default [optional] The default value if the header is not set.
     * @return mixed The header value or the default value if the header is not set.
     */
    public function get(string $key, $default = null)
    {
        return parent::get(strtolower($key), $default);
    }

    /**
     * Sets a header.
     *
     * @param string $key The header key.
     * @param mixed $value The header value.
     * @return HeaderBag
     */
    public function set(string $key, $value): HeaderBag
    {
        parent::set($key, $value);

        return $this;
    }

    /**
     * Checks if a header exists.
     *
     * @param string $key The header key.
     * @return bool true if the header exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return parent::has(strtolower($key));
    }

    /**
     * Removes a header.
     *
     * @param string $key The header key.
     */
    public function remove(string $key): void
    {
        parent::remove($key);
    }
}