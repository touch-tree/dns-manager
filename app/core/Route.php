<?php

namespace App\Core;

use Error;

/**
 * The Route class provides a simple and flexible way to define and handle
 * routes in your application. It supports GET and POST routes, route naming,
 * and parameter extraction from URLs.
 *
 * @package App\Core
 */
class Route
{
    /**
     * Routes.
     *
     * @var array
     */
    private static array $routes = [];

    /**
     * Named routes.
     *
     * @var array
     */
    private static array $named_routes = [];

    /**
     * Get URL for a named route.
     *
     * @param string $name The name of the route.
     * @param array $parameters Associative array of route parameters.
     * @return string|null The URL for the named route with parameters applied.
     */
    public static function route(string $name, array $parameters = []): ?string
    {
        $routes = self::$named_routes[$name] ?? null;

        if (!is_null($routes)) {
            $url = $routes['url'];

            foreach ($parameters as $key => $value) {
                $url = str_replace('{' . $key . '}', $value, $url);
            }

            return $url;
        }

        return null;
    }

    /**
     * Set a GET route.
     *
     * @param string $url The URL pattern for the route.
     * @param array $action An array representing the controller and method to be called for this route.
     * @return Route
     */
    public static function get(string $url, array $action): Route
    {
        return self::add_route('GET', $url, $action);
    }

    /**
     * Set a POST route.
     *
     * @param string $url The URL pattern for the route.
     * @param array $action An array representing the controller and method to be called for this route.
     * @return Route
     */
    public static function post(string $url, array $action): Route
    {
        return self::add_route('POST', $url, $action);
    }

    /**
     * Add a route to the internal routing.
     *
     * @param string $method The HTTP method for the route (GET or POST).
     * @param string $url The URL pattern for the route.
     * @param array $action An array representing the controller and method to be called for this route.
     * @return Route
     */
    private static function add_route(string $method, string $url, array $action): Route
    {
        self::$routes[] = [
            'url' => $url,
            'method' => $method,
            'action' => $action,
        ];

        return new self();
    }

    /**
     * Set a name for a route.
     *
     * @param string $name The name for the route.
     * @return $this
     * @throws Error If a duplicate route name is detected.
     */
    public function name(string $name): Route
    {
        if (isset(self::$named_routes[$name])) {
            throw new Error('Route name cannot have duplicates: ' . $name);
        }

        self::$named_routes[$name] = self::$routes[count(self::$routes) - 1];

        return $this;
    }

    /**
     * Resolve the matching route and run the associated controller action and parameters.
     *
     * @param string $url The URL to resolve. REQUEST_URI most of the time.
     * @return bool True if a matching route was found and resolved, false otherwise.
     * @throws Error If the controller action is not in a valid format.
     */
    public static function resolve(string $url): bool
    {
        $_status = false;

        foreach (self::$routes as $route) {
            $action = $route['action'];

            if ($_SERVER['REQUEST_METHOD'] !== $route['method']) {
                continue;
            }

            if (preg_match(self::get_pattern($route['url']), $url)) {
                if (!is_array($action) || count($action) !== 2 || !is_string($action[0]) || !is_string($action[1])) {
                    throw new Error('Invalid controller action format');
                }

                $response = call_user_func_array(
                    [
                        new $action[0](),
                        $action[1]
                    ],
                    self::get_parameters($route['url'], $url)
                );

                if ($response instanceof Redirect) {
                    $response->send();
                    $_status = true;
                }

                if ($response instanceof View) {
                    echo $response->render();
                    $_status = true;
                }
            }
        }

        if (!$_status) {
            echo view('404')->render();
        }

        return $_status;
    }

    /**
     * Get route parameters from the URL using the corresponding route.
     *
     * @param string $route_url The URL pattern of the route.
     * @param string $url The actual URL.
     * @return array Associative array of route parameters, or empty array if no match.
     */
    private static function get_parameters(string $route_url, string $url): ?array
    {
        $pattern = self::get_pattern($route_url);

        if (preg_match($pattern, $url, $matches)) {
            return array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
        }

        return [];
    }

    /**
     * Get route pattern.
     *
     * @param string $route_url The URL pattern of the route.
     * @return string The regex pattern for the route.
     */
    private static function get_pattern(string $route_url): string
    {
        return '#^' . str_replace(['\{', '\}'], ['(?P<', '>[^/]+)'], preg_quote($route_url, '#')) . '$#';
    }
}
