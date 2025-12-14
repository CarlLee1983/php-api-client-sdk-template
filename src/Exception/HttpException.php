<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Exception;

/**
 * Exception thrown when an HTTP request fails.
 */
class HttpException extends BaseException
{
    /**
     * Create a new HTTP exception.
     *
     * @param string $message The error message
     * @param int $statusCode The HTTP status code
     * @param string $responseBody The raw response body
     */
    public function __construct(
        string $message,
        public readonly int $statusCode = 0,
        public readonly string $responseBody = ''
    ) {
        parent::__construct($message, $statusCode);
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the raw response body.
     */
    public function getResponseBody(): string
    {
        return $this->responseBody;
    }

    /**
     * Try to decode the response body as JSON.
     *
     * @return array<string, mixed>|null
     */
    public function getDecodedResponse(): ?array
    {
        if (empty($this->responseBody)) {
            return null;
        }

        $decoded = json_decode($this->responseBody, true);

        return is_array($decoded) ? $decoded : null;
    }
}
