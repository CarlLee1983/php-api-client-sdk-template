<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Tests\Unit;

use {{NAMESPACE}}\BaseClient;
use {{NAMESPACE}}\BaseConfig;
use {{NAMESPACE}}\Contracts\ConfigInterface;
use {{NAMESPACE}}\Exception\HttpException;
use {{NAMESPACE}}\Http\HttpClientInterface;
use {{NAMESPACE}}\Http\HttpResponse;
use {{NAMESPACE}}\Tests\TestCase;

/**
 * Tests for BaseClient class.
 */
class BaseClientTest extends TestCase
{
    private function createMockClient(HttpResponse $response): HttpClientInterface
    {
        return new class ($response) implements HttpClientInterface {
            public function __construct(private HttpResponse $response)
            {
            }

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

    private function createTestConfig(): ConfigInterface
    {
        return new class extends BaseConfig {
            public function __construct()
            {
                parent::__construct('https://api.example.com', true);
            }
        };
    }

    private function createTestClient(HttpClientInterface $httpClient): BaseClient
    {
        $config = $this->createTestConfig();

        return new class ($config, $httpClient) extends BaseClient {
            public function testGet(string $path): array
            {
                return $this->get($path);
            }

            public function testPost(string $path, array $data): array
            {
                return $this->postJson($path, $data);
            }

            public function testPut(string $path, array $data): array
            {
                return $this->putJson($path, $data);
            }

            public function testDelete(string $path): array
            {
                return $this->delete($path);
            }
        };
    }

    public function test_get_request(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(200, '{"id": 1, "name": "Test"}')
        );

        $client = $this->createTestClient($mockHttp);
        $result = $client->testGet('/users/1');

        $this->assertEquals(['id' => 1, 'name' => 'Test'], $result);
    }

    public function test_post_request(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(201, '{"id": 1, "created": true}')
        );

        $client = $this->createTestClient($mockHttp);
        $result = $client->testPost('/users', ['name' => 'Test']);

        $this->assertEquals(['id' => 1, 'created' => true], $result);
    }

    public function test_put_request(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(200, '{"id": 1, "updated": true}')
        );

        $client = $this->createTestClient($mockHttp);
        $result = $client->testPut('/users/1', ['name' => 'Updated']);

        $this->assertEquals(['id' => 1, 'updated' => true], $result);
    }

    public function test_delete_request(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(200, '{"deleted": true}')
        );

        $client = $this->createTestClient($mockHttp);
        $result = $client->testDelete('/users/1');

        $this->assertEquals(['deleted' => true], $result);
    }

    public function test_empty_response_returns_empty_array(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(204, '')
        );

        $client = $this->createTestClient($mockHttp);
        $result = $client->testDelete('/users/1');

        $this->assertEquals([], $result);
    }

    public function test_server_error_throws_exception(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(500, '{"error": "Server error"}')
        );

        $client = $this->createTestClient($mockHttp);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Server error');

        $client->testGet('/users/1');
    }

    public function test_client_error_throws_exception(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(404, '{"error": "Not found"}')
        );

        $client = $this->createTestClient($mockHttp);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Client error');

        $client->testGet('/users/1');
    }

    public function test_invalid_json_throws_exception(): void
    {
        $mockHttp = $this->createMockClient(
            new HttpResponse(200, 'not valid json')
        );

        $client = $this->createTestClient($mockHttp);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('Unable to decode response');

        $client->testGet('/users/1');
    }

    public function test_get_config(): void
    {
        $mockHttp = $this->createMockClient(new HttpResponse(200, '{}'));
        $client = $this->createTestClient($mockHttp);

        $config = $client->getConfig();

        $this->assertEquals('https://api.example.com', $config->getBaseUri());
        $this->assertTrue($config->isSandbox());
    }
}
