<?php

namespace Learn\Tests\Http;

use Learn\Http\Request;
use Learn\Routing\Route;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function test_request_returns_data_obtained_from_server_correctly()
    {
        $uri = '/test/route';
        $queryParams = ['a' => 1, 'b' => 2, 'test' => 'foo'];
        $postData = ['post' => 'test', 'foo' => 'bar'];

        $request = (new Request())
            ->setUri($uri)
            ->setMethod('POST')
            ->setQueryParameters($queryParams)
            ->setPostData($postData);

        $this->assertEquals($uri, $request->uri());
        $this->assertEquals($queryParams, $request->query());
        $this->assertEquals($postData, $request->data());
        $this->assertEquals('POST', $request->method());
    }

    /**
     * Test that the `data` method returns a value if a key is given.
     */
    public function test_data_returns_value_if_key_is_given()
    {
        // Define test data as an associative array.
        $data = ['test' => 5, 'foo' => 1, 'bar' => 2];

        // Create a request with the test data.
        $request = (new Request())->setPostData($data);

        // Perform assertions.
        $this->assertEquals($request->data('test'), 5);   // Check if the 'test' key returns the expected value.
        $this->assertEquals($request->data('foo'), 1);    // Check if the 'foo' key returns the expected value.
        $this->assertNull($request->data("doesn't exist")); // Check if a non-existing key returns null.
    }

    /**
     * Test that the `query` method returns a value if a key is given.
     */
    public function test_query_returns_value_if_key_is_given()
    {
        // Define test data as an associative array.
        $data = ['test' => 5, 'foo' => 1, 'bar' => 2];

        // Create a request with the test data as query parameters.
        $request = (new Request())->setQueryParameters($data);

        // Perform assertions.
        $this->assertEquals($request->query('test'), 5);   // Check if the 'test' key returns the expected value.
        $this->assertEquals($request->query('foo'), 1);    // Check if the 'foo' key returns the expected value.
        $this->assertNull($request->query("doesn't exist")); // Check if a non-existing key returns null.
    }

    /**
     * Test that the `routeParameters` method returns a value if a key is given.
     */
    public function test_route_parameters_returns_value_if_key_is_given()
    {
        // Define a route with parameters and a matching request.
        $route = new Route('/test/{param}/foo/{bar}', fn () => "test");
        $request = (new Request())
            ->setRoute($route)
            ->setUri('/test/1/foo/2');

        // Perform assertions.
        $this->assertEquals($request->routeParameters('param'), 1);   // Check if the 'param' key returns the expected value.
        $this->assertEquals($request->routeParameters('bar'), 2);      // Check if the 'bar' key returns the expected value.
        $this->assertNull($request->routeParameters("doesn't exist")); // Check if a non-existing key returns null.
    }

}
