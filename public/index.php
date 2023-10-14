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
$router->get('/test/{test}', function (Request $request) {
    return Response::json($request->routeParameters());
});

$router->get('/redirect', function (Request $request) {
    return Response::redirect('/test/4');
});
$router->post('/test', function (Request $request) {
    return Response::json($request->query());
});
// define server
$server = new PhpNativeServer();
// execute routes with try-catch
try {
    // get the action of the route requested
    $request = $server->getRequest();
    $route = $router->resolve($request);
    $request->setRoute($route);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    // If not found route print message and header HTTP 404
    $server->sendResponse(Response::text("Not Found")->setStatus(404));
}
