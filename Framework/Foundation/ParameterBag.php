<?php

namespace Framework\Foundation;

/**
 * The ParameterBag class represents a simple class for managing a collection of parameters.
 *
 * This class provides methods to interact with parameters stored as an associative array.
 *
 * @package Framework\Foundation
 */
class ParameterBag
{
    /**
     * @var array The parameters stored in the bag.
     */
    protected array $parameters;

    /**
     * ParameterBag constructor.
     *
     * @param array $parameters [optional] An associative array of parameters.
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Check if there are any parameters set.
     *
     * @return bool true if parameters exist, false otherwise.
     */
    public function any(): bool
    {
        return !empty($this->parameters);
    }

    /**
     * Get all parameters.
     *
     * @return array An associative array of parameters.
     */
    public function all(): array
    {
        return $this->parameters;
    }

    /**
     * Get the value of a parameter.
     *
     * @param string $key The key of the parameter.
     * @param mixed $default [optional] The default value if the parameter is not found.
     * @return mixed The value of the parameter, or the default value if not found.
     */
    public function get(string $key, $default = null)
    {
        return $this->parameters[$key] ?? $default;
    }

    /**
     * Set a parameter.
     *
     * @param string $key The key of the parameter.
     * @param mixed $value The value of the parameter.
     * @return ParameterBag The ParameterBag instance for method chaining.
     */
    public function set(string $key, $value): ParameterBag
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    /**
     * Check if a parameter exists.
     *
     * @param string $key The key of the parameter.
     * @return bool true if the parameter exists, false otherwise.
     */
    public function has(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    /**
     * Remove a parameter.
     *
     * @param string $key The key of the parameter to be removed.
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->parameters[$key]);
    }
}
