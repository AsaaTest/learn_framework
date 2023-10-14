<?php

namespace Learn\Container;

/**
 * Container Class
 *
 * This class provides a container for managing and resolving class instances as singletons.
 * It allows creating instances of classes and storing them for future references, thus avoiding
 * repeated instantiation of instances for classes that should behave as singletons.
 */
class Container
{
    /**
     * Stores instances of created singleton classes.
     *
     * @var array
     */
    private static array $instances = [];

    /**
     * Resolve an instance of a class as a singleton.
     *
     * If the instance of the class has already been created and stored in the $instances array, it returns that instance.
     * If the instance does not exist, it creates and stores it in the $instances array for future references.
     * You can provide either the class name or a custom build function.
     *
     * @param string $class The name of the class to resolve as a singleton.
     * @param string|callable|null $build A string with the name of a custom class to build
     *                                   or a custom build function that returns the instance.
     * @return object|null An instance of the class if it exists, or null if it does not.
     */
    public static function singleton(string $class, string|callable|null $build = null)
    {
        // Check if the instance of the class already exists in the $instances array.
        if (!array_key_exists($class, self::$instances)) {
            // If it does not exist, create a new instance of the class using PHP reflection.
            // The class instance is stored in the $instances array for future references.
            match (true) {
                is_null($build) => self::$instances[$class] = new $class(),
                is_string($build) => self::$instances[$class] = new $build(),
                is_callable($build) => self::$instances[$class] = $build(),
            };
        }

        // Return the instance of the class.
        return self::$instances[$class];
    }

    /**
     * Resolve an instance of a class.
     *
     * Returns the instance of the class if it has already been created and stored in the $instances array.
     * If the instance does not exist in the array, it returns null.
     *
     * @param string $class The name of the class to resolve.
     * @return object|null An instance of the class if it exists, or null if it does not.
     */
    public static function resolve(string $class)
    {
        // Return the instance of the class if it exists in the $instances array; otherwise, return null.
        return self::$instances[$class] ?? null;
    }
}
