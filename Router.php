<?php
require_once './HttpNotFoundException.php';
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
     * Resolve route, get the action of route requested
     *
     * @return void
     */
    public function resolve(){
        // get method 
        $method = $_SERVER["REQUEST_METHOD"];
        //get uri
        $uri = $_SERVER["REQUEST_URI"];
        // get action of array routes
        $action = $this->routes[$method][$uri] ?? null;
        // is null set an exception
        if(is_null($action)){
            throw new HttpNotFoundException();
        }
        // return action
        return $action;
    }

    /**
     * Method get storage route for method GET
     *
     * @param string $uri
     * @param callable $action
     * @return void
     */
    public function get(string $uri, callable $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    /**
     * Method get storage route for method POST
     *
     * @param string $uri
     * @param callable $action
     * @return void
     */
    public function post(string $uri, callable $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    /**
     * Method get storage route for method PUT
     *
     * @param string $uri
     * @param callable $action
     * @return void
     */
    public function put(string $uri, callable $action)
    {
        $this->routes['PUT'][$uri] = $action;
    }

    /**
     * Method get storage route for method PATCH
     *
     * @param string $uri
     * @param callable $action
     * @return void
     */
    public function patch(string $uri, callable $action)
    {
        $this->routes['PATCH'][$uri] = $action;
    }

    /**
     * Method get storage route for method DELETE
     *
     * @param string $uri
     * @param callable $action
     * @return void
     */
    public function delete(string $uri, callable $action)
    {
        $this->routes['DELETE'][$uri] = $action;
    }

}
