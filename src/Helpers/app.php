<?php

use Learn\App;
use Learn\Container\Container;

/**
 * Get the application instance or a specific class instance from the container.
 *
 * @param string $class (Optional) The class name to resolve from the container. Defaults to 'App'.
 * @return mixed The resolved instance from the container.
 */
function app($class = App::class)
{
    return Container::resolve($class);
}

/**
 * Get a singleton instance of the specified class from the container.
 *
 * @param string $class The name of the class to resolve as a singleton.
 * @return object|null The resolved singleton instance or null if not found.
 */
function singleton(string $class, string|callable|null $build = null)
{
    return Container::singleton($class, $build);
}
