<?php

namespace App\Core;

use Error;
use ReflectionException;
use ReflectionMethod;

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
     * Routes
     *
     * @var array
     */
    private static array $routes = [];

    /**
     * Named routes
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
     *
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
     * Resolve the matching route and dispatch the associated controller action and parameters.
     *
     * @param string $url The URL to resolve. REQUEST_URI most of the time.
     * @return bool True if a matching route was found and resolved, false otherwise.
     *
     * @throws Error If the controller action is not in a valid format.
     * @throws ReflectionException
     */
    public static function dispatch(string $url): bool
    {
        $status = false;

        foreach (self::$routes as $route) {
            $route_url = $route['url'];
            [$class, $method] = $route['action'];

            if ($_SERVER['REQUEST_METHOD'] !== $route['method']) {
                continue;
            }

            if (preg_match(self::get_pattern($route['url']), $url)) {
                if (!class_exists($class)) {
                    throw new Error('Invalid controller given');
                }

                if (!method_exists($class, $method)) {
                    throw new Error('Unable to find method for ' . $class);
                }

                $response = self::resolve_controller(
                    [
                        app(Container::class)::get($class),
                        $method
                    ],
                    self::get_parameters($route_url, $url)
                );

                if ($response instanceof Redirect) {
                    $response->send();
                    $status = true;
                }

                if ($response instanceof View) {
                    echo $response->render();
                    $status = true;
                }
            }
        }

        if (!$status) {
            echo view('404')->render();
        }

        return $status;
    }

    /**
     * Resolve the controller method and invoke it with the provided parameters.
     *
     * @param array $action An array containing the controller instance and method.
     * @param array $path_parameters Associative array of path parameters.
     * @return mixed The result of invoking the controller method.
     *
     * @throws ReflectionException If there is an issue with reflection.
     * @throws Error If a type hint must be set for a parameter without a type hint.
     */
    private static function resolve_controller(array $action, array $path_parameters)
    {
        [$class, $_method] = $action;

        $method = new ReflectionMethod($class, $_method);
        $parameters = [];

        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new Error('Type hint must be set for ' . $name . ' in ' . $param->getDeclaringClass()->name);
            }

            if ($type->getName() == Request::class) {
                $parameters[] = request();
                continue;
            }

            if (is_subclass_of($type->getName(), Request::class)) {
                $request = $type->getName();
                $parameters[] = new $request;
                continue;
            }

            $parameters[] = $path_parameters[$name] ?? null;
        }

        return $method->invokeArgs($class, $parameters);
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
