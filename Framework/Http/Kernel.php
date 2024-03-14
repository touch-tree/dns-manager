<?php

namespace Framework\Http;

use Framework\Foundation\View;
use Framework\Routing\Router;

/**
 * The Kernel class is the central HTTP component of the application.
 *
 * This class is responsible for handling HTTP requests and preparing responses, including middleware processing and event emits.
 *
 * @package Framework\Http
 */
class Kernel
{
    /**
     * Router instance.
     *
     * @var Router
     */
    protected Router $router;

    /**
     * Kernel constructor.
     *
     * @param Router $router The router instance.
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Handle an incoming HTTP request.
     *
     * @param Request $request The incoming HTTP request to be handled.
     * @return Response|null The response to the request, or null if no response is generated.
     */
    public function handle(Request $request): ?Response
    {
        $response = $this->prepare_response($request, $this->router::dispatch($request));

        if (is_a($response, Response::class)) {
            echo $response->send();
        }

        $this->terminate($request);

        return $response;
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param Request $request The HTTP request object.
     * @return void
     */
    protected function terminate(Request $request)
    {
        $request->session()->forget(['flash', 'errors']);
    }

    /**
     * Prepare a response for the request.
     *
     * @param Request $request The HTTP request object.
     * @param View|RedirectResponse|JsonResponse|null $response The response to be prepared.
     * @return Response|null The prepared response object, or null if no valid response is generated.
     */
    private function prepare_response(Request $request, $response): ?Response
    {
        if ($response instanceof RedirectResponse) {
            $request->flash();
            return $response;
        }

        if ($response instanceof JsonResponse) {
            return $response;
        }

        if ($response instanceof View) {
            return response($response->render(), Response::HTTP_OK, $response->get_headers());
        }

        return response(view('errors.404')->render(), Response::HTTP_NOT_FOUND);
    }
}