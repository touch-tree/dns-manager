<?php

namespace App\Core;

use Error;
use ReflectionException;

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
        if (isset(self::$named_routes[$name])) {
            $route = self::$named_routes[$name];
            $url = $route['url'];

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
     * @param string|null $url The URL to resolve. REQUEST_URI most of the time.
     * @return bool True if a matching route was found and resolved, false otherwise.
     * @throws Error If the controller action is not in a valid format.
     * @throws ReflectionException If the controller cannot be reflected
     */
    public static function resolve(?string $url): bool
    {
        foreach (self::$routes as $route) {
            $route_url = $route['url'];
            $action = $route['action'];

            if ($_SERVER['REQUEST_METHOD'] !== $route['method']) {
                continue;
            }

            $route_parameters = self::get_route_parameters($route_url, $url) ?? [];

            // check whether the current REQUEST_URI matches any of our routes
            if (preg_match(self::get_route_pattern($route_url), $url)) {
                if (!is_array($action) || count($action) !== 2 || !is_string($action[0]) || !is_string($action[1])) {
                    throw new Error('Invalid controller action format');
                }

                $response = call_user_func_array(
                    [
                        new $action[0](),
                        $action[1]
                    ],
                    $route_parameters
                );

                if ($response instanceof Redirect) {
                    $response->send();
                    return true;
                }

                if ($response instanceof View) {
                    echo $response->render();
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get route parameters from the URL using the corresponding route.
     *
     * @param string $route_url The URL pattern of the route.
     * @param string $url The actual URL.
     * @return array|null Associative array of route parameters, or null if no match.
     */
    private static function get_route_parameters(string $route_url, string $url): ?array
    {
        if (preg_match(self::get_route_pattern($route_url), $url, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return null;
    }

    /**
     * Get route pattern.
     *
     * @param string $route_url The URL pattern of the route.
     * @return string The regex pattern for the route.
     */
    private static function get_route_pattern(string $route_url): string
    {
        return '#^' . str_replace(['\{', '\}'], ['(?P<', '>[^/]+)'], preg_quote($route_url, '#')) . '$#';
    }
}
