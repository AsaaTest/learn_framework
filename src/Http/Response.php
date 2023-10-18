<?php

namespace Learn\Http;

class Response
{
    /**
     * HTTP status code of the response.
     *
     * @var int
     */
    protected int $status = 200;

    /**
     * HTTP headers of the response.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Content of the response.
     *
     * @var string|null
     */
    protected ?string $content = null;

    /**
     * Get the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * Set the HTTP status code of the response.
     *
     * @param int $status The HTTP status code to set.
     * @return self The Response instance.
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the HTTP headers of the response, or a specific header by key.
     *
     * @param string|null $key The header key (optional).
     * @return array|string|null The headers or a specific header value.
     */
    public function headers(?string $key = null): array|string|null
    {
        if (is_null($key)) {
            return $this->headers;
        }
        return $this->headers[strtolower($key)] ?? null;
    }

    /**
     * Set an HTTP header for the response.
     *
     * @param string $header The header name.
     * @param string $value The header value.
     * @return self The Response instance.
     */
    public function setHeader(string $header, string $value): self
    {
        $this->headers[strtolower($header)] = $value;
        return $this;
    }

    /**
     * Remove an HTTP header from the response.
     *
     * @param string $header The header to remove.
     */
    public function removeHeader(string $header)
    {
        unset($this->headers[strtolower($header)]);
    }

    /**
     * Set the content type for the response.
     *
     * @param string $value The content type.
     * @return self The Response instance.
     */
    public function setContentType(string $value): self
    {
        $this->setHeader("Content-Type", $value);
        return $this;
    }

    /**
     * Get the content of the response.
     *
     * @return string|null The response content.
     */
    public function content(): ?string
    {
        return $this->content;
    }

    /**
     * Set the content of the response.
     *
     * @param string $content The response content.
     * @return self The Response instance.
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Prepare the response, remove headers if content is null, and set the "Content-Length" header.
     */
    public function prepare()
    {
        if (is_null($this->content)) {
            $this->removeHeader("Content-Type");
            $this->removeHeader("Content-Length");
        } else {
            $this->setHeader("Content-Length", strlen($this->content));
        }
    }

    /**
     * Create a new instance of the Response class with content in JSON format.
     *
     * @param array $data An array of data to be converted to JSON format.
     * @return self An instance of the Response class with JSON content.
     */
    public static function json(array $data): self
    {
        return (new self())
            ->setContentType("application/json")
            ->setContent(json_encode($data));
    }

    /**
     * Create a new instance of the Response class with plain text content.
     *
     * @param string $text The text to set as content.
     * @return self An instance of the Response class with plain text content.
     */
    public static function text(string $text): self
    {
        return (new self())
            ->setContentType("text/plain")
            ->setContent($text);
    }

    /**
     * Create a new instance of the Response class for redirection to another URI.
     *
     * @param string $uri The URI to which the response should be redirected.
     * @return self An instance of the Response class for redirection.
     */
    public static function redirect(string $uri): self
    {
        return (new self())
            ->setStatus(302) // Set the HTTP status code for redirection (302 Found).
            ->setHeader("Location", $uri); // Set the "Location" header to specify the redirection target.
    }

    /**
     * Create a new instance of the Response class with a view's rendered content.
     *
     * @param string $viewName The name of the view.
     * @param array $params An array of parameters to pass to the view.
     * @param mixed $layout The layout to use (optional).
     * @return self An instance of the Response class with HTML content.
     */
    public static function view(string $viewName, array $params = [], $layout = null): self
    {
        $content = app()->view->render($viewName, $params, $layout);
        return (new self())
            ->setContentType("text/html")
            ->setContent($content);
    }

    /**
     * Set response status to an error code and flash error messages.
     *
     * @param array $errors An array of error messages.
     * @param int $status The HTTP status code for the response.
     * @return self The Response instance.
     */
    public function withErrors(array $errors, int $status = 400): self
    {
        $this->setStatus($status);
        session()->flash('_errors', $errors);
        session()->flash('_old', request()->data());
        return $this;
    }
}
