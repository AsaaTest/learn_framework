<?php

namespace Learn;

use Learn\Database\Drivers\DatabaseDriver;
use Learn\Database\Drivers\PdoDriver;
use Learn\Database\Model;
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

    public DatabaseDriver $database;

    /**
 * Bootstrap method.
 *
 * Initializes and configures the application.
 *
 * @return App The configured application instance.
 */
    public static function bootstrap(): App
    {
        // Create a new instance of the App class or return an existing one.
        $app = singleton(self::class);

        // Initialize the router for the application.
        $app->router = new Router();

        // Initialize the server for the application using the PHP native server.
        $app->server = new PhpNativeServer();

        // Get the current HTTP request using the server.
        $app->request = $app->server->getRequest();

        // Initialize the view engine for rendering views.
        $app->view = new LearnEngine(__DIR__ . "/../views");

        // Initialize the session using the PHP native session storage.
        $app->session = new Session(new PhpNativeSessionStorage());

        // Initialize the database driver as a PDO driver.
        $app->database = new PdoDriver();

        // Connect to the database with specific details (e.g., MySQL on localhost).
        $app->database->connect('mysql', 'localhost', 3306, 'learn_framework', 'root', '');

        // Set the database driver for the Model class, allowing it to interact with the database.
        Model::setDatabaseDriver($app->database);

        // Load the default validation rules for the Rule class.
        Rule::loadDefaultRules();

        // Return the configured application instance.
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
        $this->database->close();
        exit();
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
