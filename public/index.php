<?php
// import required class

use Learn\Http\HttpNotFoundException;
use Learn\Http\Request;
use Learn\Http\Response;
use Learn\Routing\Router;
use Learn\Server\PhpNativeServer;

// require autoload of composer
require_once '../vendor/autoload.php';
// require helpers
require_once '../src/Helpers/helpers.php';

//define class Router
$router = new Router();
// define routes
$router->get('/test/{test}', function () {
    $response = new Response();
    $response->setHeader("Content-Type", "application/json");
    $response->setContent(json_encode(["msg" => "get ok"]));

    return $response;
});

$router->get('/test2', function () {
    return "GET2 Ok";
});
$router->post('/test', function () {
    return "POST Ok";
});
// define server
$server = new PhpNativeServer();
// execute routes with try-catch
try {
    // get the action of the route requested
    $request = new Request($server);
    $route = $router->resolve($request);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    // If not found route print message and header HTTP 404
    $response = new Response();
    $response->setStatus(404);
    $response->setContent("Not Found");
    $server->sendResponse($response);
}
