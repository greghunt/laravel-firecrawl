<?php

namespace GregHunt\LaravelFirecrawl\Tests;

use GregHunt\LaravelFirecrawl\Facades\Firecrawl;
use GregHunt\LaravelFirecrawl\LaravelFirecrawlClient;
use HelgeSverre\Firecrawl\DTO\Document;
use Mockery\MockInterface;

class ScrapeTest extends TestCase
{
    public function test_scrape_with_no_options(): void
    {
        $this->mock(LaravelFirecrawlClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('scrape')
                ->once()
                ->with('https://example.com')
                ->andReturn(new Document(
                    markdown: '# Test Content',
                    html: '<h1>Test Content</h1>',
                ));
        });

        $result = Firecrawl::scrape('https://example.com');

        $this->assertInstanceOf(Document::class, $result);
        $this->assertEquals('# Test Content', $result->markdown);
    }

    public function test_scrape_with_array_options(): void
    {
        $this->mock(LaravelFirecrawlClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('scrape')
                ->once()
                ->with('https://example.com', ['onlyMainContent' => true])
                ->andReturn(new Document(
                    markdown: '# Test Content',
                    html: '<h1>Test Content</h1>',
                ));
        });

        $result = Firecrawl::scrape('https://example.com', [
            'onlyMainContent' => true,
        ]);

        $this->assertInstanceOf(Document::class, $result);
    }

    public function test_scrape_with_multiple_options(): void
    {
        $this->mock(LaravelFirecrawlClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('scrape')
                ->once()
                ->with('https://example.com', [
                    'onlyMainContent' => true,
                    'mobile' => true,
                    'waitFor' => 1000,
                ])
                ->andReturn(new Document(
                    markdown: '# Test Content',
                    html: '<h1>Test Content</h1>',
                ));
        });

        $result = Firecrawl::scrape('https://example.com', [
            'onlyMainContent' => true,
            'mobile' => true,
            'waitFor' => 1000,
        ]);

        $this->assertInstanceOf(Document::class, $result);
    }

    public function test_scrape_with_formats(): void
    {
        $this->mock(LaravelFirecrawlClient::class, function (MockInterface $mock) {
            $mock->shouldReceive('scrape')
                ->once()
                ->with('https://example.com', [
                    'formats' => ['markdown', 'html'],
                ])
                ->andReturn(new Document(
                    markdown: '# Test Content',
                    html: '<h1>Test Content</h1>',
                ));
        });

        $result = Firecrawl::scrape('https://example.com', [
            'formats' => ['markdown', 'html'],
        ]);

        $this->assertInstanceOf(Document::class, $result);
    }
}
