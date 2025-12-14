<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Exception;

/**
 * Exception thrown when configuration is invalid.
 */
class ConfigException extends BaseException
{
    /**
     * Create a new configuration exception.
     *
     * @param string $message The error message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
