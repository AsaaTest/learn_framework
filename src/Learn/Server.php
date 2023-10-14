<?php

namespace Learn;

class Server
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
}
