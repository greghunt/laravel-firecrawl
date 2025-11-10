<?php

namespace GregHunt\LaravelFirecrawl\Tests;

use GregHunt\LaravelFirecrawl\Facades\Firecrawl;
use HelgeSverre\Firecrawl\FirecrawlClient;

class DependencyInjectionTest extends TestCase
{
    public function test_can_inject_firecrawl_client_in_constructor(): void
    {
        $service = $this->app->make(TestService::class);

        $this->assertInstanceOf(FirecrawlClient::class, $service->getClient());
    }

    public function test_injected_client_matches_facade_root(): void
    {
        $service = $this->app->make(TestService::class);
        $facadeClient = Firecrawl::getFacadeRoot();

        $this->assertSame($service->getClient(), $facadeClient);
    }

    public function test_can_resolve_firecrawl_client_from_container(): void
    {
        $client = $this->app->make(FirecrawlClient::class);

        $this->assertInstanceOf(FirecrawlClient::class, $client);
    }

    public function test_can_resolve_firecrawl_client_by_alias(): void
    {
        $client = $this->app->make('firecrawl');

        $this->assertInstanceOf(FirecrawlClient::class, $client);
    }
}

// Test helper class
class TestService
{
    public function __construct(
        protected FirecrawlClient $client
    ) {}

    public function getClient(): FirecrawlClient
    {
        return $this->client;
    }
}
