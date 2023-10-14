<?php

namespace Learn\Server;

use Learn\Http\Response;

/**
 * Interface for implements a Server
 */
interface Server
{
    /**
     * Set Uri
     *
     * @return string
     */
    public function requestUri(): string;

    /**
     * Set Method
     *
     * @return string
     */
    public function requestMethod(): string;

    /**
     * Set data of POST
     *
     * @return array
     */
    public function postData(): array;

    /**
     * Set data of GET
     *
     * @return array
     */
    public function queryParams(): array;

    /**
     * process the send response 
     *
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response);
}