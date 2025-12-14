# Add New API Endpoint

> **📖 使用方式**
>
> 將以下指令複製給 AI 助手：
>
> ```
> 請參考 .ai/prompts/add-endpoint.md，在 Client 類別中新增 API 方法：
>
> 方法名稱：[METHOD_NAME]
> HTTP Method：[GET/POST/PUT/DELETE]
> 路徑：[/api/path/{id}]
> 參數：[列出參數和類型]
> ```

---

## Context

You are adding a new API endpoint to an existing SDK that was created from `php-api-client-sdk-template`.

## Task: Add a New API Method

### Step 1: Define the Method in Client

Add a new method to your `Client` class:

```php
/**
 * [Description of what this endpoint does]
 *
 * @param string $id The resource ID
 * @param array<string, mixed> $options Optional parameters
 * @return array<string, mixed> The API response
 * @throws HttpException When the API request fails
 * @throws ValidationException When input validation fails
 */
public function getResource(string $id, array $options = []): array
{
    if (empty($id)) {
        throw ValidationException::required('id');
    }

    $query = array_filter([
        'include' => $options['include'] ?? null,
        'fields' => $options['fields'] ?? null,
    ]);

    return $this->get("/resources/{$id}", $query, $this->buildHeaders());
}
```

### Step 2: Add Validation (if needed)

For complex validation, create a dedicated validator:

```php
private function validateResourceData(array $data): void
{
    $errors = [];

    if (empty($data['name'])) {
        $errors['name'] = ['Name is required'];
    }

    if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = ['Invalid email format'];
    }

    if (!empty($errors)) {
        throw new ValidationException('Validation failed', $errors);
    }
}
```

### Step 3: Create Response Types (Optional)

For type-safe responses, create a dedicated class:

```php
<?php

declare(strict_types=1);

namespace YourNamespace\Response;

final readonly class ResourceResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $status,
        public \DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            status: $data['status'],
            createdAt: new \DateTimeImmutable($data['created_at']),
        );
    }
}
```

### Step 4: Write Tests

```php
public function test_get_resource(): void
{
    $mock = $this->createMockHttpClient(
        new HttpResponse(200, json_encode([
            'id' => '123',
            'name' => 'Test Resource',
            'status' => 'active',
        ]))
    );

    $client = new Client(new Config('test-key'), $mock);
    $result = $client->getResource('123');

    $this->assertEquals('123', $result['id']);
    $this->assertEquals('Test Resource', $result['name']);
}

public function test_get_resource_with_empty_id_throws(): void
{
    $client = new Client(new Config('test-key'));

    $this->expectException(ValidationException::class);
    $client->getResource('');
}
```

### Step 5: Update Documentation

Add the new method to README.md:

```markdown
### Get Resource

```php
$resource = $client->getResource('resource-id', [
    'include' => 'metadata',
]);
```
```

## Common Patterns

### POST with JSON Body
```php
return $this->postJson('/resources', $data, $this->buildHeaders());
```

### PUT for Updates
```php
return $this->putJson("/resources/{$id}", $data, $this->buildHeaders());
```

### DELETE
```php
return $this->delete("/resources/{$id}", $this->buildHeaders());
```

### Query Parameters
```php
return $this->get('/resources', [
    'page' => $page,
    'per_page' => $perPage,
    'sort' => 'created_at',
], $this->buildHeaders());
```
