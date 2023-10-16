<?php

// Import the required classes and namespaces.
use Learn\App;
use Learn\Http\Middleware;
use Learn\Http\Request;
use Learn\Http\Response;
use Learn\Routing\Route;
use Learn\Validation\Rule;
use Learn\Validation\Rules\Required;

// Require the Composer autoload file.
require_once '../vendor/autoload.php';

// Bootstrap the application, initializing it.
$app = App::bootstrap();

// Define routes using the application's router.
Route::get('/test/{test}', function (Request $request) {
    // Define a route that responds to GET requests with JSON data containing route parameters.
    return json($request->routeParameters());
});

Route::get('/redirect', function (Request $request) {
    // Define a route that responds to GET requests by redirecting to another route.
    return redirect('/test/4');
});

Route::post('/test', function (Request $request) {
    // Define a route that responds to POST requests with JSON data containing query parameters.
    return json($request->query());
});

class AuthMiddleware implements Middleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->headers('Authorization') != 'test') {
            return json(['msg' => 'not autorizated'])->setStatus(401);
        }
        $response = $next($request);
        $response->setHeader('X-Test-Custom-Header', 'hola');
        return $response;
    }
}


Route::get('/middlewares', fn (Request $request) => json(['msg' => 'ok middleware']))
    ->setMiddlewares([AuthMiddleware::class]);

Route::get('/html', fn (Request $request) => view('home', ['user' => 'Pedro']));

Route::post('/validate', fn (Request $request) => json($request->validate(
    [
        'test' => Rule::required(),
        'num' => Rule::number(),
        'email' => [Rule::required(), Rule::email()]
    ],
    [
        'email' => [
            Required::class => 'DAME EL CAMPO'
        ]
    ]
)));

// Run the application, which will handle incoming HTTP requests based on the defined routes.
$app->run();
