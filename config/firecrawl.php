<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firecrawl API Key
    |--------------------------------------------------------------------------
    |
    | Your Firecrawl API key. You can obtain this from your Firecrawl
    | dashboard at https://firecrawl.dev
    |
    */

    'api_key' => env('FIRECRAWL_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Firecrawl API URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the Firecrawl API. This typically doesn't need to be
    | changed unless you're using a self-hosted instance.
    |
    */

    'api_url' => env('FIRECRAWL_API_URL', 'https://api.firecrawl.dev/'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout (Milliseconds)
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in milliseconds. Default is 60000 (60 seconds)
    | if not specified. Set to null to use the SDK's default.
    |
    */

    'timeout_ms' => env('FIRECRAWL_TIMEOUT_MS'),

    /*
    |--------------------------------------------------------------------------
    | Max Retries
    |--------------------------------------------------------------------------
    |
    | The maximum number of retry attempts for failed requests. Default is 3
    | if not specified. Set to null to use the SDK's default.
    |
    */

    'max_retries' => env('FIRECRAWL_MAX_RETRIES'),

    /*
    |--------------------------------------------------------------------------
    | Backoff Factor
    |--------------------------------------------------------------------------
    |
    | The exponential backoff multiplier for retries. Default is 0.5 if not
    | specified. Set to null to use the SDK's default.
    |
    */

    'backoff_factor' => env('FIRECRAWL_BACKOFF_FACTOR'),
];
