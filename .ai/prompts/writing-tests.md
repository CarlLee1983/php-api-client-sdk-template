# Writing Tests

## Context

This guide covers testing patterns for SDKs built with `php-api-client-sdk-template`.

## Test Structure

```
tests/
├── TestCase.php           # Base test class
├── Unit/
│   ├── BaseClientTest.php
│   ├── ConfigTest.php
│   ├── ExceptionTest.php
│   └── HttpResponseTest.php
└── Feature/               # Optional: Integration tests
    └── ApiIntegrationTest.php
```

## Creating a Mock HTTP Client

The template uses an injectable `HttpClientInterface`, making it easy to mock:

```php
<?php

use YourNamespace\Http\HttpClientInterface;
use YourNamespace\Http\HttpResponse;

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
```

## Testing Client Methods

### Basic Success Test

```php
public function test_list_resources_returns_array(): void
{
    $mock = $this->createMockHttpClient(
        new HttpResponse(200, json_encode([
            'data' => [
                ['id' => '1', 'name' => 'Resource 1'],
                ['id' => '2', 'name' => 'Resource 2'],
            ],
        ]))
    );

    $client = new Client(new Config('test-api-key'), $mock);
    $result = $client->listResources();

    $this->assertArrayHasKey('data', $result);
    $this->assertCount(2, $result['data']);
}
```

### Testing Error Responses

```php
public function test_throws_http_exception_on_server_error(): void
{
    $mock = $this->createMockHttpClient(
        new HttpResponse(500, json_encode(['error' => 'Internal Server Error']))
    );

    $client = new Client(new Config('test-api-key'), $mock);

    $this->expectException(HttpException::class);
    $this->expectExceptionMessage('Server error');

    $client->listResources();
}

public function test_throws_http_exception_on_not_found(): void
{
    $mock = $this->createMockHttpClient(
        new HttpResponse(404, json_encode(['error' => 'Resource not found']))
    );

    $client = new Client(new Config('test-api-key'), $mock);

    $this->expectException(HttpException::class);

    $client->getResource('nonexistent');
}
```

### Testing Validation

```php
public function test_throws_validation_exception_for_empty_id(): void
{
    $client = new Client(new Config('test-api-key'));

    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage('id');

    $client->getResource('');
}

public function test_throws_validation_exception_for_invalid_amount(): void
{
    $client = new Client(new Config('test-api-key'));

    $this->expectException(ValidationException::class);

    $client->createPayment(['amount' => -100]);
}
```

## Testing Configuration

```php
<?php

namespace YourNamespace\Tests\Unit;

use YourNamespace\Config;
use YourNamespace\Exception\ConfigException;
use YourNamespace\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_can_create_config_with_valid_values(): void
    {
        $config = new Config(
            apiKey: 'test-api-key',
            baseUri: 'https://api.example.com',
            sandbox: true
        );

        $this->assertEquals('test-api-key', $config->apiKey);
        $this->assertEquals('https://api.example.com', $config->getBaseUri());
        $this->assertTrue($config->isSandbox());
    }

    public function test_throws_exception_for_empty_api_key(): void
    {
        $this->expectException(ConfigException::class);
        $this->expectExceptionMessage('API key');

        new Config(apiKey: '');
    }

    public function test_throws_exception_for_whitespace_api_key(): void
    {
        $this->expectException(ConfigException::class);

        new Config(apiKey: '   ');
    }
}
```

## Testing HTTP Response Helpers

```php
public function test_json_returns_decoded_array(): void
{
    $response = new HttpResponse(200, '{"key": "value"}');

    $this->assertEquals(['key' => 'value'], $response->json());
}

public function test_is_successful_for_2xx_status(): void
{
    $this->assertTrue((new HttpResponse(200, ''))->isSuccessful());
    $this->assertTrue((new HttpResponse(201, ''))->isSuccessful());
    $this->assertTrue((new HttpResponse(204, ''))->isSuccessful());
    $this->assertFalse((new HttpResponse(400, ''))->isSuccessful());
}
```

## Advanced: Request Capturing Mock

For testing request details:

```php
private function createCapturingMockClient(HttpResponse $response): object
{
    return new class ($response) implements HttpClientInterface {
        public string $lastMethod = '';
        public string $lastUrl = '';
        public array $lastHeaders = [];
        public array $lastBody = [];

        public function __construct(private HttpResponse $response) {}

        public function request(
            string $method,
            string $url,
            array $headers = [],
            array $body = []
        ): HttpResponse {
            $this->lastMethod = $method;
            $this->lastUrl = $url;
            $this->lastHeaders = $headers;
            $this->lastBody = $body;
            return $this->response;
        }
    };
}

public function test_sends_correct_headers(): void
{
    $mock = $this->createCapturingMockClient(new HttpResponse(200, '{}'));
    $client = new Client(new Config('my-api-key'), $mock);

    $client->listResources();

    $this->assertEquals('GET', $mock->lastMethod);
    $this->assertArrayHasKey('Authorization', $mock->lastHeaders);
    $this->assertEquals('Bearer my-api-key', $mock->lastHeaders['Authorization']);
}
```

## Data Providers

For testing multiple scenarios:

```php
/**
 * @dataProvider invalidAmountProvider
 */
public function test_rejects_invalid_amounts(mixed $amount, string $expectedError): void
{
    $client = new Client(new Config('test-key'));

    $this->expectException(ValidationException::class);
    $this->expectExceptionMessage($expectedError);

    $client->createPayment(['amount' => $amount]);
}

public static function invalidAmountProvider(): array
{
    return [
        'negative' => [-100, 'greater than zero'],
        'zero' => [0, 'greater than zero'],
        'string' => ['abc', 'must be numeric'],
        'null' => [null, 'required'],
    ];
}
```
