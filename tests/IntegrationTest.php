<?php

namespace GregHunt\LaravelFirecrawl\Tests;

use GregHunt\LaravelFirecrawl\Facades\Firecrawl;
use GregHunt\LaravelFirecrawl\LaravelFirecrawlClient;
use HelgeSverre\Firecrawl\DTO\Document;
use HelgeSverre\Firecrawl\DTO\ScrapeOptions;
use HelgeSverre\Firecrawl\Enums\Format;
use HelgeSverre\Firecrawl\FirecrawlClient;
use HelgeSverre\Firecrawl\Http\HttpClientInterface;
use Mockery\MockInterface;

class IntegrationTest extends TestCase
{
    public function test_array_options_are_converted_to_dto(): void
    {
        // Mock the HTTP client to avoid actual API calls
        $mockHttpClient = \Mockery::mock(HttpClientInterface::class);
        $mockHttpClient->shouldReceive('post')
            ->once()
            ->withArgs(function ($endpoint, $body) {
                // Verify the options were properly converted
                return $endpoint === '/v2/scrape'
                    && $body['url'] === 'https://example.com'
                    && isset($body['onlyMainContent'])
                    && $body['onlyMainContent'] === true
                    && isset($body['mobile'])
                    && $body['mobile'] === true;
            })
            ->andReturn([
                'success' => true,
                'data' => [
                    'markdown' => '# Test Content',
                    'html' => '<h1>Test Content</h1>',
                ],
            ]);

        // Create a real FirecrawlClient with the mocked HTTP client
        $firecrawlClient = new FirecrawlClient(
            apiKey: 'test-key',
            httpClient: $mockHttpClient
        );

        // Create the Laravel wrapper
        $laravelClient = new LaravelFirecrawlClient($firecrawlClient);

        // Call scrape with array options (as shown in README)
        $result = $laravelClient->scrape('https://example.com', [
            'onlyMainContent' => true,
            'mobile' => true,
        ]);

        $this->assertInstanceOf(Document::class, $result);
        $this->assertEquals('# Test Content', $result->markdown);
    }

    public function test_format_strings_are_converted_to_enums(): void
    {
        // Mock the HTTP client
        $mockHttpClient = \Mockery::mock(HttpClientInterface::class);
        $mockHttpClient->shouldReceive('post')
            ->once()
            ->withArgs(function ($endpoint, $body) {
                // Verify formats are present
                return $endpoint === '/v2/scrape'
                    && isset($body['formats'])
                    && in_array('markdown', $body['formats'])
                    && in_array('html', $body['formats']);
            })
            ->andReturn([
                'success' => true,
                'data' => [
                    'markdown' => '# Test',
                    'html' => '<h1>Test</h1>',
                ],
            ]);

        // Create a real FirecrawlClient with the mocked HTTP client
        $firecrawlClient = new FirecrawlClient(
            apiKey: 'test-key',
            httpClient: $mockHttpClient
        );

        // Create the Laravel wrapper
        $laravelClient = new LaravelFirecrawlClient($firecrawlClient);

        // Call scrape with format strings (as shown in README)
        $result = $laravelClient->scrape('https://example.com', [
            'formats' => ['markdown', 'html'],
        ]);

        $this->assertInstanceOf(Document::class, $result);
    }

    public function test_empty_options_array_works(): void
    {
        // Mock the HTTP client
        $mockHttpClient = \Mockery::mock(HttpClientInterface::class);
        $mockHttpClient->shouldReceive('post')
            ->once()
            ->with('/v2/scrape', ['url' => 'https://example.com'])
            ->andReturn([
                'success' => true,
                'data' => [
                    'markdown' => '# Test',
                ],
            ]);

        $firecrawlClient = new FirecrawlClient(
            apiKey: 'test-key',
            httpClient: $mockHttpClient
        );

        $laravelClient = new LaravelFirecrawlClient($firecrawlClient);

        // Call scrape with no options
        $result = $laravelClient->scrape('https://example.com');

        $this->assertInstanceOf(Document::class, $result);
    }

    public function test_facade_works_end_to_end(): void
    {
        // Mock the HTTP client
        $mockHttpClient = \Mockery::mock(HttpClientInterface::class);
        $mockHttpClient->shouldReceive('post')
            ->once()
            ->andReturn([
                'success' => true,
                'data' => [
                    'markdown' => '# Facade Test',
                ],
            ]);

        // Replace the FirecrawlClient in the container with our mocked version
        $this->app->singleton(FirecrawlClient::class, function () use ($mockHttpClient) {
            return new FirecrawlClient(
                apiKey: 'test-key',
                httpClient: $mockHttpClient
            );
        });

        // Force the LaravelFirecrawlClient to be recreated with the new FirecrawlClient
        $this->app->forgetInstance(LaravelFirecrawlClient::class);

        // Use the facade
        $result = Firecrawl::scrape('https://example.com', [
            'onlyMainContent' => true,
        ]);

        $this->assertInstanceOf(Document::class, $result);
        $this->assertEquals('# Facade Test', $result->markdown);
    }
}
