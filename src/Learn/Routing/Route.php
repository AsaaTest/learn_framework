<?php

namespace Learn\Routing;

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
}
