# Laravel Firecrawl

A Laravel package wrapper for the [Firecrawl PHP SDK](https://github.com/HelgeSverre/firecrawl-php-sdk), providing a convenient facade and configuration for web scraping and crawling.

## Requirements

- PHP 8.3 or higher
- Laravel 11.0 or higher

## Installation

Install the package via Composer:

```bash
composer require greghunt/laravel-firecrawl
```

The package will automatically register itself via Laravel's package discovery.

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=firecrawl-config
```

This will create a `config/firecrawl.php` file where you can customize the package settings.

Add your Firecrawl API key to your `.env` file:

```env
FIRECRAWL_API_KEY=your-api-key-here
```

### Configuration Options

All configuration options can be set via environment variables:

```env
FIRECRAWL_API_KEY=your-api-key-here
FIRECRAWL_API_URL=https://api.firecrawl.dev/
FIRECRAWL_TIMEOUT_MS=60000
FIRECRAWL_MAX_RETRIES=3
FIRECRAWL_BACKOFF_FACTOR=0.5
```

## Usage

### Using the Facade

The easiest way to use Firecrawl is through the facade:

```php
use GregHunt\LaravelFirecrawl\Facades\Firecrawl;

// Scrape a single page
$result = Firecrawl::scrape('https://example.com');

// Access the scraped content
echo $result->markdown;
echo $result->html;
```

### Dependency Injection

You can also inject the Firecrawl client into your classes:

```php
use HelgeSverre\Firecrawl\FirecrawlClient;

class YourController extends Controller
{
    public function __construct(
        protected FirecrawlClient $firecrawl
    ) {}

    public function scrape()
    {
        $result = $this->firecrawl->scrape('https://example.com');
        return response()->json($result);
    }
}
```

## Features

### Scraping

Scrape a single webpage:

```php
use GregHunt\LaravelFirecrawl\Facades\Firecrawl;

$result = Firecrawl::scrape('https://example.com', [
    'formats' => ['markdown', 'html', 'links'],
    'onlyMainContent' => true,
]);

echo $result->markdown;
```

### Crawling

Start an asynchronous crawl:

```php
$job = Firecrawl::startCrawl('https://example.com', [
    'limit' => 100,
    'maxDepth' => 3,
]);

// Check crawl status
$status = Firecrawl::getCrawlStatus($job->id);

// Or use blocking crawl that waits for completion
$result = Firecrawl::crawl('https://example.com', [
    'limit' => 10,
]);
```

### Batch Scraping

Scrape multiple URLs at once:

```php
$urls = [
    'https://example.com/page1',
    'https://example.com/page2',
    'https://example.com/page3',
];

$result = Firecrawl::batchScrape($urls);

foreach ($result->data as $page) {
    echo $page->markdown;
}
```

### Site Mapping

Generate a site structure map:

```php
$map = Firecrawl::map('https://example.com', [
    'search' => 'blog/*',
]);

foreach ($map->links as $link) {
    echo $link;
}
```

### Search

Search for content:

```php
$results = Firecrawl::search('Laravel tutorials', [
    'limit' => 10,
]);

foreach ($results->data as $result) {
    echo $result->title;
    echo $result->url;
}
```

### Data Extraction

Extract structured data using AI:

```php
$urls = ['https://example.com/product'];

$result = Firecrawl::extract($urls, [
    'prompt' => 'Extract product name, price, and description',
]);
```

## Advanced Options

### Custom Scraping Options

```php
$result = Firecrawl::scrape('https://example.com', [
    'formats' => ['markdown', 'html', 'links', 'screenshot'],
    'onlyMainContent' => true,
    'includeTags' => ['article', 'main'],
    'excludeTags' => ['nav', 'footer'],
    'headers' => [
        'User-Agent' => 'Custom Agent',
    ],
    'waitFor' => 1000, // Wait 1 second before scraping
    'mobile' => true,  // Use mobile viewport
]);
```

### Crawl Options

```php
$result = Firecrawl::crawl('https://example.com', [
    'limit' => 100,
    'maxDepth' => 3,
    'allowBackwardLinks' => false,
    'allowExternalLinks' => false,
    'includePaths' => ['/blog/*'],
    'excludePaths' => ['/admin/*'],
    'webhook' => 'https://your-app.com/webhook',
]);
```

## Error Handling

The package uses the exceptions from the underlying Firecrawl SDK:

```php
use HelgeSverre\Firecrawl\Exceptions\FirecrawlException;

try {
    $result = Firecrawl::scrape('https://example.com');
} catch (FirecrawlException $e) {
    Log::error('Firecrawl error: ' . $e->getMessage());
}
```

## Testing

### Running Package Tests

This package includes a comprehensive test suite. To run the tests:

```bash
composer test
```

The test suite includes:
- Service Provider registration tests
- Facade functionality tests
- Configuration tests
- Dependency injection tests

All tests use Orchestra Testbench to simulate a Laravel environment.

### Testing Your Implementation

When testing your application code that uses this package, you can mock the Firecrawl facade:

```php
use GregHunt\LaravelFirecrawl\Facades\Firecrawl;
use HelgeSverre\Firecrawl\Data\ScrapeResult;

Firecrawl::shouldReceive('scrape')
    ->once()
    ->with('https://example.com')
    ->andReturn(new ScrapeResult(
        markdown: '# Test Content',
        success: true,
    ));
```

Alternatively, you can mock the `FirecrawlClient` in the container:

```php
use HelgeSverre\Firecrawl\FirecrawlClient;
use Mockery\MockInterface;

$this->mock(FirecrawlClient::class, function (MockInterface $mock) {
    $mock->shouldReceive('scrape')
        ->once()
        ->andReturn(/* mocked response */);
});
```

## Credits

- [Greg Hunt](https://greghunt.dev)
- [Helge Sverre](https://github.com/HelgeSverre) - Original PHP SDK
- [Firecrawl](https://firecrawl.dev) - Web scraping service
- [Claude 4.5 Sonnet](https://claude.ai) - AI agent

## License

MIT License. See [LICENSE](LICENSE) for more information.

## Links

- [Firecrawl Website](https://firecrawl.dev)
- [Firecrawl PHP SDK](https://github.com/HelgeSverre/firecrawl-php-sdk)
- [Laravel Documentation](https://laravel.com/docs)
