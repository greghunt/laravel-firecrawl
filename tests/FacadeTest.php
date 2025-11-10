<?php

namespace GregHunt\LaravelFirecrawl\Tests;

use GregHunt\LaravelFirecrawl\Facades\Firecrawl;
use HelgeSverre\Firecrawl\FirecrawlClient;

class FacadeTest extends TestCase
{
    public function test_facade_resolves_to_firecrawl_client(): void
    {
        $client = Firecrawl::getFacadeRoot();

        $this->assertInstanceOf(FirecrawlClient::class, $client);
    }

    public function test_facade_returns_same_instance(): void
    {
        $client1 = Firecrawl::getFacadeRoot();
        $client2 = Firecrawl::getFacadeRoot();

        $this->assertSame($client1, $client2);
    }

    public function test_facade_accessor_returns_correct_class(): void
    {
        $reflection = new \ReflectionClass(Firecrawl::class);
        $method = $reflection->getMethod('getFacadeAccessor');
        $method->setAccessible(true);

        $accessor = $method->invoke(new Firecrawl());

        $this->assertEquals(FirecrawlClient::class, $accessor);
    }

    public function test_facade_can_access_client_instance(): void
    {
        $this->app['config']->set('firecrawl.api_key', 'test-key');
        $this->app->forgetInstance(FirecrawlClient::class);

        $client = Firecrawl::getFacadeRoot();

        $this->assertInstanceOf(FirecrawlClient::class, $client);
    }
}
