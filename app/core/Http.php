<?php

namespace App\Core;

/**
 * The Http class provides a simple interface for sending HTTP requests using cURL.
 * It allows you to set custom headers, send GET and POST requests, and handle
 * responses. This class is designed to simplify the interaction with APIs.
 *
 * @package App\Core
 */
class Http
{
    /**
     * Request headers
     *
     * @var array
     */
    public array $headers = [];

    /**
     * Request response
     *
     * @var bool|string
     */
    public $response;

    /**
     * Set headers for the request.
     *
     * @param array $headers The custom headers for the request.
     * @return self
     */
    public static function set_headers(array $headers): self
    {
        $instance = new self();
        $instance->headers = $headers;

        return $instance;
    }

    /**
     * Batch process cURL options.
     *
     * @param mixed $curl The cURL resource.
     * @param array $options The cURL options to set.
     * @return void
     */
    private function many_curl_setopt($curl, array $options)
    {
        foreach ($options as $option) {
            curl_setopt($curl, $option[0], $option[1]);
        }
    }

    /**
     * Send a request to the API endpoint.
     *
     * @param string $method The HTTP method (POST, GET, etc.).
     * @param string $endpoint The API endpoint URL.
     * @param array $data The data to send with the request.
     * @return bool|string
     */
    private function request(string $method, string $endpoint, array $data = [])
    {
        $curl = curl_init($endpoint);

        $this->many_curl_setopt(
            $curl,
            [
                [CURLOPT_CUSTOMREQUEST, $method],
                [CURLOPT_RETURNTRANSFER, true],
                [CURLOPT_HTTPHEADER, $this->headers],
            ]
        );

        if ($method === 'POST' || $method === 'PATCH') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($curl);

        if ($error = curl_error($curl)) {
            echo $error;
        }

        curl_close($curl);

        return $response;
    }

    /**
     * Send a PATCH request.
     *
     * @param string $endpoint The API endpoint URL.
     * @param array $data The data to send with the request.
     * @return Http
     */
    public function patch(string $endpoint, array $data = []): Http
    {
        $this->response = $this->request('PATCH', $endpoint, $data);

        return $this;
    }

    /**
     * Send a POST request.
     *
     * @param string $endpoint The API endpoint URL.
     * @param array $data The data to send with the request.
     * @return Http
     */
    public function post(string $endpoint, array $data = []): Http
    {
        $this->response = $this->request('POST', $endpoint, $data);

        return $this;
    }

    /**
     * Send a GET request.
     *
     * @param string $endpoint The API endpoint URL.
     * @param array $data The data to send with the request.
     * @return Http
     */
    public function get(string $endpoint, array $data = []): Http
    {
        $this->response = $this->request('GET', $endpoint, $data);

        return $this;
    }

    /**
     * Send a PUT request.
     *
     * @param string $endpoint The API endpoint URL.
     * @param array $data The data to send with the request.
     * @return Http
     */
    public function put(string $endpoint, array $data = []): Http
    {
        $this->response = $this->request('PUT', $endpoint, $data);

        return $this;
    }

    /**
     * Send a UPDATE request.
     *
     * @param string $endpoint The API endpoint URL.
     * @param array $data The data to send with the request.
     * @return Http
     */
    public function update(string $endpoint, array $data = []): Http
    {
        $this->response = $this->request('UPDATE', $endpoint, $data);

        return $this;
    }

    /**
     * Send a DELETE request.
     *
     * @param string $endpoint The API endpoint URL.
     * @param array $data The data to send with the request.
     * @return Http
     */
    public function delete(string $endpoint, array $data = []): Http
    {
        $this->response = $this->request('DELETE', $endpoint, $data);

        return $this;
    }

    /**
     * Get the response as JSON.
     *
     * @return array
     */
    public function json(): array
    {
        return json_decode($this->response, true);
    }
}
