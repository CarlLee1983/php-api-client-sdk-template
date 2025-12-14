# Implement a New SDK

## Context

You are helping to implement a new PHP SDK using the `php-api-client-sdk-template`. The template provides base classes for HTTP communication, configuration, and error handling.

## Prerequisites

Before starting, ensure you have:
1. Run `./init.sh` to initialize the project with your package details
2. Run `composer install` to install dependencies
3. Verified tests pass with `composer test`

## Task: Create the Main SDK Classes

### Step 1: Create the Config Class

Create `src/Config.php` extending `BaseConfig`:

```php
<?php

declare(strict_types=1);

namespace YourNamespace;

use YourNamespace\Exception\ConfigException;

final class Config extends BaseConfig
{
    public function __construct(
        public readonly string $apiKey,
        string $baseUri = 'https://api.example.com',
        bool $sandbox = true
    ) {
        if (empty(trim($apiKey))) {
            throw new ConfigException('API key is required');
        }
        
        parent::__construct($baseUri, $sandbox);
    }
}
```

### Step 2: Create the Main Client Class

Create `src/Client.php` extending `BaseClient`:

```php
<?php

declare(strict_types=1);

namespace YourNamespace;

use YourNamespace\Http\HttpClientInterface;

final class Client extends BaseClient
{
    public function __construct(
        Config $config,
        ?HttpClientInterface $httpClient = null
    ) {
        parent::__construct($config, $httpClient);
    }

    /**
     * Get a list of resources.
     *
     * @return array<string, mixed>
     */
    public function listResources(): array
    {
        return $this->get('/resources', [], $this->buildHeaders());
    }

    /**
     * Create a new resource.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function createResource(array $data): array
    {
        return $this->postJson('/resources', $data, $this->buildHeaders());
    }

    /**
     * Build common request headers.
     *
     * @return array<string, string>
     */
    private function buildHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->config->apiKey,
            'Content-Type' => 'application/json',
        ];
    }
}
```

### Step 3: Update Laravel ServiceProvider

If using Laravel, update `src/Laravel/ServiceProvider.php`:

```php
$this->app->singleton(Client::class, function ($app) {
    return new Client(new Config(
        apiKey: config('sdk.api_key'),
        baseUri: config('sdk.base_uri'),
        sandbox: config('sdk.sandbox', true),
    ));
});
```

### Step 4: Write Tests

Create `tests/Unit/ClientTest.php`:

```php
<?php

declare(strict_types=1);

namespace YourNamespace\Tests\Unit;

use YourNamespace\Client;
use YourNamespace\Config;
use YourNamespace\Http\HttpClientInterface;
use YourNamespace\Http\HttpResponse;
use YourNamespace\Tests\TestCase;

class ClientTest extends TestCase
{
    private function createMockHttpClient(HttpResponse $response): HttpClientInterface
    {
        return new class ($response) implements HttpClientInterface {
            public function __construct(private HttpResponse $response) {}

            public function request(
                string $method,
                string $url,
                array $headers = [],
                array $body = []
            ): HttpResponse {
                return $this->response;
            }
        };
    }

    public function test_list_resources(): void
    {
        $mock = $this->createMockHttpClient(
            new HttpResponse(200, json_encode(['data' => []]))
        );

        $client = new Client(
            new Config('test-api-key'),
            $mock
        );

        $result = $client->listResources();

        $this->assertArrayHasKey('data', $result);
    }
}
```

## Verification

After implementation:
1. Run `composer test` to ensure all tests pass
2. Run `composer install` to verify autoloading
3. Check PHPDoc is complete for all public methods
