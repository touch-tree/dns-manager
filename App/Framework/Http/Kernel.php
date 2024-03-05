<?php

namespace App\Framework\Http;

use App\Framework\Foundation\View;
use App\Framework\Routing\Router;

/**
 * This class is the central component of our application responsible for handling HTTP requests
 * and preparing responses, including middleware processing and event emits.
 *
 * @package App\Framework\Http
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
     * @param mixed $response The response to be prepared.
     * @param Request $request The HTTP request object.
     * @return void
     */
    private function prepare_response($response, Request $request): void
    {
        // middleware should handle session request logic not prepare response lol, fix this

        if ($response instanceof RedirectResponse) {
            session()->forget('flash');
            $request->flash();
            $response->send();
        }

        if ($response instanceof JsonResponse) {
            echo $response->send();
        }

        if ($response instanceof View) {
            echo $response->render();
        }

        session()->forget(['flash', 'errors']);
    }
}