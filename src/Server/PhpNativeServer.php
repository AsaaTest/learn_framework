<?php

namespace Learn\Server;

use Learn\Http\Response;
use Learn\Server\Server;

class PhpNativeServer implements Server
{
    /**
     * Get Uri of Server
     *
     * @return string
     */
    public function requestUri(): string
    {
        return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
    }

    /**
     * Get method of Server
     *
     * @return string
     */
    public function requestMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Ger Post data send by route
     *
     * @return array
     */
    public function postData(): array
    {
        return $_POST;
    }

    /**
     * Get data of _GET send in route
     *
     * @return array
     */
    public function queryParams(): array
    {
        return $_GET;
    }

    /**
     * Send response send by user
     *
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response)
    {
        // delete any header "Content-Type" exists for class `Response` define own "Content-Type"
        header("Content-Type: None");
        header_remove("Content-Type");

        // prepare the response with aditional settings
        $response->prepare();

        // establish the response Http code
        http_response_code($response->status());

        // Iterate the headers of response and send to client
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }

        // Print the content of response in body of response HTTP
        print($response->content());
    }
}
