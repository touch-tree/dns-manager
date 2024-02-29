<?php

namespace App\Framework\Http;

use App\Framework\Base\ParameterBag;

/**
 * This class represents the HTTP header object.
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
        return parent::get(strtolower($key), $default);
    }

    /**
     * Set a header.
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
     * Check if a header exists.
     *
     * @param string $key The header key.
     * @return bool True if the header exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return parent::has(strtolower($key));
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