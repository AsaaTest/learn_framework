<?php
// import required class

use Learn\App;
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
$app = new App();
// define routes
$app->router->get('/test/{test}', function (Request $request) {
    return Response::json($request->routeParameters());
});

$app->router->get('/redirect', function (Request $request) {
    return Response::redirect('/test/4');
});
$app->router->post('/test', function (Request $request) {
    return Response::json($request->query());
});

$app->run();
