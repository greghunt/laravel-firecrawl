<?php

namespace GregHunt\LaravelFirecrawl;

use HelgeSverre\Firecrawl\Firecrawl;
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

        $this->app->singleton(Firecrawl::class, function ($app) {
            $config = $app['config']['firecrawl'];

            return new Firecrawl(
                apiKey: $config['api_key'],
                apiUrl: $config['api_url'] ?? null,
                timeout: $config['timeout'] ?? null,
                maxRetries: $config['max_retries'] ?? null,
                retryBackoff: $config['retry_backoff'] ?? null,
            );
        });

        $this->app->alias(Firecrawl::class, 'firecrawl');
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
