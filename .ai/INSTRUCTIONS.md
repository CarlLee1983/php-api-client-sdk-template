# PHP API Client SDK Template - AI Instructions

## Overview

This is a PHP SDK template for building API client libraries. When a user initializes a new project using `init.sh`, all template variables (`{{VARIABLE}}`) will be replaced with actual values.

## Template Variables

The following placeholders are used throughout the codebase:

| Variable | Description | Example |
|----------|-------------|---------|
| `{{PACKAGE_NAME}}` | Composer package name | `acme/payment-sdk` |
| `{{PACKAGE_DESCRIPTION}}` | Package description | `A PHP SDK for ACME Payment API` |
| `{{NAMESPACE}}` | PHP namespace | `Acme\PaymentSdk` |
| `{{NAMESPACE_ESCAPED}}` | Escaped for JSON | `Acme\\PaymentSdk` |
| `{{REPO_OWNER}}` | GitHub username | `acme` |
| `{{REPO_NAME}}` | Repository name | `payment-sdk-php` |
| `{{AUTHOR_NAME}}` | Author name | `John Doe` |
| `{{AUTHOR_EMAIL}}` | Author email | `john@acme.com` |
| `{{YEAR}}` | Current year | `2024` |

## Core Components

### 1. BaseClient (`src/BaseClient.php`)
Abstract HTTP client with methods:
- `postJson(string $path, array $payload, array $headers = []): array`
- `get(string $path, array $query = [], array $headers = []): array`
- `putJson(string $path, array $payload, array $headers = []): array`
- `delete(string $path, array $headers = []): array`

### 2. BaseConfig (`src/BaseConfig.php`)
Configuration base class implementing `ConfigInterface`:
- `getBaseUri(): string`
- `isSandbox(): bool`

### 3. Exception Classes (`src/Exception/`)
- `BaseException` - Base exception class
- `ConfigException` - Configuration errors
- `HttpException` - HTTP request/response errors
- `ValidationException` - Input validation errors

### 4. HTTP Abstraction (`src/Http/`)
- `HttpClientInterface` - Interface for HTTP clients
- `HttpResponse` - HTTP response wrapper
- `NativeHttpClient` - Default implementation using file_get_contents

### 5. Laravel Integration (`src/Laravel/`)
- `ServiceProvider` - Laravel service provider
- `Facade` - Laravel facade base class
- `config/sdk.php` - Laravel configuration file

## File Naming Conventions

- PHP classes: PascalCase (e.g., `PaymentClient.php`)
- Interfaces: Suffix with `Interface` (e.g., `ConfigInterface.php`)
- Tests: Suffix with `Test` (e.g., `PaymentClientTest.php`)
- Exceptions: Suffix with `Exception` (e.g., `PaymentException.php`)

## Code Style

- PHP 8.1+ with `declare(strict_types=1)`
- Use readonly properties where applicable
- Follow PSR-12 coding standard
- Add PHPDoc for all public methods
