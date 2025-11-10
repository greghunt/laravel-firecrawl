<?php

namespace GregHunt\LaravelFirecrawl\Tests;

use GregHunt\LaravelFirecrawl\FirecrawlServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            FirecrawlServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Firecrawl' => \GregHunt\LaravelFirecrawl\Facades\Firecrawl::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('firecrawl.api_key', 'test-api-key');
    }
}
