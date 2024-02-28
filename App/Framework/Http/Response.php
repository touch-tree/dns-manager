<?php

namespace App\Framework\Http;

/**
 * This class represents an HTTP response in a web application.
 *
 * @package App\Framework\Http
 */
class Response
{
    /**
     * Content of the response.
     *
     * @var mixed
     */
    private $content;

    /**
     * HTTP status code of the response.
     *
     * @var int
     */
    private int $status_code;

    /**
     * HeaderBag instance to manage HTTP headers.
     *
     * @var HeaderBag
     */
    private HeaderBag $headers;

    /**
     * Response constructor.
     *
     * @param mixed $content The content of the response.
     * @param int $status_code The HTTP status code of the response.
     * @param HeaderBag $headers The HeaderBag instance containing HTTP headers.
     */
    public function __construct($content, int $status_code, HeaderBag $headers)
    {
        $this->content = $content;
        $this->status_code = $status_code;
        $this->headers = $headers;
    }

    /**
     * Set the content of the response.
     *
     * @param mixed $content The content of the response.
     * @return $this
     */
    public function set_content($content): Response
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Set the HTTP status code of the response.
     *
     * @param int $status_code The HTTP status code.
     * @return $this
     */
    public function set_status_code(int $status_code): Response
    {
        $this->status_code = $status_code;

        return $this;
    }

    /**
     * Get the content of the response.
     *
     * @return mixed The content of the response.
     */
    public function get_content()
    {
        return $this->content;
    }

    /**
     * Get the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function get_status_code(): int
    {
        return $this->status_code;
    }

    /**
     * Get the HeaderBag instance containing HTTP headers.
     *
     * @return HeaderBag The HeaderBag instance.
     */
    public function headers(): HeaderBag
    {
        return $this->headers;
    }

    /**
     * Send the response by sending HTTP headers and content.
     *
     * @return mixed The content of the response.
     */
    public function send()
    {
        foreach ($this->headers()->all() as $key => $value) {
            header($key . ': ' . $value);
        }

        http_response_code($this->get_status_code());

        return $this->get_content();
    }
}