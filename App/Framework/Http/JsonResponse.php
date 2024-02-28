<?php

namespace App\Framework\Http;

/**
 * This class is responsible for wrapping up the object that is sent to a request in JSON format
 *
 * @package App\Framework\Http
 */
class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     *
     * @param array $data The data to be JSON-encoded and sent in the response.
     * @param int $status_code The HTTP status code for the response. Default is 200 (OK).
     */
    public function __construct(array $data, int $status_code = 200)
    {
        parent::__construct($data, $status_code, request()->headers()->set('Content-Type', 'application/json'));
    }

    /**
     * Sends the JSON response to the client.
     *
     * Sets the 'Content-Type' header to 'application/json' and echoes the JSON-encoded content.
     */
    public function send()
    {
        return json_encode($this->send());
    }
}
