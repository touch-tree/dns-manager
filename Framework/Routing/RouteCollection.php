<?php

namespace Framework\Routing;

use Framework\Http\Request;

/**
 * This class represents a collection of routes.
 *
 * @package Framework\Routing
 */
class RouteCollection
{
    /**
     * Routes of this application.
     *
     * @var Route[]
     */
    private array $routes = [];

    /**
     * Get all routes.
     *
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * Add route.
     *
     * @param Route $route
     * @return $this
     */
    public function add(Route $route): RouteCollection
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * Get route.
     *
     * @param string $key
     * @return Route|null
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
     * Match a route with request.
     *
     * @param Request $request
     * @return Route|null
     */
    public function match(Request $request): ?Route
    {
        foreach ($this->routes as $route) {
            if ($request->method() === $route->method() && preg_match(app(Router::class)::get_pattern(config('url') . ltrim($route->uri(), '/')), $request->path())) {
                return $route;
            }
        }

        return null;
    }
}