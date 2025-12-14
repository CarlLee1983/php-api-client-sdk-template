<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * Base test case for all tests.
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Clean up the test environment.
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
