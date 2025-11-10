<?php

namespace GregHunt\LaravelFirecrawl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \HelgeSverre\Firecrawl\Data\ScrapeResult scrape(string $url, array $options = [])
 * @method static \HelgeSverre\Firecrawl\Data\CrawlJob startCrawl(string $url, array $options = [])
 * @method static \HelgeSverre\Firecrawl\Data\CrawlStatus getCrawlStatus(string $jobId)
 * @method static \HelgeSverre\Firecrawl\Data\CrawlStatus crawl(string $url, array $options = [], ?int $pollingInterval = null, ?int $timeout = null)
 * @method static \HelgeSverre\Firecrawl\Data\CancelCrawlResponse cancelCrawl(string $jobId)
 * @method static \HelgeSverre\Firecrawl\Data\BatchScrapeJob startBatchScrape(array $urls, array $options = [])
 * @method static \HelgeSverre\Firecrawl\Data\BatchScrapeStatus batchScrape(array $urls, array $options = [], ?int $pollingInterval = null, ?int $timeout = null)
 * @method static \HelgeSverre\Firecrawl\Data\MapResult map(string $url, array $options = [])
 * @method static \HelgeSverre\Firecrawl\Data\SearchResult search(string $query, array $options = [])
 * @method static \HelgeSverre\Firecrawl\Data\ExtractResult extract(array $urls, array $options = [])
 *
 * @see \HelgeSverre\Firecrawl\FirecrawlClient
 */
class Firecrawl extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \HelgeSverre\Firecrawl\FirecrawlClient::class;
    }
}
