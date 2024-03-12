<?php

namespace Framework\Http;

use Error;
use Framework\Foundation\Session;
use LogicException;

/**
 * This RedirectResponse class represents a redirect response, allowing for easy redirection in a web application.
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
     * The session manager for storing flash data.
     *
     * @var Session
     */
    protected Session $session;

    /**
     * Redirect constructor.
     *
     * @param Session $session The session manager for storing flash data.
     * @param int $status_code The status code for the redirect.
     */
    public function __construct(Session $session, int $status_code = Response::HTTP_MOVED_PERMANENTLY)
    {
        parent::__construct(null, $status_code, request()->headers());

        $this->session = $session;
    }

    /**
     * Set the path to the URL specified in the 'Referer' header or the Base URL if not present.
     *
     * This method is commonly used in web applications to redirect back to the previous page.
     * It retrieves the URL from the 'Referer' header in the HTTP request headers. If the 'Referer'
     * header is not present, it defaults to the Base URL.
     *
     * @return RedirectResponse
     */
    public function back(): RedirectResponse
    {
        $this->path = request()->headers()->get('referer') ?? base_url();

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
        $route = route($path);

        if (is_null($route)) {
            throw new Error('Route is invalid: ' . $path);
        }

        $this->path = $route;

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
        if (is_null($this->path)) {
            throw new LogicException('Cannot use with method without setting the path property');
        }

        $this->session->flash($key, $value);

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
            $_SESSION['errors']['form'][$key][] = $value;
        }

        return $this;
    }

    /**
     * Perform the actual redirection and terminate the script.
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