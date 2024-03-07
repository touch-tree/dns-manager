<?php

namespace Framework\Http;

/**
 * The Server class provides easy access for server variables.
 *
 * @package Framework\Http
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