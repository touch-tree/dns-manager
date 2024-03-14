<?php

namespace Framework\Http;

use Error;
use Framework\Foundation\Session;

/**
 * The Redirect class provides methods to redirect users to specific routes or URLs, creating redirects with flash data,
 * redirect back to the previous page, and generate JSON responses.
 *
 * @package Framework\Http
 */
class Redirect
{
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
     *
     * @throws Error If the provided route is invalid.
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Redirect to a specified route or URL.
     *
     * @param string $path The path or URL to redirect to.
     * @return RedirectResponse The redirect response object.
     */
    public function to(string $path): RedirectResponse
    {
        $response = new RedirectResponse($this->session);

        return $response->route($path);
    }

    /**
     * Redirect back to the previous page or the base URL if no referer is provided.
     *
     * This method is commonly used to redirect users back to the previous page.
     * It retrieves the URL from the 'Referer' header in the HTTP request headers.
     * If the 'Referer' header is not present, it defaults to the base URL.
     *
     * @return RedirectResponse The redirect response object.
     */
    public function back(): RedirectResponse
    {
        $response = new RedirectResponse($this->session);

        return $response->back();
    }

    /**
     * Return a JsonResponse object with the provided data.
     *
     * @param array $data The data to be included in the JSON response.
     * @return JsonResponse The JSON response object.
     */
    public function json(array $data): JsonResponse
    {
        return new JsonResponse($data);
    }
}