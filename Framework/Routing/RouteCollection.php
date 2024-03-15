<?php

namespace Framework\Routing;

use Framework\Http\Request;
use Framework\Support\Url;

/**
 * The RouteCollection class represents a collection of routes in the routing system.
 *
 * This class manages a collection of routes, allowing for the addition,
 * retrieval, and matching of routes based on incoming requests.
 *
 * @package Framework\Routing
 */
class RouteCollection
{
    /**
     * The routes registered in this collection.
     *
     * @var array<Route>
     */
    private array $routes = [];

    /**
     * Get all routes registered in the collection.
     *
     * @return array<Route> An array of Route objects.
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * Add a route to the collection.
     *
     * @param Route $route The route to be added.
     * @return $this
     */
    public function add(Route $route): RouteCollection
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Get a route from the collection by its name.
     *
     * @param string $key The name of the route to retrieve.
     * @return Route|null The Route object if found, otherwise null.
     */
    public function get(string $key): ?Route
    {
        foreach ($this->routes as $route) {
            if ($route->name() === $key) {
                return $route;
            }
        }

        return null;
    }

    /**
     * Match an incoming request to a route in the collection.
     *
     * @param Request $request The incoming HTTP request.
     * @return Route|null The matched Route object if found, otherwise null.
     */
    public function match(Request $request): ?Route
    {
        foreach ($this->routes as $route) {
            $route_uri = Url::to($route->uri(), [], true);

            if ($request->method() === $route->method() && preg_match(app(Router::class)::get_pattern($route_uri), $request->path())) {
                return $route;
            }
        }

        return null;
    }
}