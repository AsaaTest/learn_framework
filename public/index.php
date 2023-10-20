<?php

// Import the required classes and namespaces.
use Learn\App;
use Learn\Database\DB;
use Learn\Database\Model;
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
        'test' => 'required',
        'num' => 'number',
        'email' => 'required_with:num|email'
    ],
    [
        'email' => [
            'email' => 'DAME EL 2CAMPO'
        ]
    ]
)));

Route::get('/session', function (Request $request) {
    // session()->flash('alert3', 'test');
    // return json(['id' => session()->id(), 'test' => session()->get('test', 'por defecto')]);
    return json($_SESSION);
});

Route::get('/form', fn (Request $request) => view('form'));
Route::post('/form', function (Request $request) {
    return json($request->validate([
        'email' => 'email',
        'name' => 'required|number'
    ]));
});

Route::post('/create-user', function (Request $request) {
    DB::statement('INSERT INTO users (name,email) VALUES (?,?)', [$request->data('name'), $request->data('email')]);
    return json(['msg' => 'ok']);
});

Route::get('/users', function (Request $request) {
    return json(DB::statement("SELECT * FROM users"));
});

class User extends Model
{
    protected array $fillable = [
        "name",
        "email"
    ];
}

Route::post('/user/model', function (Request $request) {
    // $user = new User();
    // $user->name = $request->data('name');
    // $user->email = $request->data('email');
    // $user->save();
    return json(User::create($request->data())->toArray());
});

Route::get('/user/query', function (Request $request) {
    // return json(User::find(4)->toArray());
    return json(array_map(fn ($m) => $m->toArray(), User::where('name', 'cochi')));
});

// Run the application, which will handle incoming HTTP requests based on the defined routes.
$app->run();
