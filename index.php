<?php
// import required class
require_once './helpers.php';
require_once './Router.php';
//define class Router
$router = new Router();
// define routes
$router->get('/test', function () {
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
    $action = $router->resolve();
    // execute action
    print($action());
} catch (HttpNotFoundException $e) {
    // If not found route print message and header HTTP 404
    print('Not Found');
    http_response_code(404);
}
