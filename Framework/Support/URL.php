<?php

namespace Framework\Support;

use Framework\Foundation\Config;
use Framework\Http\Request;

/**
 * The URL class represents a utility for generating URLs.
 *
 * @package Framework\Support
 */
class URL
{
    /**
     * Get the base URL for the application.
     *
     * @return string|null The base URL for the application. Returns Null if URL is not set.
     */
    public static function app_url(): ?string
    {
        $url = config('app_url');

        $document_path = realpath(server()->get('DOCUMENT_ROOT'));
        $path = str_replace('\\', '/', str_replace($document_path, '', base_path()));

        return !empty($url) ? $url : request()->base_url() . ltrim($path, '/') . '/';
    }

    /**s
     * Generate an absolute URL for the given path and parameters, optionally excluding the host.
     *
     * @param string $path The path to the resource.
     * @param array $parameters Parameters to append to the URL as query parameters (Optional).
     * @param bool $exclude_host Whether to exclude the host from the generated URL (Optional).
     * @return string The generated absolute URL.
     */
    public static function to(string $path, array $parameters = [], bool $exclude_host = false): string
    {
        $url = self::app_url() . ltrim($path, '/');

        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        if ($exclude_host) {
            $parts = parse_url($url);
            $url = $parts['path'];

            if (isset($parts['query'])) {
                $url .= '?' . $parts['query'];
            }
        }

        return $url;
    }

    /**
     * Get the current URL.
     *
     * @return string The current URL.
     */
    public static function current(): string
    {
        return request()->base_url() . ltrim(request()->path(), '/');
    }
}

