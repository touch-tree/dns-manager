<?php

namespace App\Framework\Http;

/**
 * This class provides methods to retrieve, set, check, and remove HTTP headers.
 * It ensures consistency by normalizing header keys to lowercase, making it
 * suitable for applications that need a uniform approach to header manipulation.
 *
 * @package App\Framework\Http
 */
class HeaderBag
{
    /**
     * @var array The array containing the headers.
     */
    protected array $headers = [];

    /**
     * Get all headers.
     *
     * @return array An associative array of headers.
     */
    public function all(): array
    {
        if (empty($this->headers)) {
            $this->headers = array_change_key_case(getallheaders());
        }

        return $this->headers;
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
        return $this->all()[strtolower($key)] ?? $default;
    }

    /**
     * Set a header.
     *
     * @param string $key The header key.
     * @param mixed $value The header value.
     */
    public function set(string $key, $value)
    {
        $this->headers[strtolower($key)] = $value;
    }

    /**
     * Check if a header exists.
     *
     * @param string $key The header key.
     * @return bool True if the header exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->all()[strtolower($key)]);
    }

    /**
     * Remove a header.
     *
     * @param string $key The header key.
     */
    public function remove(string $key)
    {
        unset($this->headers[strtolower($key)]);
    }
}