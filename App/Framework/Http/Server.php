<?php

namespace App\Framework\Http;

/**
 * This class represents a wrapper for the server variables.
 *
 * @package App\Framework\Http
 */
class Server
{
    /**
     * Get server variable.
     *
     * @param $key
     * @param string|null $default
     * @return mixed|string|null
     */
    public function get($key, ?string $default = null)
    {
        return $_SERVER[$key] ?? $default;
    }
}