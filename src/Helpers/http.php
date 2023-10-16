<?php

use Learn\Http\Request;
use Learn\Http\Response;

/**
 * Create and return a JSON response with the provided data.
 *
 * @param array $data Data to be converted to JSON.
 * @return Response JSON response.
 */
function json(array $data): Response
{
    return Response::json($data);
}

/**
 * Create and return a response for redirecting to the specified URI.
 *
 * @param string $uri URI to which the request should be redirected.
 * @return Response Redirect response.
 */
function redirect(string $uri): Response
{
    return Response::redirect($uri);
}

/**
 * Create and return a response for rendering a view template.
 *
 * @param string $viewName Name of the view template to render.
 * @param array $params Parameters to be passed to the view template.
 * @param mixed $layout Layout template to use (or null for default).
 * @return Response View rendering response.
 */
function view(string $viewName, array $params = [], $layout = null): Response
{
    return Response::view($viewName, $params, $layout);
}

/**
 * Get and return the current request instance.
 *
 * @return Request Current request instance.
 */
function request(): Request
{
    return app()->request;
}
