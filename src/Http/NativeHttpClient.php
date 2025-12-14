<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Http;

use {{NAMESPACE}}\Exception\HttpException;

/**
 * Native HTTP client using file_get_contents.
 *
 * This is the default HTTP client implementation that requires no external dependencies.
 * For production use with high traffic, consider using Guzzle or other HTTP libraries.
 */
final class NativeHttpClient implements HttpClientInterface
{
    /**
     * @var int Default timeout in seconds
     */
    private int $timeout;

    /**
     * Create a new native HTTP client.
     *
     * @param int $timeout Request timeout in seconds
     */
    public function __construct(int $timeout = 30)
    {
        $this->timeout = $timeout;
    }

    /**
     * {@inheritdoc}
     */
    public function request(
        string $method,
        string $url,
        array $headers = [],
        array $body = []
    ): HttpResponse {
        $method = strtoupper($method);

        $httpHeaders = [];
        foreach ($headers as $name => $value) {
            $httpHeaders[] = "{$name}: {$value}";
        }

        $options = [
            'http' => [
                'method' => $method,
                'header' => implode("\r\n", $httpHeaders),
                'timeout' => $this->timeout,
                'ignore_errors' => true, // Don't throw on 4xx/5xx
            ],
        ];

        if (!empty($body) && in_array($method, ['POST', 'PUT', 'PATCH'], true)) {
            $options['http']['content'] = json_encode($body);
        }

        $context = stream_context_create($options);

        $responseBody = @file_get_contents($url, false, $context);

        if ($responseBody === false) {
            throw new HttpException(
                'Failed to connect to server',
                0,
                ''
            );
        }

        $statusCode = $this->parseStatusCode($http_response_header ?? []);
        $responseHeaders = $this->parseHeaders($http_response_header ?? []);

        return new HttpResponse($statusCode, $responseBody, $responseHeaders);
    }

    /**
     * Parse HTTP status code from response headers.
     *
     * @param array<int, string> $headers Raw response headers
     */
    private function parseStatusCode(array $headers): int
    {
        if (empty($headers)) {
            return 0;
        }

        // First header contains status line, e.g., "HTTP/1.1 200 OK"
        if (preg_match('/HTTP\/\d\.\d\s+(\d+)/', $headers[0], $matches)) {
            return (int) $matches[1];
        }

        return 0;
    }

    /**
     * Parse response headers into associative array.
     *
     * @param array<int, string> $rawHeaders Raw response headers
     * @return array<string, string>
     */
    private function parseHeaders(array $rawHeaders): array
    {
        $headers = [];

        foreach ($rawHeaders as $header) {
            // Skip status line
            if (str_starts_with($header, 'HTTP/')) {
                continue;
            }

            $parts = explode(':', $header, 2);
            if (count($parts) === 2) {
                $headers[trim($parts[0])] = trim($parts[1]);
            }
        }

        return $headers;
    }
}
