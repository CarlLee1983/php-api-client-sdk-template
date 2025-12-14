<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Tests\Unit;

use {{NAMESPACE}}\Http\HttpResponse;
use {{NAMESPACE}}\Tests\TestCase;

/**
 * Tests for HttpResponse class.
 */
class HttpResponseTest extends TestCase
{
    public function test_successful_response(): void
    {
        $response = new HttpResponse(200, '{"status": "ok"}');

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isClientError());
        $this->assertFalse($response->isServerError());
        $this->assertEquals(200, $response->statusCode);
    }

    public function test_client_error_response(): void
    {
        $response = new HttpResponse(404, '{"error": "not found"}');

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isClientError());
        $this->assertFalse($response->isServerError());
    }

    public function test_server_error_response(): void
    {
        $response = new HttpResponse(500, '{"error": "internal error"}');

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isClientError());
        $this->assertTrue($response->isServerError());
    }

    public function test_json_decoding(): void
    {
        $response = new HttpResponse(200, '{"name": "test", "value": 123}');

        $json = $response->json();

        $this->assertIsArray($json);
        $this->assertEquals('test', $json['name']);
        $this->assertEquals(123, $json['value']);
    }

    public function test_json_returns_null_for_empty_body(): void
    {
        $response = new HttpResponse(204, '');

        $this->assertNull($response->json());
    }

    public function test_json_returns_null_for_invalid_json(): void
    {
        $response = new HttpResponse(200, 'not valid json');

        $this->assertNull($response->json());
    }

    public function test_get_header(): void
    {
        $response = new HttpResponse(200, '', [
            'Content-Type' => 'application/json',
            'X-Request-Id' => '12345',
        ]);

        $this->assertEquals('application/json', $response->getHeader('Content-Type'));
        $this->assertEquals('application/json', $response->getHeader('content-type'));
        $this->assertEquals('12345', $response->getHeader('X-Request-Id'));
        $this->assertNull($response->getHeader('Non-Existent'));
    }
}
