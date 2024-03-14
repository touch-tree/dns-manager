<?php

namespace Framework\Http;

/**
 * The Response class represents an HTTP response in the application.
 *
 * This class encapsulates the content, status code, and headers of an HTTP response.
 *
 * @package Framework\Http
 */
class Response
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    const HTTP_NO_CONTENT = 204;
    const HTTP_MOVED_PERMANENTLY = 301;
    const HTTP_FOUND = 302;
    const HTTP_NOT_MODIFIED = 304;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_INTERNAL_SERVER_ERROR = 500;

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
     * Add a header to the response.
     *
     * @param string $key The header key.
     * @param string $value The header value.
     * @return $this
     */
    public function with_header(string $key, string $value): Response
    {
        $this->headers->set($key, $value);

        return $this;
    }

    /**
     * Set the content type of the response.
     *
     * @param string $content_type The content type.
     * @return $this
     */
    public function with_content_type(string $content_type): Response
    {
        $this->headers->set('Content-Type', $content_type);

        return $this;
    }

    /**
     * Get the content of the response.
     *
     * @return mixed The content of the response.
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * Get the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function status_code(): int
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
     * Get JSON response.
     *
     * @param array $data The data to be JSON-encoded and sent in the response.
     * @param int $status_code The HTTP status code for the response. Default is 200 (OK).
     * @return JsonResponse The JsonResponse instance.
     */
    public function json(array $data, int $status_code = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse($data, $status_code);
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

        http_response_code($this->status_code());

        return $this->content();
    }
}
