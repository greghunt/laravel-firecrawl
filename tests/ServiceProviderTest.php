<?php

namespace GregHunt\LaravelFirecrawl\Tests;

use GregHunt\LaravelFirecrawl\FirecrawlServiceProvider;
use GregHunt\LaravelFirecrawl\LaravelFirecrawlClient;
use HelgeSverre\Firecrawl\FirecrawlClient;

class ServiceProviderTest extends TestCase
{
    public function test_service_provider_is_registered(): void
    {
        $providers = $this->app->getLoadedProviders();

        $this->assertArrayHasKey(FirecrawlServiceProvider::class, $providers);
    }

    public function test_firecrawl_client_is_bound_as_singleton(): void
    {
        $client1 = $this->app->make(FirecrawlClient::class);
        $client2 = $this->app->make(FirecrawlClient::class);

        $this->assertInstanceOf(FirecrawlClient::class, $client1);
        $this->assertSame($client1, $client2);
    }

    public function test_firecrawl_client_is_aliased(): void
    {
        $laravelClient = $this->app->make(LaravelFirecrawlClient::class);
        $aliasedClient = $this->app->make('firecrawl');

        $this->assertSame($laravelClient, $aliasedClient);
        $this->assertInstanceOf(LaravelFirecrawlClient::class, $aliasedClient);
    }

    public function test_config_is_merged(): void
    {
        $config = $this->app['config']->get('firecrawl');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('api_key', $config);
        $this->assertArrayHasKey('api_url', $config);
        $this->assertArrayHasKey('timeout_ms', $config);
        $this->assertArrayHasKey('max_retries', $config);
        $this->assertArrayHasKey('backoff_factor', $config);
    }

    public function test_client_uses_config_values(): void
    {
        $this->app['config']->set('firecrawl.api_key', 'custom-api-key');
        $this->app['config']->set('firecrawl.api_url', 'https://custom.api.url');
        $this->app['config']->set('firecrawl.timeout_ms', 90000);
        $this->app['config']->set('firecrawl.max_retries', 5);
        $this->app['config']->set('firecrawl.backoff_factor', 1.0);

        // Rebind the singleton with new config
        $this->app->forgetInstance(FirecrawlClient::class);

        $client = $this->app->make(FirecrawlClient::class);

        $this->assertInstanceOf(FirecrawlClient::class, $client);
    }

    public function test_client_works_with_minimal_config(): void
    {
        $this->app['config']->set('firecrawl.api_key', 'minimal-key');

        // Rebind the singleton with new config
        $this->app->forgetInstance(FirecrawlClient::class);

        $client = $this->app->make(FirecrawlClient::class);

        $this->assertInstanceOf(FirecrawlClient::class, $client);
    }
}
