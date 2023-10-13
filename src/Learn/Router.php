<?php

namespace Learn;

use Learn\Route;

/**
 * Class Router
 * 
 * handle routes
 */
class Router
{
    /**
     * variable storage defined routes
     *
     * @var array
     */
    protected array $routes = [];

    /**
     * construct
     */
    public function __construct()
    {
        // define array with main methods http 
        $this->routes = [
            "GET" => [],
            "POST" => [],
            "PUT" => [],
            "PATCH" => [],
            "DELETE" => []
        ];
    }

    /**
     * Resolve a route and get the action for the requested route.
     *
     * @param string $uri   The URI of the requested route.
     * @param string $method The HTTP method (e.g., GET, POST) used for the request.
     *
     * @return mixed
     */
    public function resolve(string $uri, string $method)
    {
        // Iterate over the registered routes for the specific HTTP method.
        foreach ($this->routes[$method] as $route) {
            // Check if the route matches the requested URI using the "matches" method of the "Route" class.
            if ($route->matches($uri)) {
                return $route; // Return the matching route.
            }
        }
        throw new HttpNotFoundException(); // Throw an exception if no matching route is found.
    }

    /**
     * Method for registering a route for the GET HTTP method.
     *
     * @param string $uri    The URI of the route.
     * @param callable $action A callback function or closure associated with the route.
     *
     * @return void
     */
    public function get(string $uri, callable $action)
    {
        $this->registerRoute("GET", $uri, $action);
    }

    /**
     * Method for registering a route for the POST HTTP method.
     *
     * @param string $uri    The URI of the route.
     * @param callable $action A callback function or closure associated with the route.
     *
     * @return void
     */
    public function post(string $uri, callable $action)
    {
        $this->registerRoute("POST", $uri, $action);
    }

    /**
     * Method for registering a route for the PUT HTTP method.
     *
     * @param string $uri    The URI of the route.
     * @param callable $action A callback function or closure associated with the route.
     *
     * @return void
     */
    public function put(string $uri, callable $action)
    {
        $this->registerRoute("PUT", $uri, $action);
    }

    /**
     * Method for registering a route for the PATCH HTTP method.
     *
     * @param string $uri    The URI of the route.
     * @param callable $action A callback function or closure associated with the route.
     *
     * @return void
     */
    public function patch(string $uri, callable $action)
    {
        $this->registerRoute("PATCH", $uri, $action);
    }

    /**
     * Method for registering a route for the DELETE HTTP method.
     *
     * @param string $uri    The URI of the route.
     * @param callable $action A callback function or closure associated with the route.
     *
     * @return void
     */
    public function delete(string $uri, callable $action)
    {
        $this->registerRoute("DELETE", $uri, $action);
    }

    /**
     * Register new route in router
     *
     * @param string $method HTTP method
     * @param string $uri URI of the route
     * @param \Closure|array $action action associed to route
     */
    protected function registerRoute(string $method, string $uri, \Closure|array $action)
    {
        $route = new Route($uri, $action);
        // Crea un nuevo objeto "Route" con la URI y la acción proporcionadas y lo agrega al array de rutas.
        $this->routes[$method][] = $route;
    }
}
