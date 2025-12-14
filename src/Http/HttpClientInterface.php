<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Http;

/**
 * HTTP client interface for making API requests.
 *
 * Implement this interface to provide a custom HTTP client,
 * for example when using Guzzle or for testing with mocks.
 */
interface HttpClientInterface
{
    /**
     * Send an HTTP request.
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE, etc.)
     * @param string $url Full URL to request
     * @param array<string, string> $headers Request headers
     * @param array<string, mixed> $body Request body (will be JSON encoded for non-GET requests)
     * @return HttpResponse The HTTP response
     */
    public function request(
        string $method,
        string $url,
        array $headers = [],
        array $body = []
    ): HttpResponse;
}
