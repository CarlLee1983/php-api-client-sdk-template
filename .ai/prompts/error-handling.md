# Error Handling Patterns

> **📖 使用方式**
>
> 將以下指令複製給 AI 助手：
>
> ```
> 請參考 .ai/prompts/error-handling.md，幫我設計錯誤處理：
>
> 需要處理的錯誤類型：
> - [例如：付款被拒絕、餘額不足]
> - [例如：API 回傳錯誤碼]
> - [例如：網路連線失敗]
> ```

---

## Context

This guide covers error handling patterns for SDKs built with `php-api-client-sdk-template`.

## Exception Hierarchy

```
BaseException
├── ConfigException      # Configuration errors
├── HttpException        # HTTP request/response errors
└── ValidationException  # Input validation errors
```

## When to Use Each Exception

### ConfigException

Use for configuration-related errors during SDK initialization:

```php
use YourNamespace\Exception\ConfigException;

final class Config extends BaseConfig
{
    public function __construct(
        public readonly string $apiKey,
        public readonly string $merchantId,
        string $baseUri = 'https://api.example.com',
        bool $sandbox = true
    ) {
        if (empty(trim($apiKey))) {
            throw new ConfigException('API key is required');
        }

        if (empty(trim($merchantId))) {
            throw new ConfigException('Merchant ID is required');
        }

        if (!str_starts_with($baseUri, 'https://')) {
            throw new ConfigException('Base URI must use HTTPS');
        }

        parent::__construct($baseUri, $sandbox);
    }
}
```

### ValidationException

Use for input validation errors before making API calls:

```php
use YourNamespace\Exception\ValidationException;

public function createPayment(array $data): array
{
    // Simple required field
    if (empty($data['amount'])) {
        throw ValidationException::required('amount');
    }

    // Invalid value
    if ($data['amount'] <= 0) {
        throw ValidationException::invalid('amount', 'must be greater than zero');
    }

    // Multiple errors
    $errors = [];
    if (empty($data['currency'])) {
        $errors['currency'] = ['Currency is required'];
    }
    if (empty($data['description'])) {
        $errors['description'] = ['Description is required'];
    }
    if (!empty($errors)) {
        throw new ValidationException('Validation failed', $errors);
    }

    return $this->postJson('/payments', $data, $this->buildHeaders());
}
```

### HttpException

The BaseClient automatically throws HttpException for HTTP errors. You can also throw it manually:

```php
use YourNamespace\Exception\HttpException;

// Thrown automatically by BaseClient for 4xx/5xx responses

// Manual throw for custom error handling
if ($response->statusCode === 429) {
    throw new HttpException(
        'Rate limit exceeded. Please retry after ' . $response->getHeader('Retry-After') . ' seconds',
        429,
        $response->body
    );
}
```

## API Error Handling Pattern

For APIs that return error details in the response body:

```php
public function processPayment(string $transactionId): array
{
    $response = $this->postJson('/payments/process', [
        'transaction_id' => $transactionId,
    ], $this->buildHeaders());

    // Check for API-level errors
    if (isset($response['status']) && $response['status'] !== 0) {
        throw new HttpException(
            $response['msg'] ?? 'Payment processing failed',
            $response['status'],
            json_encode($response)
        );
    }

    return $response;
}
```

## Client Error Handling Example

```php
use YourNamespace\Client;
use YourNamespace\Config;
use YourNamespace\Exception\ConfigException;
use YourNamespace\Exception\HttpException;
use YourNamespace\Exception\ValidationException;

try {
    $client = new Client(new Config(
        apiKey: getenv('API_KEY'),
        merchantId: getenv('MERCHANT_ID'),
    ));

    $result = $client->createPayment([
        'amount' => 1000,
        'currency' => 'TWD',
    ]);

} catch (ConfigException $e) {
    // Handle configuration errors
    error_log("Configuration error: {$e->getMessage()}");
    throw $e;

} catch (ValidationException $e) {
    // Handle validation errors with field-level details
    foreach ($e->getErrors() as $field => $errors) {
        error_log("Validation error for {$field}: " . implode(', ', $errors));
    }
    throw $e;

} catch (HttpException $e) {
    // Handle HTTP/API errors
    error_log("API error ({$e->getStatusCode()}): {$e->getMessage()}");
    
    // Try to get more details from response
    $details = $e->getDecodedResponse();
    if ($details) {
        error_log("Error details: " . json_encode($details));
    }
    throw $e;
}
```

## Creating Custom Exceptions

For SDK-specific errors, extend BaseException:

```php
<?php

declare(strict_types=1);

namespace YourNamespace\Exception;

final class PaymentException extends BaseException
{
    public function __construct(
        string $message,
        public readonly string $transactionId,
        public readonly string $errorCode,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    public static function declined(string $transactionId, string $reason): self
    {
        return new self(
            "Payment declined: {$reason}",
            $transactionId,
            'PAYMENT_DECLINED'
        );
    }

    public static function timeout(string $transactionId): self
    {
        return new self(
            'Payment processing timed out',
            $transactionId,
            'PAYMENT_TIMEOUT'
        );
    }
}
```
