<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Http;

/**
 * HTTP response wrapper.
 */
final class HttpResponse
{
    /**
     * Create a new HTTP response.
     *
     * @param int $statusCode HTTP status code
     * @param string $body Response body
     * @param array<string, string> $headers Response headers
     */
    public function __construct(
        public readonly int $statusCode,
        public readonly string $body,
        public readonly array $headers = []
    ) {
    }

    /**
     * Check if the response was successful (2xx status code).
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Check if the response was a client error (4xx status code).
     */
    public function isClientError(): bool
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * Check if the response was a server error (5xx status code).
     */
    public function isServerError(): bool
    {
        return $this->statusCode >= 500;
    }

    /**
     * Decode the response body as JSON.
     *
     * @return array<string, mixed>|null
     */
    public function json(): ?array
    {
        if (empty($this->body)) {
            return null;
        }

        $decoded = json_decode($this->body, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * Get a specific header value.
     *
     * @param string $name Header name (case-insensitive)
     * @return string|null Header value or null if not found
     */
    public function getHeader(string $name): ?string
    {
        $name = strtolower($name);

        foreach ($this->headers as $key => $value) {
            if (strtolower($key) === $name) {
                return $value;
            }
        }

        return null;
    }
}
