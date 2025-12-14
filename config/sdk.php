<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Your API key for authentication. This should be kept secret and set
    | via environment variables.
    |
    */
    'api_key' => env('SDK_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Base URI
    |--------------------------------------------------------------------------
    |
    | The base URI for API requests. Typically this will be different for
    | sandbox/production environments.
    |
    */
    'base_uri' => env('SDK_BASE_URI', 'https://api.example.com'),

    /*
    |--------------------------------------------------------------------------
    | Sandbox Mode
    |--------------------------------------------------------------------------
    |
    | When enabled, the SDK will use sandbox/test credentials and endpoints.
    | Set to false in production.
    |
    */
    'sandbox' => env('SDK_SANDBOX', true),

    /*
    |--------------------------------------------------------------------------
    | Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in seconds.
    |
    */
    'timeout' => env('SDK_TIMEOUT', 30),
];
