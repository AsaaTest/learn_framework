<?php

namespace Learn;

use Learn\Container\Container;
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
    /**
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

    /**
     * Bootstrap method.
     *
     * Initializes and configures the application.
     *
     * @return App The configured application instance.
     */
    public static function bootstrap(): App
    {
        $app = Container::singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        return $app;
    }

    /**
     * Run the application.
     *
     * This method is responsible for handling HTTP requests and responses.
     */
    public function run()
    {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $response = Response::text("Not Found")->setStatus(404);
            $this->server->sendResponse($response);
        }
    }
}
