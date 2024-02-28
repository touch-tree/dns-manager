<?php

namespace App\Framework\Http;

/**
 * This class is responsible for wrapping up the object that is sent to a request in JSON format
 *
 * @package App\Framework\Http
 */
class JsonResponse
{
    /**
     * The HTTP status code for the response.
     *
     * @var int
     */
    private int $status_code;

    /**
     * The content to be JSON-encoded and sent in the response.
     *
     * @var array
     */
    private array $content;

    /**
     * JsonResponse constructor.
     *
     * @param array $content The content to be JSON-encoded and sent in the response.
     * @param int $status_code The HTTP status code for the response. Default is 200 (OK).
     */
    public function __construct(array $content, int $status_code = 200)
    {
        $this->content = $content;
        $this->status_code = $status_code;
    }

    /**
     * Sends the JSON response to the client.
     *
     * Sets the 'Content-Type' header to 'application/json' and echoes the JSON-encoded content.
     */
    public function send()
    {
        header('Content-Type: application/json', true, $this->status_code);

        return json_encode($this->content);
    }
}
