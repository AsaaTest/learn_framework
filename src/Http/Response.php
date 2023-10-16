<?php

namespace Learn\Http;

class Response
{
    /**
     * status of response
     *
     * @var integer
     */
    protected int $status = 200;

    /**
     * headers of response
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * content of response
     *
     * @var string|null
     */
    protected ?string $content = null;

    /**
     * getter status
     *
     * @return integer
     */
    public function status(): int
    {
        return $this->status;
    }

    /**
     * setter status
     *
     * @param integer $status
     * @return self
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * getter headers
     *
     * @param string|null $key
     * @return array|string|null
     */
    public function headers(?string $key = null): array|string|null
    {
        if (is_null($key)) {
            return $this->headers;
        }

        return $this->headers[strtolower($key)] ?? null;
    }

    /**
     * setter headers
     *
     * @param string $header
     * @param string $value
     * @return self
     */
    public function setHeader(string $header, string $value): self
    {
        $this->headers[strtolower($header)] = $value;
        return $this;
    }

    /**
     * remove header
     *
     * @param string $header header to remove
     * @return void
     */
    public function removeHeader(string $header)
    {
        unset($this->headers[strtolower($header)]);
    }

    /**
     * set type of content
     *
     * @param string $value
     * @return self
     */
    public function setContentType(string $value): self
    {
        $this->setHeader("Content-Type", $value);
        return $this;
    }

    /**
     * getter content
     *
     * @return string|null
     */
    public function content(): ?string
    {
        return $this->content;
    }

    /**
     * setter content
     *
     * @param string $content
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Prepare response
     *
     * remove headers if content is null
     * definer content length depending of the content
     *
     * @return void
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
     * @return self An instance of the Response class with content in JSON format.
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
     * @param string $text Text to set as content.
     * @return self An instance of the Response class with plain text content.
     */
    public static function text(string $text): self
    {
        return (new self())
            ->setContentType("text/plain")
            ->setContent($text);
    }

    /**
     * Create a new instance of the Response class to redirect to another URI.
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

    public static function view(string $viewName, array $params = [], $layout = null): self
    {
        $content = app()->view->render($viewName, $params, $layout);
        return (new self())
            ->setContentType("text/html")
            ->setContent($content);
    }
}
