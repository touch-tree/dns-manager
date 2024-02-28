<?php

namespace App\Framework\Http;

use App\Framework\Base\Session;
use Error;

/**
 * This class provides functionality for creating redirects with flash data.
 *
 * @package App\Framework\Http
 */
final class Redirect
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
     * Redirect towards a specified route or URL.
     *
     * @param string $path
     * @return RedirectResponse
     */
    public function to(string $path): RedirectResponse
    {
        $response = new RedirectResponse($this->session);

        return $response->route($path);
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
        $response = new RedirectResponse($this->session);

        return $response->back();
    }

    /**
     * Return a JsonResponse object.
     *
     * @param array $data
     * @return JsonResponse
     */
    public function json(array $data): JsonResponse
    {
        return new JsonResponse($data);
    }
}
