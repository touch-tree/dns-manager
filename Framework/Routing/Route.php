<?php

namespace Framework\Routing;

/**
 * The Route class represents a singular route in the routing system.
 *
 * This class encapsulates information about a single route, including its URI pattern,
 * associated HTTP method, and the action to be taken when the route is matched.
 *
 * @package Framework\Routing
 */
class Route
{
    /**
     * The URI pattern for the route.
     *
     * @var string
     */
    protected string $uri;

    /**
     * The HTTP method associated with the route.
     *
     * @var string
     */
    protected string $method;

    /**
     * The action to be taken when the route is matched.
     *
     * @var array
     */
    protected array $action;

    /**
     * The name of the route.
     *
     * @var string|null
     */
    protected ?string $name;

    /**
     * Route constructor.
     *
     * @param string $uri The URI pattern for the route.
     * @param string $method The HTTP method associated with the route.
     * @param array $action The action to be taken when the route is matched.
     */
    public function __construct(string $uri, string $method, array $action)
    {
        $this->uri = $uri;
        $this->method = $method;
        $this->action = $action;
        $this->name = null;
    }

    /**
     * Get the URI pattern for the route.
     *
     * @return string The URI pattern.
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Get the HTTP method associated with the route.
     *
     * @return string The HTTP method.
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Get the action associated with the route.
     *
     * @return array The action.
     */
    public function action(): array
    {
        return $this->action;
    }

    /**
     * Set the name of the route.
     *
     * @param string $name The name of the route.
     * @return $this
     */
    public function set_name(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the name of the route.
     *
     * @return string|null The name of the route, or null if not set.
     */
    public function name(): ?string
    {
        return $this->name;
    }
}