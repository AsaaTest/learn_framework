<?php

namespace Learn\Server;

use Learn\Http\Request;
use Learn\Http\Response;
use Learn\Server\Server;

/**
 * Implementation of the Server interface using the PHP native server.
 */
class PhpNativeServer implements Server
{
    /**
     * Get the Request object based on the PHP native server environment.
     *
     * @return Request The Request object representing the client's request.
     */
    public function getRequest(): Request
    {
        return (new Request())
            ->setUri(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))
            ->setMethod($_SERVER["REQUEST_METHOD"])
            ->setHeaders(getallheaders())
            ->setPostData($_POST)
            ->setQueryParameters($_GET);
    }

    /**
     * Send the response generated by the application to the client.
     *
     * @param Response $response The Response object to be sent to the client.
     * @return void
     */
    public function sendResponse(Response $response)
    {
        // Delete any existing "Content-Type" header since the Response class defines its own "Content-Type".
        header("Content-Type: None");
        header_remove("Content-Type");

        // Prepare the response with additional settings.
        $response->prepare();

        // Set the HTTP response code based on the Response object.
        http_response_code($response->status());

        // Iterate through the response headers and send them to the client.
        foreach ($response->headers() as $header => $value) {
            header("$header: $value");
        }

        // Print the content of the response in the body of the HTTP response.
        print($response->content());
    }
}
