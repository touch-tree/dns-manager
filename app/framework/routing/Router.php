<?php

namespace App\Framework\Routing;

use App\Framework\Base\View;
use App\Framework\Http\Redirect;
use App\Framework\Http\Request;
use Exception;
use ReflectionException;
use ReflectionMethod;

/**
 * The Router class provides a simple and flexible way to define and handle
 * routes in your application. It supports GET and POST routes, route naming,
 * and parameter extraction from URLs.
 *
 * @package App\Framework\Routing
 */
class Router
{
    /**
     * Routes
     *
     * @var array<Route>
     */
    private static array $routes = [];

    /**
     * Named routes
     *
     * @var array<Route>
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
        $route = self::$named_routes[$name] ?? null;

        if (!is_null($route)) {
            $uri = $route->uri();

            foreach ($parameters as $key => $value) {
                $uri = str_replace('{' . $key . '}', $value, $uri);
            }

            return $uri;
        }

        return null;
    }

    /**
     * Set a GET route.
     *
     * @param string $uri The URI pattern for the route.
     * @param array $action An array representing the controller and method to be called for this route.
     * @return Router
     */
    public static function get(string $uri, array $action): Router
    {
        return self::add_route('GET', $uri, $action);
    }

    /**
     * Set a POST route.
     *
     * @param string $uri The URI pattern for the route.
     * @param array $action An array representing the controller and method to be called for this route.
     * @return Router
     */
    public static function post(string $uri, array $action): Router
    {
        return self::add_route('POST', $uri, $action);
    }

    /**
     * Add a route to the internal routing.
     *
     * @param string $method The HTTP method for the route (GET or POST).
     * @param string $uri The URI pattern for the route.
     * @param array $action An array representing the controller and method to be called for this route.
     * @return Router
     */
    private static function add_route(string $method, string $uri, array $action): Router
    {
        self::$routes[] = new Route($uri, $method, $action);
        return new self();
    }

    /**
     * Set a name for a route.
     *
     * @param string $name The name for the route.
     * @return $this
     *
     * @throws Exception If a duplicate route name is detected.
     */
    public function name(string $name): Router
    {
        if (isset(self::$named_routes[$name])) {
            throw new Exception($name);
        }

        self::$named_routes[$name] = self::$routes[count(self::$routes) - 1];

        return $this;
    }

    /**
     * Resolve the matching route and dispatch the associated controller action and parameters.
     *
     * @param string $uri The URI to resolve. REQUEST_URI most of the time.
     * @return bool True if a matching route was found and resolved, false otherwise.
     *
     * @throws Exception If the controller action is not in a valid format.
     * @throws ReflectionException
     */
    public static function dispatch(string $uri): bool
    {
        foreach (self::$routes as $route) {
            [$class, $method] = $route->get_action();

            if ($_SERVER['REQUEST_METHOD'] !== $route->method()) {
                continue;
            }

            if (preg_match(self::get_pattern($route->uri()), $uri)) {
                if (!class_exists($class)) {
                    throw new Exception('Invalid controller given');
                }

                if (!method_exists($class, $method)) {
                    throw new Exception('Unable to find method for ' . $class);
                }

                $response = self::resolve_controller(
                    [
                        app($class),
                        $method
                    ],
                    self::get_parameters($route->uri(), $uri)
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

        echo view('404')->render();

        return false;
    }

    /**
     * Resolve the controller method and invoke it with the provided parameters.
     *
     * @param array $action An array containing the controller instance and method.
     * @param array $path_parameters Associative array of path parameters.
     * @return mixed The result of invoking the controller method.
     *
     * @throws ReflectionException If there is an issue with reflection.
     * @throws Exception If a type hint must be set for a parameter without a type hint.
     */
    private static function resolve_controller(array $action, array $path_parameters)
    {
        [$class, $_method] = $action;

        $method = new ReflectionMethod($class, $_method);
        $parameters = [];

        foreach ($method->getParameters() as $param) {
            $class_name = $param->getDeclaringClass()->name;
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new Exception('Type hint must be set for ' . $name . ' in ' . $class_name);
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
