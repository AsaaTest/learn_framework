<?php

namespace Learn\Http;

// Define the Middleware interface, which specifies a method for handling requests and passing control to the next middleware.
interface Middleware
{
    public function handle(Request $request, \Closure $next): Response;
}
