# Fix: Array Options Support for Firecrawl::scrape()

## Problem
The `Firecrawl::scrape()` facade method was throwing a type error when called with array options:

```
HelgeSverre\Firecrawl\FirecrawlClient::scrape(): Argument #2 ($options) must be of type ?HelgeSverre\Firecrawl\DTO\ScrapeOptions, array given
```

The README documentation showed that users should pass arrays:
```php
$result = Firecrawl::scrape('https://example.com', [
    'formats' => ['markdown', 'html', 'links'],
    'onlyMainContent' => true,
]);
```

But the underlying SDK's `FirecrawlClient::scrape()` method expected a `ScrapeOptions` DTO object.

## Solution
Created a Laravel wrapper class that handles array-to-DTO conversion:

### 1. New `LaravelFirecrawlClient` Class
- Location: `src/LaravelFirecrawlClient.php`
- Wraps the `FirecrawlClient` and accepts arrays
- Converts arrays to appropriate DTO objects (ScrapeOptions, CrawlOptions, etc.)
- Automatically converts format strings to Format enums
- Provides a `__call()` magic method to proxy other methods to the underlying client

### 2. Updated Service Provider
- `FirecrawlServiceProvider` now registers both:
  - `FirecrawlClient` (for direct SDK access)
  - `LaravelFirecrawlClient` (for facade usage)
- The `'firecrawl'` alias points to `LaravelFirecrawlClient`

### 3. Updated Facade
- `Firecrawl` facade now points to `LaravelFirecrawlClient`
- Updated PHPDoc blocks to reflect array parameters
- Added `getClient()` method to access underlying SDK client if needed

### 4. Test Coverage
- Added `tests/ScrapeTest.php` - Tests scrape method with various options
- Added `tests/IntegrationTest.php` - End-to-end tests with mocked HTTP client
- Updated existing tests to work with the new architecture

## Usage Examples

### Basic scrape (no options)
```php
use GregHunt\LaravelFirecrawl\Facades\Firecrawl;

$result = Firecrawl::scrape('https://example.com');
```

### Scrape with options (array format)
```php
$result = Firecrawl::scrape('https://example.com', [
    'formats' => ['markdown', 'html'],
    'onlyMainContent' => true,
    'mobile' => true,
    'waitFor' => 1000,
]);
```

### Access underlying SDK client (if needed)
```php
$laravelClient = app(LaravelFirecrawlClient::class);
$sdkClient = $laravelClient->getClient();
```

### Direct injection (backward compatible)
```php
// Still works - injects the SDK client directly
class MyController {
    public function __construct(
        protected FirecrawlClient $client
    ) {}
}

// New way - injects the Laravel wrapper
class MyController {
    public function __construct(
        protected LaravelFirecrawlClient $client
    ) {}
}
```

## Benefits
1. ✅ Matches README documentation - users can pass arrays
2. ✅ Backward compatible - can still inject `FirecrawlClient` directly
3. ✅ Type safe - arrays are converted to DTOs with validation
4. ✅ Testable - wrapper can be mocked unlike final `FirecrawlClient`
5. ✅ Laravel-friendly - follows Laravel conventions for facades

## Tests
All 31 tests pass:
- Service provider tests
- Facade tests
- Dependency injection tests
- Scrape functionality tests
- Integration tests
