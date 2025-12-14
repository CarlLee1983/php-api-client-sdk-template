# {{PACKAGE_NAME}}

[![CI](https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}/actions/workflows/ci.yml/badge.svg)](https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}/actions/workflows/ci.yml)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://www.php.net/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

[繁體中文](./README_ZH.md) | English

{{PACKAGE_DESCRIPTION}}

## Features

- 🚀 **PHP 8.1+** with strict types and readonly properties
- 📦 **PSR-4 Autoloading** with `{{NAMESPACE}}` namespace
- 🔌 **Injectable HTTP Client** for easy mocking and testing
- ✅ **Type-safe Configuration** with validation
- 🛡️ **Comprehensive Error Handling** with custom exceptions
- 🎯 **Laravel Support** with auto-discovery
- 📝 **Full PHPDoc Documentation** for IDE support

## Requirements

- PHP 8.1 or higher
- ext-json

## Installation

```bash
composer require {{PACKAGE_NAME}}
```

## Quick Start

### Basic Configuration

```php
use {{NAMESPACE}}\Config;
use {{NAMESPACE}}\Client;

$client = new Client(new Config(
    apiKey: getenv('API_KEY'),
    baseUri: 'https://api.example.com'
));
```

### Making Requests

```php
// Your SDK usage examples here
$response = $client->someMethod();
```

## Laravel Integration

This package supports Laravel's auto-discovery. After installation, the service provider will be automatically registered.

### Publish Configuration

```bash
php artisan vendor:publish --provider="{{NAMESPACE}}\Laravel\ServiceProvider"
```

### Configure Environment

Add the following to your `.env` file:

```env
SDK_API_KEY=your-api-key
SDK_BASE_URI=https://api.example.com
SDK_SANDBOX=true
```

### Usage in Laravel

```php
use {{NAMESPACE}}\Client;

class YourController
{
    public function __construct(private Client $client)
    {
    }

    public function index()
    {
        return $this->client->someMethod();
    }
}
```

## Error Handling

```php
use {{NAMESPACE}}\Exception\HttpException;
use {{NAMESPACE}}\Exception\ValidationException;
use {{NAMESPACE}}\Exception\ConfigException;

try {
    $response = $client->someMethod();
} catch (ValidationException $e) {
    // Handle validation errors
    foreach ($e->getErrors() as $field => $errors) {
        echo "{$field}: " . implode(', ', $errors) . "\n";
    }
} catch (HttpException $e) {
    // Handle HTTP errors
    echo "HTTP Error {$e->getStatusCode()}: {$e->getMessage()}\n";
} catch (ConfigException $e) {
    // Handle configuration errors
    echo "Config Error: {$e->getMessage()}\n";
}
```

## Testing

This library includes an injectable HTTP client interface for easy testing:

```php
use {{NAMESPACE}}\Http\HttpClientInterface;
use {{NAMESPACE}}\Http\HttpResponse;

$mockClient = new class implements HttpClientInterface {
    public function request(
        string $method,
        string $url,
        array $headers = [],
        array $body = []
    ): HttpResponse {
        return new HttpResponse(200, json_encode([
            'status' => 'success',
        ]));
    }
};

$client = new Client($config, $mockClient);
```

## Running Tests

```bash
composer install
composer test
```

## Documentation

For detailed API reference, see the [API Documentation](./docs/API.md).

## Contributing

Please see [CONTRIBUTING.md](./CONTRIBUTING.md) for details.

## Security

For security vulnerabilities, please see [SECURITY.md](./SECURITY.md).

## License

This project is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.

## Links

- [GitHub Repository](https://github.com/{{REPO_OWNER}}/{{REPO_NAME}})
- [Packagist](https://packagist.org/packages/{{PACKAGE_NAME}})
