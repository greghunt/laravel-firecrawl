<?php

namespace GregHunt\LaravelFirecrawl;

use HelgeSverre\Firecrawl\FirecrawlClient;
use Illuminate\Support\ServiceProvider;

class FirecrawlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/firecrawl.php', 'firecrawl'
        );

        // Register the base Firecrawl client
        $this->app->singleton(FirecrawlClient::class, function ($app) {
            $config = $app['config']['firecrawl'];

            return new FirecrawlClient(
                apiKey: $config['api_key'],
                apiUrl: $config['api_url'] ?? null,
                timeoutMs: $config['timeout_ms'] ?? null,
                maxRetries: $config['max_retries'] ?? null,
                backoffFactor: $config['backoff_factor'] ?? null,
            );
        });

        // Register the Laravel wrapper client
        $this->app->singleton(LaravelFirecrawlClient::class, function ($app) {
            return new LaravelFirecrawlClient(
                $app->make(FirecrawlClient::class)
            );
        });

        // Alias points to the Laravel wrapper for facade usage
        $this->app->alias(LaravelFirecrawlClient::class, 'firecrawl');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/firecrawl.php' => config_path('firecrawl.php'),
            ], 'firecrawl-config');
        }
    }
}
