<?php

namespace GregHunt\LaravelFirecrawl;

use HelgeSverre\Firecrawl\DTO\BatchScrapeOptions;
use HelgeSverre\Firecrawl\DTO\CrawlOptions;
use HelgeSverre\Firecrawl\DTO\Document;
use HelgeSverre\Firecrawl\DTO\MapOptions;
use HelgeSverre\Firecrawl\DTO\ScrapeOptions;
use HelgeSverre\Firecrawl\Enums\Format;
use HelgeSverre\Firecrawl\FirecrawlClient;

/**
 * Laravel wrapper for Firecrawl client that accepts arrays and converts them to DTOs.
 */
class LaravelFirecrawlClient
{
    public function __construct(
        protected FirecrawlClient $client
    ) {}

    /**
     * Scrape a single URL.
     *
     * @param  string  $url  Target URL to scrape
     * @param  array<string, mixed>  $options  Scraping options as array
     * @return Document Scraped document with requested formats
     */
    public function scrape(string $url, array $options = []): Document
    {
        $scrapeOptions = empty($options) ? null : $this->convertToScrapeOptions($options);

        return $this->client->scrape($url, $scrapeOptions);
    }

    /**
     * Convert array options to ScrapeOptions DTO.
     */
    protected function convertToScrapeOptions(array $options): ScrapeOptions
    {
        // Convert string format names to Format enums
        if (isset($options['formats']) && is_array($options['formats'])) {
            $options['formats'] = array_map(
                fn($format) => is_string($format) ? Format::from($format) : $format,
                $options['formats']
            );
        }

        return new ScrapeOptions(...$options);
    }

    /**
     * Start a crawl job (async).
     *
     * @param  string  $url  Root URL to crawl
     * @param  array<string, mixed>  $options  Crawl configuration as array
     */
    public function startCrawl(string $url, array $options = []): mixed
    {
        $crawlOptions = empty($options) ? null : new CrawlOptions(...$options);

        return $this->client->startCrawl($url, $crawlOptions);
    }

    /**
     * Get the status and partial data of a crawl job.
     */
    public function getCrawlStatus(string $jobId): mixed
    {
        return $this->client->getCrawlStatus($jobId);
    }

    /**
     * Convenience waiter: start a crawl and poll until it finishes.
     *
     * @param  string  $url  Root URL to crawl
     * @param  array<string, mixed>  $options  Crawl configuration as array
     * @param  int|null  $pollInterval  Polling interval in seconds
     * @param  int|null  $timeout  Maximum wait time in seconds
     */
    public function crawl(
        string $url,
        array $options = [],
        ?int $pollInterval = null,
        ?int $timeout = null
    ): mixed {
        $crawlOptions = empty($options) ? null : new CrawlOptions(...$options);

        return $this->client->crawl($url, $crawlOptions, $pollInterval ?? 2, $timeout ?? 300);
    }

    /**
     * Cancel a crawl job.
     */
    public function cancelCrawl(string $jobId): mixed
    {
        return $this->client->cancelCrawl($jobId);
    }

    /**
     * Start a batch scrape job for multiple URLs (async).
     *
     * @param  string[]  $urls  URLs to scrape
     * @param  array<string, mixed>  $options  Batch options as array
     */
    public function startBatchScrape(array $urls, array $options = []): mixed
    {
        $batchOptions = empty($options) ? null : new BatchScrapeOptions(...$options);

        return $this->client->startBatchScrape($urls, $batchOptions);
    }

    /**
     * Convenience waiter: start a batch scrape and poll until it finishes.
     *
     * @param  string[]  $urls  URLs to scrape
     * @param  array<string, mixed>  $options  Batch options as array
     * @param  int|null  $pollInterval  Polling interval in seconds
     * @param  int|null  $timeout  Maximum wait time in seconds
     */
    public function batchScrape(
        array $urls,
        array $options = [],
        ?int $pollInterval = null,
        ?int $timeout = null
    ): mixed {
        $batchOptions = empty($options) ? null : new BatchScrapeOptions(...$options);

        return $this->client->batchScrape($urls, $batchOptions, $pollInterval ?? 2, $timeout ?? 300);
    }

    /**
     * Map a site to discover URLs.
     *
     * @param  string  $url  Root URL to map
     * @param  array<string, mixed>  $options  Mapping options as array
     * @return array<string, mixed> Discovered links
     */
    public function map(string $url, array $options = []): array
    {
        $mapOptions = empty($options) ? null : new MapOptions(...$options);

        return $this->client->map($url, $mapOptions);
    }

    /**
     * Search the web and optionally scrape each result.
     *
     * @param  string  $query  Search query string
     * @param  array<string, mixed>  $options  Additional search options
     * @return array<string, mixed> Search results
     */
    public function search(string $query, array $options = []): array
    {
        return $this->client->search($query, $options);
    }

    /**
     * Start an extract job (async).
     *
     * @param  array<string, mixed>  $options  Extraction options as array
     * @return array<string, mixed> Job ID or processing state
     */
    public function extract(array $options): array
    {
        return $this->client->extract($options);
    }

    /**
     * Get underlying Firecrawl client for advanced usage.
     */
    public function getClient(): FirecrawlClient
    {
        return $this->client;
    }

    /**
     * Proxy method calls to the underlying client.
     */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->client->$method(...$arguments);
    }
}
