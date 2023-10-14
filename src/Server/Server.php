<?php

namespace Learn\Server;

use Learn\Http\Request;
use Learn\Http\Response;

/**
 * Interface for implements a Server
 */
interface Server
{
    /**
     * Get request send by client
     *
     * @return Request
     */
    public function getRequest(): Request;

    /**
     * process the send response
     *
     * @param Response $response
     * @return void
     */
    public function sendResponse(Response $response);
}
