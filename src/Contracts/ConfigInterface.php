<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Contracts;

/**
 * Configuration interface for SDK clients.
 */
interface ConfigInterface
{
    /**
     * Get the base URI for API requests.
     */
    public function getBaseUri(): string;

    /**
     * Check if running in sandbox/test mode.
     */
    public function isSandbox(): bool;
}
