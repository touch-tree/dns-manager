<?php

namespace App\Core;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;

/**
 * The ServiceProvider class provides a simple Dependency Injection Container for managing and resolving instances of classes.
 *
 * @package App\Core
 */
class ServiceProvider
{
    /**
     * An array to store instances of resolved classes.
     *
     * @var array
     */
    private static array $instances = [];

    /**
     * Get an instance of the specified class.
     *
     * @param string $class_name The fully qualified class name.
     * @return object The resolved instance of the specified class.
     * @throws ReflectionException If the class cannot be reflected.
     * @throws RuntimeException If the class is not instantiable.
     */
    public static function get(string $class_name): object
    {
        if (isset(self::$instances[$class_name]) === false) {
            self::$instances[$class_name] = self::resolve_instance($class_name);
        }

        return self::$instances[$class_name];
    }

    /**
     * Resolve an instance of the specified class using reflection.
     *
     * @param string $class_name The fully qualified class name.
     * @return object The resolved instance of the specified class.
     * @throws ReflectionException If the class cannot be reflected.
     * @throws RuntimeException If the class is not instantiable.
     */
    private static function resolve_instance(string $class_name): object
    {
        $reflection_class = new ReflectionClass($class_name);

        if ($reflection_class->isInstantiable() === false) {
            throw new RuntimeException('Class ' . $class_name . ' is not instantiable');
        }

        $constructor = $reflection_class->getConstructor();

        if ($constructor === null) {
            return $reflection_class->newInstance();
        }

        return $reflection_class->newInstanceArgs(self::resolve_constructor_dependencies($constructor));
    }

    /**
     * Resolve constructor dependencies.
     *
     * @param ReflectionMethod $constructor The constructor method.
     * @return array The resolved dependencies.
     * @throws ReflectionException
     */
    private static function resolve_constructor_dependencies(ReflectionMethod $constructor): array
    {
        $dependencies = [];

        foreach ($constructor->getParameters() as $param) {
            $param_class = $param->getClass();

            if ($param_class === null) {
                throw new RuntimeException('Unable to resolve dependency for parameter ' . $param->name . ' in ' . $param->getDeclaringClass()->name);
            }

            $dependencies[] = self::get($param_class->getName());
        }

        return $dependencies;
    }
}
