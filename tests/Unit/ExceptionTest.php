<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Tests\Unit;

use {{NAMESPACE}}\Exception\ConfigException;
use {{NAMESPACE}}\Exception\HttpException;
use {{NAMESPACE}}\Exception\ValidationException;
use {{NAMESPACE}}\Tests\TestCase;

/**
 * Tests for Exception classes.
 */
class ExceptionTest extends TestCase
{
    public function test_config_exception(): void
    {
        $exception = new ConfigException('Invalid configuration');

        $this->assertEquals('Invalid configuration', $exception->getMessage());
        $this->assertInstanceOf(\Exception::class, $exception);
    }

    public function test_http_exception(): void
    {
        $exception = new HttpException('Server error', 500, '{"error": "internal"}');

        $this->assertEquals('Server error', $exception->getMessage());
        $this->assertEquals(500, $exception->getStatusCode());
        $this->assertEquals('{"error": "internal"}', $exception->getResponseBody());

        $decoded = $exception->getDecodedResponse();
        $this->assertIsArray($decoded);
        $this->assertEquals('internal', $decoded['error']);
    }

    public function test_http_exception_with_empty_body(): void
    {
        $exception = new HttpException('Not found', 404, '');

        $this->assertNull($exception->getDecodedResponse());
    }

    public function test_validation_exception(): void
    {
        $errors = [
            'email' => ['Email is required', 'Email must be valid'],
            'name' => ['Name is required'],
        ];

        $exception = new ValidationException('Validation failed', $errors);

        $this->assertEquals('Validation failed', $exception->getMessage());
        $this->assertEquals($errors, $exception->getErrors());
        $this->assertEquals(['Email is required', 'Email must be valid'], $exception->getFieldErrors('email'));
        $this->assertTrue($exception->hasFieldError('email'));
        $this->assertFalse($exception->hasFieldError('phone'));
        $this->assertEquals([], $exception->getFieldErrors('nonexistent'));
    }

    public function test_validation_exception_required_factory(): void
    {
        $exception = ValidationException::required('email');

        $this->assertStringContainsString('email', $exception->getMessage());
        $this->assertStringContainsString('required', $exception->getMessage());
        $this->assertTrue($exception->hasFieldError('email'));
    }

    public function test_validation_exception_invalid_factory(): void
    {
        $exception = ValidationException::invalid('amount', 'must be positive');

        $this->assertStringContainsString('amount', $exception->getMessage());
        $this->assertStringContainsString('invalid', $exception->getMessage());
        $this->assertEquals(['must be positive'], $exception->getFieldErrors('amount'));
    }
}
