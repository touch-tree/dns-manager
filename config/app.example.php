<?php

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
|
| This file contains configuration settings for your application.
| including the port and any other relevant components, such as routes, ports
| or subdomains, necessary for accessing the entrypoint of your application.
|
|--------------------------------------------------------------------------
*/

return [

    /**
     * Make sure that the token contains the necessary permissions for
     * this application to function properly.
     */
    'api_token' => '',

    /**
     * Ensure that the client has admin permissions over
     * every account and zone to avoid conflicts.
     */
    'api_client_id' => '',

    /**
     * URL to the api.
     */
    'api_url' => 'https://api.cloudflare.com/client/v4',

];