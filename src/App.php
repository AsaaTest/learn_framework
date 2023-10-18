<?php

namespace Learn;

use Learn\Http\HttpNotFoundException;
use Learn\Http\Request;
use Learn\Http\Response;
use Learn\Routing\Router;
use Learn\Server\PhpNativeServer;
use Learn\Server\Server;
use Learn\Session\PhpNativeSessionStorage;
use Learn\Session\Session;
use Learn\Validation\Exceptions\ValidationException;
use Learn\Validation\Rule;
use Learn\View\LearnEngine;
use Learn\View\View;
use Throwable;

/**
 * The main application class responsible for handling HTTP requests and responses.
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
     * View engine
     *
     * @var View
     */
    public View $view;

    /**
     * Session
     *
     * @var Session
     */
    public Session $session;

    /**
     * Bootstrap method.
     *
     * Initializes and configures the application.
     *
     * @return App The configured application instance.
     */
    public static function bootstrap(): App
    {
        $app = singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        $app->view = new LearnEngine(__DIR__ . "/../views");
        $app->session = new Session(new PhpNativeSessionStorage());
        Rule::loadDefaultRules();
        return $app;
    }

    /**
     * Prepare the next request, storing the previous URI in the session for GET requests.
     */
    public function prepareNextRequest()
    {
        if ($this->request->method() == 'GET') {
            $this->session->set('_previous', $this->request->uri());
        }
    }

    /**
     * Terminate the current request by sending the specified response.
     *
     * @param Response $response The response to be sent to the client to terminate the request.
     */
    public function terminate(Response $response)
    {
        $this->prepareNextRequest();
        $this->server->sendResponse($response);
    }

    /**
     * Run the application.
     *
     * This method is responsible for handling HTTP requests and responses. It resolves routes, processes exceptions,
     * and sends appropriate responses based on the outcome of the request handling.
     *
     * @throws HttpNotFoundException When the requested route is not found (HTTP 404).
     * @throws ValidationException When validation of input data fails (HTTP 422).
     * @throws Throwable When an unhandled exception occurs.
     */
    public function run()
    {
        try {
            $this->terminate($this->router->resolve($this->request));
        } catch (HttpNotFoundException $e) {
            // Handle HTTP 404 (Not Found) error by sending an appropriate response.
            $this->abort(Response::text("Not Found")->setStatus(404));
        } catch (ValidationException $e) {
            // Handle validation errors by sending a JSON response with validation error details (HTTP 422).
            $this->abort(back()->withErrors($e->errors(), 422));
        } catch (Throwable $e) {
            // Handle unhandled exceptions by sending a JSON response with error message and trace.
            $response = json([
                "error" => $e::class,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ]);
            $this->abort($response->setStatus(500));
        }
    }

    /**
     * Abort the current request with a specified response.
     *
     * This method allows you to forcefully terminate the current request by sending a specific response.
     *
     * @param Response $response The response to be sent to the client to terminate the request.
     */
    public function abort(Response $response)
    {
        $this->terminate($response);
    }
}
