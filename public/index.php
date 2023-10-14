<?php
// import required class

use Learn\Http\HttpNotFoundException;
use Learn\Http\Request;
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
    return "GET Ok";
});

$router->get('/test2', function () {
    return "GET2 Ok";
});
$router->post('/test', function () {
    return "POST Ok";
});
// execute routes with try-catch
try {
    // get the action of the route requested
    $route = $router->resolve(new Request(new PhpNativeServer()));
    $action = $route->action();
    // execute action
    print($action());
} catch (HttpNotFoundException $e) {
    // If not found route print message and header HTTP 404
    print('Not Found');
    http_response_code(404);
}
