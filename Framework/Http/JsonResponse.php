<?php

namespace Framework\Http;

/**
 * The JsonResponse represents an HTTP response containing JSON data.
 *
 * This class is responsible for wrapping up data in JSON format and sending it as an HTTP response.
 *
 * @package Framework\Http
 */
class JsonResponse extends Response
{
    /**
     * JsonResponse constructor.
     *
     * @param array $data The data to be JSON-encoded and sent in the response.
     * @param int $status_code [optional] The HTTP status code for the response. Default is 200 (OK).
     */
    public function __construct(array $data, int $status_code = Response::HTTP_OK)
    {
        parent::__construct($data, $status_code, request()->headers()->set('Content-Type', 'application/json'));
    }

    /**
     * Sends the JSON response to the client.
     *
     * This method sets the 'Content-Type' header to 'application/json' and echoes the JSON-encoded content.
     *
     * @return string The JSON-encoded content.
     */
    public function send(): string
    {
        return json_encode(parent::send());
    }
}
