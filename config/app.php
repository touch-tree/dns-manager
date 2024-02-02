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
     * this application to function properly
     */
    'api_token' => '_kGTRdiPGUDpFAGPbhRaI_VyL6QIQbdzSsRaD9zv',

    /**
     * Ensure that the client has admin permissions over
     * every account and zone to avoid conflicts
     */
    'api_client_id' => 'aacd53b551b73609b46c19bc0f2e328e',

    /**
     * URL to the api
     */
    'api_url' => 'https://api.cloudflare.com/client/v4',

];