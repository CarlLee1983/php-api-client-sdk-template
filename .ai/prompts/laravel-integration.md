# Laravel Integration

## Context

This guide covers Laravel integration patterns for SDKs built with `php-api-client-sdk-template`.

## Setup

The template includes Laravel auto-discovery support. After installation, the ServiceProvider is automatically registered.

## Configuration

### 1. Publish Configuration

```bash
php artisan vendor:publish --provider="YourNamespace\Laravel\ServiceProvider"
```

### 2. Environment Variables

Add to `.env`:

```env
SDK_API_KEY=your-api-key
SDK_BASE_URI=https://api.example.com
SDK_SANDBOX=true
SDK_TIMEOUT=30
```

### 3. Update ServiceProvider

Edit `src/Laravel/ServiceProvider.php`:

```php
<?php

declare(strict_types=1);

namespace YourNamespace\Laravel;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use YourNamespace\Client;
use YourNamespace\Config;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/sdk.php',
            'sdk'
        );

        $this->app->singleton(Client::class, function ($app) {
            return new Client(new Config(
                apiKey: config('sdk.api_key'),
                baseUri: config('sdk.base_uri'),
                sandbox: config('sdk.sandbox', true),
            ));
        });

        // Register an alias for convenience
        $this->app->alias(Client::class, 'sdk.client');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/sdk.php' => config_path('sdk.php'),
            ], 'config');
        }
    }

    public function provides(): array
    {
        return [
            Client::class,
            'sdk.client',
        ];
    }
}
```

### 4. Update Facade

Edit `src/Laravel/Facade.php`:

```php
<?php

declare(strict_types=1);

namespace YourNamespace\Laravel;

use Illuminate\Support\Facades\Facade as BaseFacade;
use YourNamespace\Client;

/**
 * @method static array listResources()
 * @method static array createResource(array $data)
 * @method static array getResource(string $id)
 *
 * @see \YourNamespace\Client
 */
class Facade extends BaseFacade
{
    protected static function getFacadeAccessor(): string
    {
        return Client::class;
    }
}
```

## Usage Patterns

### Dependency Injection (Recommended)

```php
<?php

namespace App\Http\Controllers;

use YourNamespace\Client;

class PaymentController extends Controller
{
    public function __construct(
        private Client $client
    ) {
    }

    public function index()
    {
        $resources = $this->client->listResources();
        return view('payments.index', compact('resources'));
    }
}
```

### Using Facade

First, add to `config/app.php`:

```php
'aliases' => [
    'SDK' => YourNamespace\Laravel\Facade::class,
],
```

Then use:

```php
use SDK;

$resources = SDK::listResources();
```

### Using App Container

```php
$client = app(YourNamespace\Client::class);
$resources = $client->listResources();
```

## Testing in Laravel

### Mocking the Client

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use YourNamespace\Client;
use Mockery;

class PaymentTest extends TestCase
{
    public function test_can_list_resources(): void
    {
        $mock = Mockery::mock(Client::class);
        $mock->shouldReceive('listResources')
            ->once()
            ->andReturn(['data' => []]);

        $this->app->instance(Client::class, $mock);

        $response = $this->get('/api/resources');

        $response->assertOk();
    }
}
```

### Using Fake HTTP Client

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use YourNamespace\Client;
use YourNamespace\Config;
use YourNamespace\Http\HttpClientInterface;
use YourNamespace\Http\HttpResponse;

class PaymentIntegrationTest extends TestCase
{
    private function createFakeClient(array $fakeResponses): void
    {
        $mock = new class ($fakeResponses) implements HttpClientInterface {
            private array $responses;
            private int $index = 0;

            public function __construct(array $responses)
            {
                $this->responses = $responses;
            }

            public function request(
                string $method,
                string $url,
                array $headers = [],
                array $body = []
            ): HttpResponse {
                return $this->responses[$this->index++] ?? new HttpResponse(500, '{}');
            }
        };

        $client = new Client(
            new Config(apiKey: 'test-key'),
            $mock
        );

        $this->app->instance(Client::class, $client);
    }

    public function test_payment_flow(): void
    {
        $this->createFakeClient([
            new HttpResponse(200, json_encode(['id' => '123', 'status' => 'pending'])),
            new HttpResponse(200, json_encode(['id' => '123', 'status' => 'completed'])),
        ]);

        // Test your flow...
    }
}
```

## Configuration File

Update `config/sdk.php`:

```php
<?php

return [
    'api_key' => env('SDK_API_KEY', ''),
    'base_uri' => env('SDK_BASE_URI', 'https://api.example.com'),
    'sandbox' => env('SDK_SANDBOX', true),
    'timeout' => env('SDK_TIMEOUT', 30),

    // Add SDK-specific configuration
    'retry' => [
        'times' => env('SDK_RETRY_TIMES', 3),
        'sleep' => env('SDK_RETRY_SLEEP', 100), // milliseconds
    ],
];
```
