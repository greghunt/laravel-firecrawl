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
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout for API requests in milliseconds. Default is null which
    | uses the SDK's default timeout.
    |
    */

    'timeout' => env('FIRECRAWL_TIMEOUT'),

    /*
    |--------------------------------------------------------------------------
    | Max Retries
    |--------------------------------------------------------------------------
    |
    | The maximum number of retry attempts for failed requests. Default is
    | null which uses the SDK's default retry behavior.
    |
    */

    'max_retries' => env('FIRECRAWL_MAX_RETRIES'),

    /*
    |--------------------------------------------------------------------------
    | Retry Backoff
    |--------------------------------------------------------------------------
    |
    | The exponential backoff factor for retries. Default is null which uses
    | the SDK's default backoff behavior.
    |
    */

    'retry_backoff' => env('FIRECRAWL_RETRY_BACKOFF'),
];
