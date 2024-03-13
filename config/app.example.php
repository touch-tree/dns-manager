<?php

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
|
| This file contains configuration settings for your application,
| including the base URL, project directory, development mode, and API details.
| Ensure that the provided API token and client ID have the necessary permissions.
|
| For more information, refer to the config function in Base.php.
|
|--------------------------------------------------------------------------
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | This value sets the base URL of the application. If provided, it should
    | be the full URL, including the protocol. Leave empty for a relative URL.
    |
    */
    'url' => '',

    /*
    |--------------------------------------------------------------------------
    | Development Mode
    |--------------------------------------------------------------------------
    |
    | Set this to true for development, and false for production. It controls
    | whether the application is in development mode or not.
    |
    */
    'development_mode' => true,

    /*
    |--------------------------------------------------------------------------
    | API Token
    |--------------------------------------------------------------------------
    |
    | The API token for authentication. Make sure the token contains the
    | necessary permissions for the application to function properly.
    |
    */
    'api_token' => '',

    /*
    |--------------------------------------------------------------------------
    | API Client ID
    |--------------------------------------------------------------------------
    |
    | The API client ID for authentication. Ensure that the client has admin
    | permissions over every account and zone to avoid conflicts.
    |
    */
    'api_client_id' => '',

    /*
    |--------------------------------------------------------------------------
    | API URL
    |--------------------------------------------------------------------------
    |
    | The URL to the API. It specifies the base URL for making API requests.
    |
    */
    'api_url' => 'https://api.cloudflare.com/client/v4',

    /*
    |--------------------------------------------------------------------------
    | Root CNAME Target
    |--------------------------------------------------------------------------
    |
    | The default CNAME target for the root domain. Specify the CNAME target
    | that should be used as the default for the root domain.
    |
    */
    'root_cname_target' => '',

    /*
    |--------------------------------------------------------------------------
    | Sub-CNAME Target
    |--------------------------------------------------------------------------
    |
    | The default CNAME target for subdomains. Specify the CNAME target that
    | should be used as the default for subdomains.
    |
    */
    'sub_cname_target' => '',

];