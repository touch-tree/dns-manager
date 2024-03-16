<?php

namespace Framework\Http;

use Error;
use Framework\Foundation\Session;
use Framework\Support\Url;
use LogicException;

/**
 * The RedirectResponse class represents a redirect response.
 *
 * This class facilitates redirection to specific routes or URLs, including support for flash data.
 *
 * @package Framework\Http
 */
class RedirectResponse extends Response
{
    /**
     * The destination path for the redirect.
     *
     * @var string|null
     */
    protected ?string $path;

    /**
     * RedirectResponse constructor.
     *
     * @param int $status_code [optional] The status code for the redirect. Default is 301 (Moved Permanently).
     */
    public function __construct(int $status_code = Response::HTTP_MOVED_PERMANENTLY)
    {
        $headers = new HeaderBag();

        $headers
            ->set('Content-Type', 'application/json')
            ->set('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->set('Pragma', 'no-cache')
            ->set('Expires', '0');

        parent::__construct(null, $status_code, $headers);
    }

    /**
     * Redirect back to the previous page or the base URL if no referer is provided.
     *
     * This method retrieves the URL from the 'Referer' header in the HTTP request headers.
     * If the 'Referer' header is not present, it defaults to the base URL.
     *
     * @return RedirectResponse
     */
    public function back(): RedirectResponse
    {
        $this->path = request()->headers()->get('referer') ?? Url::base_url();

        return $this;
    }

    /**
     * Create a new Redirect instance for the specified route.
     *
     * @param string $path The destination path for the redirect.
     * @return RedirectResponse
     *
     * @throws Error If the provided route is invalid.
     */
    public function route(string $path): RedirectResponse
    {
        $this->path = route($path) ?: $path;

        return $this;
    }

    /**
     * Attach flash data to the redirect.
     *
     * @param string $key The key for the flash data.
     * @param mixed $value The value of the flash data.
     * @return RedirectResponse
     *
     * @throws LogicException If the 'path' property is not set.
     */
    public function with(string $key, $value): RedirectResponse
    {
        session()->flash($key, $value);

        return $this;
    }

    /**
     * Add custom error messages to the session's 'errors' array.
     *
     * This method is useful when you need to manually add custom error messages
     * outside the typical form request validation.
     *
     * @param array $errors An associative array where keys represent error keys and values represent error messages.
     * @return RedirectResponse
     */
    public function with_errors(array $errors): RedirectResponse
    {
        foreach ($errors as $key => $value) {
            session()->push('errors.form.' . $key, $value);
        }

        return $this;
    }

    /**
     * Perform the actual redirection and terminate the script.
     *
     * Sends the redirection header and exits the script execution.
     *
     * @return void
     */
    public function send()
    {
        parent::send();

        header('Location: ' . $this->path);

        exit();
    }
}