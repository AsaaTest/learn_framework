<?php

namespace Learn\Http;

use Learn\Routing\Route;

/**
 * Represents an HTTP Request.
 */
class Request
{
    /**
     * URI requested by the client.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Route matched by the URI.
     *
     * @var Route
     */
    protected Route $route;

    /**
     * HTTP method used for this request.
     *
     * @var string
     */
    protected string $method;

    /**
     * POST data.
     *
     * @var array
     */
    protected array $data;

    /**
     * Query parameters.
     *
     * @var array
     */
    protected array $query;

    /**
     * Headers for this request.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Get the requested URI.
     *
     * @return string The URI requested by the client.
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Set the requested URI.
     *
     * @param string $uri The URI to set.
     * @return self The updated Request object.
     */
    public function setUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get the matched route for the request.
     *
     * @return Route The route matched by the URI.
     */
    public function route(): Route
    {
        return $this->route;
    }

    /**
     * Set the matched route for the request.
     *
     * @param Route $route The Route object to set as the matched route.
     * @return self The updated Request object.
     */
    public function setRoute(Route $route): self
    {
        $this->route = $route;
        return $this;
    }

    /**
     * Get the HTTP method used for the request.
     *
     * @return string The HTTP method used for the request (e.g., GET, POST).
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Set the HTTP method used for the request.
     *
     * @param string $method The HTTP method to set.
     * @return self The updated Request object.
     */
    public function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get the request headers.
     *
     * @param string|null $key (Optional) The specific header key to retrieve.
     * @return array|string|null If $key is provided, the value of the header; otherwise, an array of all headers.
     */
    public function headers(?string $key = null): array|string|null
    {
        if (is_null($key)) {
            return $this->headers;
        }

        return $this->headers[strtolower($key)] ?? null;
    }

    /**
     * Set the request headers.
     *
     * @param array $headers An array of headers to set for the request.
     * @return self The updated Request object.
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            $this->headers[strtolower($header)] = $value;
        }

        return $this;
    }

    /**
     * Get the POST data.
     *
     * @param string|null $key (Optional) The specific POST data key to retrieve.
     * @return array|string|null If $key is provided, the value of the POST data; otherwise, an array of all POST data.
     */
    public function data(?string $key = null): array|string|null
    {
        if (is_null($key)) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
    }

    /**
     * Set the POST data.
     *
     * @param array $data An array of POST data to set for the request.
     * @return self The updated Request object.
     */
    public function setPostData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get the query parameters.
     *
     * @param string|null $key (Optional) The specific query parameter key to retrieve.
     * @return array|string|null If $key is provided, the value of the query parameter; otherwise, an array of all query parameters.
     */
    public function query(?string $key = null): array|string|null
    {
        if (is_null($key)) {
            return $this->query;
        }

        return $this->query[$key] ?? null;
    }

    /**
     * Set the query parameters.
     *
     * @param array $query An array of query parameters to set for the request.
     * @return self The updated Request object.
     */
    public function setQueryParameters(array $query): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Get the route parameters extracted from the URI.
     *
     * @param string|null $key (Optional) The specific route parameter key to retrieve.
     * @return array|string|null If $key is provided, the value of the route parameter; otherwise, an array of all route parameters.
     */
    public function routeParameters(?string $key = null): array|string|null
    {
        $parameters = $this->route->parseParameters($this->uri);

        if (is_null($key)) {
            return $parameters;
        }

        return $parameters[$key] ?? null;
    }
}
