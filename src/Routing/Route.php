<?php

namespace Learn\Routing;

use Learn\App;
use Learn\Container\Container;

class Route
{
    /**
     * URI defined by the user.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Action of the route, which can be a closure or an array.
     *
     * @var \Closure|array
     */
    protected \Closure|array $action;

    /**
     * Regular expression used to verify parameters passed in the route.
     * Example: /test/{param1}/help/{param2}
     *
     * @var string
     */
    protected string $regex;

    /**
     * List of parameters obtained from the URI.
     *
     * @var array
     */
    protected array $parameters;

    /**
     * HTTP middlewares
     *
     * @var \Learn\Http\Middleware[]
     */
    protected array $middlewares = [];

    /**
     * Constructor of the Route class.
     *
     * @param string $uri The URI of the route.
     * @param \Closure|array $action The action associated with the route.
     */
    public function __construct(string $uri, \Closure|array $action)
    {
        $this->uri = $uri;
        $this->action = $action;

        // Generate a regular expression from the URI that replaces segments
        // of the form {param} with ([a-zA-Z0-9]+), allowing for matching.
        $this->regex = preg_replace('/\{([a-zA-Z]+)\}/', '([a-zA-Z0-9-]+)', $uri);

        // Extract parameter names from the URI and store them in the $parameters property.
        preg_match_all('/\{([a-zA-Z]+)\}/', $uri, $parameters);
        $this->parameters = $parameters[1];
    }

    /**
     * Get the URI of the route.
     *
     * @return string The URI of the route.
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * Get the action associated with the route, which can be a closure or an array.
     *
     * @return \Closure|array The action associated with the route.
     */
    public function action(): \Closure|array
    {
        return $this->action;
    }

    /**
     * Get the array of middleware components.
     *
     * @return array An array containing middleware components.
     */
    public function middlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * Set the middleware components for this class.
     *
     * @param array $middlewares An array of middleware class names to set.
     * @return self Returns the instance of the class after setting the middleware components.
     */
    public function setMiddlewares(array $middlewares): self
    {
        // Create instances of middleware classes using the provided class names.
        $this->middlewares = array_map(fn ($middleware) => new $middleware(), $middlewares);
        return $this;
    }

    /**
     * Check if there are any middleware components assigned.
     *
     * @return bool True if there are middleware components, false otherwise.
     */
    public function hasMiddlewares(): bool
    {
        // Determine if there are any middleware components in the array.
        return count($this->middlewares) > 0;
    }


    /**
     * Check if the given URI matches the route.
     *
     * @param string $uri The URI to compare with the route.
     * @return bool True if there is a match, false otherwise.
     */
    public function matches(string $uri): bool
    {
        // Use the previously generated regular expression to check if the URI matches the route.
        return preg_match("#^$this->regex/?$#", $uri);
    }

    /**
     * Check if the route has parameters.
     *
     * @return bool True if the route has parameters, false otherwise.
     */
    public function hasParameters(): bool
    {
        return count($this->parameters) > 0;
    }

    /**
     * Extract and return the values of parameters from the URI.
     *
     * @param string $uri The URI of the route.
     * @return array An associative array where the keys are parameter names and the values are their values extracted from the URI.
     */
    public function parseParameters(string $uri): array
    {
        // Use the previously generated regular expression to extract parameter values from the URI.
        preg_match("#^$this->regex$#", $uri, $arguments);

        // Combine parameter names with the extracted values and return an associative array.
        return array_combine($this->parameters, array_slice($arguments, 1));
    }

    /**
     * Define a new GET route using the static method.
     *
     * @param string $uri     The URI pattern to match.
     * @param \Closure|array $action  The action associated with the route.
     * @return Route A Route instance representing the newly defined GET route.
     */
    public static function get(string $uri, \Closure|array $action): Route
    {
        // Resolve the current application instance from the container and access its router.
        $router = Container::resolve(App::class)->router;

        // Define a new GET route using the provided URI pattern and action.
        return $router->get($uri, $action);
    }

    /**
     * Define a new POST route using the static method.
     *
     * @param string $uri     The URI pattern to match.
     * @param \Closure|array $action  The action associated with the route.
     * @return Route A Route instance representing the newly defined POST route.
     */
    public static function post(string $uri, \Closure|array $action): Route
    {
        // Resolve the current application instance from the container and access its router.
        $router = Container::resolve(App::class)->router;

        // Define a new POST route using the provided URI pattern and action.
        return $router->post($uri, $action);
    }

    /**
     * Define a new PUT route using the static method.
     *
     * @param string $uri     The URI pattern to match.
     * @param \Closure|array $action  The action associated with the route.
     * @return Route A Route instance representing the newly defined PUT route.
     */
    public static function put(string $uri, \Closure|array $action): Route
    {
        // Resolve the current application instance from the container and access its router.
        $router = Container::resolve(App::class)->router;

        // Define a new PUT route using the provided URI pattern and action.
        return $router->put($uri, $action);
    }

    /**
     * Define a new PATCH route using the static method.
     *
     * @param string $uri     The URI pattern to match.
     * @param \Closure|array $action  The action associated with the route.
     * @return Route A Route instance representing the newly defined PATCH route.
     */
    public static function patch(string $uri, \Closure|array $action): Route
    {
        // Resolve the current application instance from the container and access its router.
        $router = Container::resolve(App::class)->router;

        // Define a new PATCH route using the provided URI pattern and action.
        return $router->patch($uri, $action);
    }

    /**
     * Define a new DELETE route using the static method.
     *
     * @param string $uri     The URI pattern to match.
     * @param \Closure|array $action  The action associated with the route.
     * @return Route A Route instance representing the newly defined DELETE route.
     */
    public static function delete(string $uri, \Closure|array $action): Route
    {
        // Resolve the current application instance from the container and access its router.
        $router = Container::resolve(App::class)->router;

        // Define a new DELETE route using the provided URI pattern and action.
        return $router->delete($uri, $action);
    }
}
