<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Exception;

/**
 * Base exception class for SDK errors.
 *
 * All SDK exceptions should extend this class.
 */
class BaseException extends \Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message The exception message
     * @param int $code The exception code
     * @param \Throwable|null $previous The previous exception
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
