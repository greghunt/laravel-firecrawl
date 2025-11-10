<?php

namespace GregHunt\LaravelFirecrawl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \HelgeSverre\Firecrawl\DTO\Document scrape(string $url, array $options = [])
 * @method static mixed startCrawl(string $url, array $options = [])
 * @method static mixed getCrawlStatus(string $jobId)
 * @method static mixed crawl(string $url, array $options = [], ?int $pollingInterval = null, ?int $timeout = null)
 * @method static mixed cancelCrawl(string $jobId)
 * @method static mixed startBatchScrape(array $urls, array $options = [])
 * @method static mixed batchScrape(array $urls, array $options = [], ?int $pollingInterval = null, ?int $timeout = null)
 * @method static array map(string $url, array $options = [])
 * @method static array search(string $query, array $options = [])
 * @method static array extract(array $options)
 * @method static \HelgeSverre\Firecrawl\FirecrawlClient getClient()
 *
 * @see \GregHunt\LaravelFirecrawl\LaravelFirecrawlClient
 */
class Firecrawl extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \GregHunt\LaravelFirecrawl\LaravelFirecrawlClient::class;
    }
}
