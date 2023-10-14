<?php

namespace Learn;

use Learn\Http\HttpNotFoundException;
use Learn\Http\Request;
use Learn\Http\Response;
use Learn\Routing\Router;
use Learn\Server\PhpNativeServer;
use Learn\Server\Server;

/**
 * Main application class responsible for handling HTTP requests and responses.
 */
class App
{
    /**S
     * The root path of the application.
     *
     * @var string
     */
    public static string $root;

    /**
     * The router for the application.
     *
     * @var Router
     */
    public Router $router;

    /**
     * The current request of the application.
     *
     * @var Request
     */
    public Request $request;

    /**
     * The web server used by the application.
     *
     * @var Server
     */
    public Server $server;

    public function __construct()
    {
        $this->router = new Router();
        $this->server = new PhpNativeServer();
        $this->request = $this->server->getRequest();
    }

    public function run()
    {
        try {
            $route = $this->router->resolve($this->request);
            $this->request->setRoute($route);
            $action = $route->action();
            $response = $action($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $response = Response::text("Not Found")->setStatus(404);
            $this->server->sendResponse($response);
        }
    }
}
