<?php

namespace GregHunt\LaravelFirecrawl\Tests;

class ConfigTest extends TestCase
{
    public function test_config_file_has_correct_structure(): void
    {
        $config = require __DIR__ . '/../config/firecrawl.php';

        $this->assertIsArray($config);
        $this->assertArrayHasKey('api_key', $config);
        $this->assertArrayHasKey('api_url', $config);
        $this->assertArrayHasKey('timeout_ms', $config);
        $this->assertArrayHasKey('max_retries', $config);
        $this->assertArrayHasKey('backoff_factor', $config);
    }

    public function test_default_api_url_is_correct(): void
    {
        $config = $this->app['config']->get('firecrawl');

        $this->assertEquals('https://api.firecrawl.dev/', $config['api_url']);
    }

    public function test_api_key_can_be_set(): void
    {
        $this->app['config']->set('firecrawl.api_key', 'my-secret-key');

        $apiKey = $this->app['config']->get('firecrawl.api_key');

        $this->assertEquals('my-secret-key', $apiKey);
    }

    public function test_timeout_ms_can_be_set(): void
    {
        $this->app['config']->set('firecrawl.timeout_ms', 120000);

        $timeout = $this->app['config']->get('firecrawl.timeout_ms');

        $this->assertEquals(120000, $timeout);
    }

    public function test_max_retries_can_be_set(): void
    {
        $this->app['config']->set('firecrawl.max_retries', 5);

        $maxRetries = $this->app['config']->get('firecrawl.max_retries');

        $this->assertEquals(5, $maxRetries);
    }

    public function test_backoff_factor_can_be_set(): void
    {
        $this->app['config']->set('firecrawl.backoff_factor', 2.0);

        $backoffFactor = $this->app['config']->get('firecrawl.backoff_factor');

        $this->assertEquals(2.0, $backoffFactor);
    }

    public function test_custom_api_url_can_be_set(): void
    {
        $this->app['config']->set('firecrawl.api_url', 'https://custom.firecrawl.dev/');

        $apiUrl = $this->app['config']->get('firecrawl.api_url');

        $this->assertEquals('https://custom.firecrawl.dev/', $apiUrl);
    }
}
