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
     * @return void
     */
    public function setStatus(int $status)
    {
        $this->status = $status;       
    }

    /**
     * getter headers
     *
     * @param string|null $key
     * @return array|string|null
     */
    public function headers(?string $key = null): array|string|null
    {
        return $this->headers;
        /* if (is_null($key)) {
            return $this->headers;
        }

        return $this->headers[strtolower($key)] ?? null; */
    }

    /**
     * setter headers
     *
     * @param string $header
     * @param string $value
     * @return void
     */
    public function setHeader(string $header, string $value)
    {
        $this->headers[strtolower($header)] = $value;
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
     * @return void
     */
    public function setContentType(string $value)
    {
        $this->setHeader("Content-Type", $value);       
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
     * @return void
     */
    public function setContent(string $content)
    {
        $this->content = $content;       
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
}
