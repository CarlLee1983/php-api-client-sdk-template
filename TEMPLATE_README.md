# PHP API Client SDK Template

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.1-8892BF.svg)](https://www.php.net/)

[繁體中文](./TEMPLATE_README_ZH.md) | English

A production-ready PHP SDK template for building **API client libraries**. Use this template to quickly bootstrap new SDK projects that integrate with third-party APIs (payment gateways, SaaS services, etc.).

## Features

- 🚀 **PHP 8.1+** with strict types and readonly properties
- 📦 **PSR-4 Autoloading** ready configuration
- 🔌 **Injectable HTTP Client** for easy testing
- ✅ **Type-safe Base Classes** (BaseClient, BaseConfig)
- 🛡️ **Exception Hierarchy** (Base, Config, Http, Validation)
- 🎯 **Laravel Support** with ServiceProvider and Facade
- 🧪 **PHPUnit Tests** pre-configured
- 🔄 **GitHub Actions** for CI/CD
- 📝 **Documentation Templates** (README, CONTRIBUTING, SECURITY)

## Ideal For

This template is designed for building SDKs that:

- ✅ Integrate with **REST APIs** (payment gateways, SaaS services)
- ✅ Require **HTTP client** functionality
- ✅ Need **configuration management** (API keys, base URLs)
- ✅ Support **Laravel** integration
- ✅ Follow **PSR standards**

**Examples**: TapPay SDK, LINE Pay SDK, Stripe SDK, Twilio SDK, etc.

## Quick Start

### 1. Clone or Download

```bash
# Clone the template
git clone https://github.com/CarlLee1983/php-api-client-sdk-template.git my-new-sdk
cd my-new-sdk
```

### 2. Run the Initialization Script

```bash
./init.sh
```

The script will guide you through:
- Composer vendor name (e.g., `carllee1983`)
- Package name (e.g., `my-sdk`)
- Package description
- PHP namespace (e.g., `Vendor\MySdk`)
- GitHub username (defaults to vendor name)
- Repository name (defaults to package name)
- Author information
- Whether to include Laravel support

### 3. Install Dependencies

```bash
composer install
```

### 4. Run Tests

```bash
composer test
```

## What's Included

### Base Classes

| Class | Description |
|-------|-------------|
| `BaseClient` | HTTP client with GET, POST, PUT, DELETE methods |
| `BaseConfig` | Configuration base with validation |
| `ConfigInterface` | Contract for configuration classes |

### Exception Classes

| Exception | Description |
|-----------|-------------|
| `BaseException` | Base exception for all SDK errors |
| `ConfigException` | Configuration validation errors |
| `HttpException` | HTTP request/response errors |
| `ValidationException` | Input validation errors |

### HTTP Abstraction

| Class | Description |
|-------|-------------|
| `HttpClientInterface` | Injectable HTTP client interface |
| `HttpResponse` | HTTP response wrapper |
| `NativeHttpClient` | Default implementation (no dependencies) |

### Laravel Integration

| Class | Description |
|-------|-------------|
| `ServiceProvider` | Auto-discovery service provider |
| `Facade` | Facade base class |
| `config/sdk.php` | Configuration file |

## Template Variables

The following placeholders are replaced during initialization:

| Variable | Description | Example |
|----------|-------------|---------|
| `{{PACKAGE_NAME}}` | Composer package name | `carllee1983/my-sdk` |
| `{{PACKAGE_DESCRIPTION}}` | Package description | `A PHP SDK for...` |
| `{{NAMESPACE}}` | PHP namespace | `MyCompany\MySdk` |
| `{{NAMESPACE_ESCAPED}}` | Escaped for JSON | `MyCompany\\MySdk` |
| `{{REPO_OWNER}}` | GitHub username | `CarlLee1983` |
| `{{REPO_NAME}}` | Repository name | `my-sdk-php` |
| `{{AUTHOR_NAME}}` | Author name | `Carl Lee` |
| `{{AUTHOR_EMAIL}}` | Author email | `carl@example.com` |
| `{{YEAR}}` | Current year | `2024` |

## Project Structure

```
php-api-client-sdk-template/
├── .github/
│   ├── ISSUE_TEMPLATE/
│   │   ├── bug_report.yml
│   │   ├── config.yml
│   │   └── feature_request.yml
│   └── PULL_REQUEST_TEMPLATE.md
│
├── templates/                     # CI files (copied during init)
│   └── .github/
│       ├── workflows/
│       │   ├── ci.yml
│       │   └── release.yml
│       └── dependabot.yml
│
├── config/
│   └── sdk.php                    # Laravel config
│
├── src/
│   ├── Contracts/
│   │   └── ConfigInterface.php
│   ├── Exception/
│   │   ├── BaseException.php
│   │   ├── ConfigException.php
│   │   ├── HttpException.php
│   │   └── ValidationException.php
│   ├── Http/
│   │   ├── HttpClientInterface.php
│   │   ├── HttpResponse.php
│   │   └── NativeHttpClient.php
│   ├── Laravel/
│   │   ├── Facade.php
│   │   └── ServiceProvider.php
│   ├── BaseClient.php
│   └── BaseConfig.php
│
├── tests/
│   ├── Unit/
│   │   ├── BaseClientTest.php
│   │   ├── ExceptionTest.php
│   │   └── HttpResponseTest.php
│   └── TestCase.php
│
├── .gitignore
├── CHANGELOG.md
├── CONTRIBUTING.md
├── CONTRIBUTING_ZH.md
├── LICENSE
├── README.md
├── README_ZH.md
├── SECURITY.md
├── TEMPLATE_README.md             # Template usage guide (removed after init)
├── TEMPLATE_README_ZH.md          # Template usage guide - Chinese
├── composer.json
├── init.sh                        # Initialization script
└── phpunit.xml
```

> **Note**: The `templates/` directory contains GitHub CI/CD workflows that will be copied to `.github/` during initialization. This prevents CI failures in the template repository itself.

## After Initialization

Once you've run `init.sh`, you'll want to:

1. **Implement your SDK**
   - Create your `Config` class extending `BaseConfig`
   - Create your `Client` class extending `BaseClient`
   - Add API methods

2. **Update Documentation**
   - Update README with actual usage examples
   - Document your API methods

3. **Register on Packagist**
   - Submit at https://packagist.org/packages/submit

## Example: Creating a Simple SDK

After initialization, here's how to build your SDK:

```php
<?php
// src/Config.php

declare(strict_types=1);

namespace MyCompany\MySdk;

use MyCompany\MySdk\Exception\ConfigException;

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

```php
<?php
// src/Client.php

declare(strict_types=1);

namespace MyCompany\MySdk;

final class Client extends BaseClient
{
    public function getUsers(): array
    {
        return $this->get('/users', [], [
            'Authorization' => 'Bearer ' . $this->config->apiKey,
        ]);
    }

    public function createUser(array $data): array
    {
        return $this->postJson('/users', $data, [
            'Authorization' => 'Bearer ' . $this->config->apiKey,
        ]);
    }
}
```

## Contributing

Contributions to improve the template are welcome! Please see [CONTRIBUTING.md](./CONTRIBUTING.md).

## License

This template is licensed under the MIT License - see the [LICENSE](./LICENSE) file for details.

## Author

Created by [Carl Lee](https://github.com/CarlLee1983)
