<?php

namespace Learn\Tests\Routing;

use Learn\Http\Request;
use Learn\Http\Response;
use Learn\Routing\Router;
use Learn\Server\Server;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private function createMockRequest(string $uri, string $method): Request
    {
        return (new Request())
                ->setUri($uri)
                ->setMethod($method);
    }

    /**
     * Prueba para resolver una ruta básica con una acción de devolución de llamada.
     */
    public function test_resolve_basic_route_with_callback_action()
    {
        // Define la URI de la ruta a probar.
        $uri = '/test';

        // Define la acción de devolución de llamada para la ruta.
        // En este caso, es una función anónima que simplemente devuelve "test".
        $action = fn () => "test";

        // Crea una nueva instancia del enrutador.
        $router = new Router();

        // Registra la ruta y su acción asociada en el enrutador utilizando el método "get".
        $router->get($uri, $action);
        // Resuelve la ruta utilizando un objeto de solicitud simulado (MockRequest) que contiene la URI y el método HTTP (GET).
        $route = $router->resolveRoute($this->createMockRequest($uri, 'GET'));
        // Verifica que la ruta resuelta tenga la misma URI y acción que las registradas previamente.
        $this->assertEquals($uri, $route->uri());
        $this->assertEquals($action, $route->action());
    }

    /**
     * Prueba para resolver múltiples rutas básicas con acciones de devolución de llamada.
     */
    public function test_resolve_multiple_basic_routes_with_callback_action()
    {
        // Define un arreglo de rutas y sus acciones de devolución de llamada para la prueba.
        $routes = [
            '/test' => fn () => "test",
            '/foo' => fn () => "foo",
            '/bar' => fn () => "bar",
            '/long/nested/route' => fn () => "long nested route"
        ];

        // Crea una nueva instancia del enrutador.
        $router = new Router();

        // Registra todas las rutas con sus acciones asociadas en el enrutador utilizando el método "get".
        foreach ($routes as $uri => $action) {
            $router->get($uri, $action);
        }

        // Resuelve cada ruta utilizando un objeto de solicitud simulado (MockRequest) que contiene la URI y el método HTTP (GET).
        foreach ($routes as $uri => $action) {
            // Resuelve la ruta utilizando un objeto de solicitud simulado (MockRequest) que contiene la URI y el método HTTP (GET).
            $route = $router->resolveRoute($this->createMockRequest($uri, 'GET'));
            // Verifica que la ruta resuelta tenga la misma URI y acción que las registradas previamente.
            $this->assertEquals($uri, $route->uri());
            $this->assertEquals($action, $route->action());
        }
    }

    /**
     * Prueba para resolver múltiples rutas básicas con acciones de devolución de llamada
     * para diferentes métodos HTTP.
     */
    public function test_resolve_multiple_basic_routes_with_callback_action_for_different_http_methods()
    {
        // Define un arreglo de rutas con sus acciones de devolución de llamada y métodos HTTP para la prueba.
        $routes = [
            ['GET', '/test', fn () => "get"],
            ['POST', '/test', fn () => "post"],
            ['PUT', '/test', fn () => "put"],
            ['PATCH', '/test', fn () => "patch"],
            ['DELETE', '/test', fn () => "delete"],

            ['GET', '/random/test', fn () => "get"],
            ['POST', '/random/nested/test', fn () => "post"],
            ['PUT', '/put/random/route', fn () => "put"],
            ['PATCH', '/some/pathc/route', fn () => "patch"],
            ['DELETE', '/d', fn () => "delete"]
        ];

        // Crea una nueva instancia del enrutador.
        $router = new Router();

        // Registra todas las rutas con sus acciones asociadas y métodos HTTP en el enrutador utilizando los métodos "get", "post", etc.
        foreach ($routes as [$method, $uri, $action]) {
            $router->{strtolower($method)}($uri, $action);
        }

        // Resuelve cada ruta utilizando un objeto de solicitud simulado (MockRequest) que contiene la URI y el método HTTP correspondiente.
        foreach ($routes as [$method, $uri, $action]) {
            $route = $router->resolveRoute($this->createMockRequest($uri, $method));

            // Verifica que cada ruta resuelta tenga la misma URI y acción que las registradas previamente.
            $this->assertEquals($uri, $route->uri());
            $this->assertEquals($action, $route->action());
        }
    }

    /**
 * Test the execution of a chain of middlewares.
 */
    public function test_run_middlewares()
    {
        // Define a test middleware 1 that adds a custom header 'x-test-one'.
        $middleware1 = new class () {
            public function handle(Request $request, \Closure $next): Response
            {
                $response = $next($request);
                $response->setHeader('x-test-one', 'one');
                return $response;
            }
        };

        // Define a test middleware 2 that adds a custom header 'x-test-two'.
        $middleware2 = new class () {
            public function handle(Request $request, \Closure $next): Response
            {
                $response = $next($request);
                $response->setHeader('x-test-two', 'two');
                return $response;
            }
        };

        // Create a router instance.
        $router = new Router();

        // Define a URI and an expected response.
        $uri = '/test';
        $expectedResponse = Response::text("test");

        // Register a GET route with middlewares.
        $router->get($uri, fn ($request) => $expectedResponse)
            ->setMiddlewares([$middleware1, $middleware2]);

        // Resolve the route with a mock request.
        $response = $router->resolve($this->createMockRequest($uri, 'GET'));

        // Perform assertions.
        $this->assertEquals($expectedResponse, $response);
        $this->assertEquals($response->headers('x-test-one'), 'one');
        $this->assertEquals($response->headers('x-test-two'), 'two');
    }

    /**
     * Test that a middleware can stop the execution of the middleware stack.
     */
    public function test_middleware_stack_can_be_stopped()
    {
        // Define a middleware that stops the middleware stack and returns a custom response.
        $stopMiddleware = new class () {
            public function handle(Request $request, \Closure $next): Response
            {
                return Response::text("Stopped");
            }
        };

        // Define a test middleware 2 that adds a custom header 'x-test-two'.
        $middleware2 = new class () {
            public function handle(Request $request, \Closure $next): Response
            {
                $response = $next($request);
                $response->setHeader('x-test-two', 'two');
                return $response;
            }
        };

        // Create a router instance.
        $router = new Router();

        // Define a URI and an unreachable response.
        $uri = '/test';
        $unreachableResponse = Response::text("Unreachable");

        // Register a GET route with middlewares, including a middleware that stops the stack.
        $router->get($uri, fn ($request) => $unreachableResponse)
            ->setMiddlewares([$stopMiddleware, $middleware2]);

        // Resolve the route with a mock request.
        $response = $router->resolve($this->createMockRequest($uri, 'GET'));

        // Perform assertions.
        $this->assertEquals("Stopped", $response->content());
        $this->assertNull($response->headers('x-test-two'));
    }

}
