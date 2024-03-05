<?php

namespace App\Framework\Foundation;

use Closure;
use Error;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

/**
 * The Container class provides a simple Dependency Injection Container for managing and resolving instances of classes.
 * Representing a service container.
 *
 * @package App\Framework\Foundation
 */
class Container
{
    /**
     * An array to store instances of resolved classes.
     *
     * @var array
     */
    private static array $instances = [];

    /**
     * An array to store bindings of abstract classes or interfaces to concrete implementations.
     *
     * @var array
     */
    private static array $bindings = [];

    /**
     * Get an instance of the specified class.
     *
     * @param string $class_name The fully qualified class name.
     * @return object The resolved instance of the specified class.
     *
     * @throws Exception
     */
    public static function get(string $class_name): object
    {
        $concrete = self::$bindings[$class_name] ?? null;

        if (is_null($concrete)) {
            return self::$instances[$class_name] = self::resolve_instance($class_name);
        }

        if ($concrete instanceof Closure) {
            return $concrete();
        }

        if (is_object($concrete)) {
            return $concrete;
        }

        return self::resolve_instance($concrete);
    }

    /**
     * Resolve an instance of the specified class using reflection.
     *
     * @param string $class_name The fully qualified class name.
     * @return object The resolved instance of the specified class.
     *
     * @throws Exception If the class is not instantiable.
     */
    private static function resolve_instance(string $class_name): object
    {
        $reflection_class = new ReflectionClass($class_name);

        if (!$reflection_class->isInstantiable()) {
            throw new Error('Class is not instantiable: ' . $class_name);
        }

        if ($constructor = $reflection_class->getConstructor()) {
            return $reflection_class->newInstanceArgs(self::resolve_dependencies($constructor));
        }

        return $reflection_class->newInstance();
    }

    /**
     * Resolve constructor dependencies.
     *
     * @param ReflectionMethod $constructor The constructor method.
     * @return array The resolved dependencies.
     *
     * @throws Exception
     */
    public static function resolve_dependencies(ReflectionMethod $constructor): array
    {
        $dependencies = [];

        foreach ($constructor->getParameters() as $param) {
            $class_name = $param->getDeclaringClass()->name;
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new Error('Type hint must be set for ' . $name . ' in ' . $class_name);
            }

            if (!($type instanceof ReflectionNamedType) || $type->isBuiltin()) {
                throw new Error('Unable to resolve dependency ' . $param->name . ' in ' . $class_name);
            }

            $dependencies[] = self::get($type->getName());
        }

        return $dependencies;
    }

    /**
     * Bind an abstract class or interface to a concrete implementation.
     *
     * @param string $abstract The abstract class or interface.
     * @param Closure|string|object $concrete The closure, class name, or instance.
     * @return void
     */
    public static function bind(string $abstract, $concrete): void
    {
        self::$bindings[$abstract] = $concrete;
    }

    /**
     * Bind an abstract class or interface to a singleton concrete implementation.
     *
     * @param string $abstract The abstract class or interface.
     * @param Closure|string|object $concrete The closure, class name, or instance.
     * @return void
     *
     * @throws ReflectionException|Exception
     */
    public static function singleton(string $abstract, $concrete): void
    {
        self::bind($abstract, $concrete);

        self::$instances[$abstract] = self::resolve_instance($abstract);
    }

    /**
     * Determine if the given abstract type has been bound.
     *
     * @param string $abstract The abstract class or interface.
     * @return bool True if the abstract type is bound, false otherwise.
     */
    public static function bound(string $abstract): bool
    {
        return isset(self::$bindings[$abstract]);
    }

    /**
     * Get the concrete implementation for a given abstract type.
     *
     * @param string $abstract The abstract class or interface.
     * @return Closure|string|object|null The closure, class name, or instance, or null if not bound.
     */
    public static function get_binding(string $abstract)
    {
        return self::$bindings[$abstract] ?? null;
    }

    /**
     * Forget the concrete implementation for a given abstract type.
     *
     * @param string $abstract The abstract class or interface.
     * @return void
     */
    public static function forget_binding(string $abstract): void
    {
        unset(self::$bindings[$abstract]);
    }
}
