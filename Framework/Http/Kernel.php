<?php

namespace Framework\Http;

use Framework\Foundation\ParameterBag;
use Framework\Foundation\View;
use Framework\Routing\Router;

/**
 * This class is the central component of our application responsible for handling HTTP requests
 * and preparing responses, including middleware processing and event emits.
 *
 * @package Framework\Http
 */
class Kernel
{
    /**
     * Handle request.
     *
     * @param Request $request The incoming HTTP request to be handled.
     */
    public function handle(Request $request)
    {
        $this->prepare_response(app(Router::class)::dispatch($request), $request);
    }

    /**
     * Prepare response for request.
     *
     * @param View|RedirectResponse|JsonResponse|null $response The response to be prepared.
     * @param Request $request The HTTP request object.
     * @return void
     */
    private function prepare_response($response, Request $request): void
    {
        $session = $request->session();

        if ($response instanceof RedirectResponse) {
            $request->flash();
            $response->send();
        }

        if ($response instanceof JsonResponse) {
            echo $response->send();
        }

        if ($response instanceof View) {
            echo $response
                ->with('form_errors', new ParameterBag($session->get('errors.form', [])))
                ->with('errors', new ParameterBag($session->get('errors.errors', [])))
                ->render();
        }

        $session->forget(['flash', 'errors']);
    }
}