<?php

declare(strict_types=1);

namespace {{NAMESPACE}};

use {{NAMESPACE}}\Contracts\ConfigInterface;
use {{NAMESPACE}}\Exception\ConfigException;

/**
 * Base configuration class for SDK clients.
 *
 * Extend this class to create your SDK's configuration.
 *
 * @example
 * ```php
 * class MyConfig extends BaseConfig
 * {
 *     public function __construct(
 *         public readonly string $apiKey,
 *         string $baseUri = 'https://api.example.com',
 *         bool $sandbox = true
 *     ) {
 *         if (empty(trim($apiKey))) {
 *             throw new ConfigException('API key cannot be empty');
 *         }
 *         parent::__construct($baseUri, $sandbox);
 *     }
 * }
 * ```
 */
abstract class BaseConfig implements ConfigInterface
{
    protected string $baseUri;
    protected bool $sandbox;

    /**
     * Create a new configuration instance.
     *
     * @param string $baseUri The base URI for API requests
     * @param bool $sandbox Whether to use sandbox/test mode
     * @throws ConfigException If base URI is empty
     */
    public function __construct(
        string $baseUri,
        bool $sandbox = true
    ) {
        if (empty(trim($baseUri))) {
            throw new ConfigException('Base URI cannot be empty');
        }

        $this->baseUri = rtrim($baseUri, '/');
        $this->sandbox = $sandbox;
    }

    /**
     * Get the base URI for API requests.
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * Check if running in sandbox/test mode.
     */
    public function isSandbox(): bool
    {
        return $this->sandbox;
    }
}
