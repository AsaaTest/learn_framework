<?php
// Import the required classes and namespaces.
use Learn\App;
use Learn\Http\Request;
use Learn\Http\Response;

// Require the Composer autoload file.
require_once '../vendor/autoload.php';

// Require custom helper functions.
require_once '../src/Helpers/helpers.php';

// Bootstrap the application, initializing it.
$app = App::bootstrap();

// Define routes using the application's router.
$app->router->get('/test/{test}', function (Request $request) {
    // Define a route that responds to GET requests with JSON data containing route parameters.
    return Response::json($request->routeParameters());
});

$app->router->get('/redirect', function (Request $request) {
    // Define a route that responds to GET requests by redirecting to another route.
    return Response::redirect('/test/4');
});

$app->router->post('/test', function (Request $request) {
    // Define a route that responds to POST requests with JSON data containing query parameters.
    return Response::json($request->query());
});

// Run the application, which will handle incoming HTTP requests based on the defined routes.
$app->run();
