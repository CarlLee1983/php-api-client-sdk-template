<?php

declare(strict_types=1);

namespace {{NAMESPACE}};

use {{NAMESPACE}}\Contracts\ConfigInterface;
use {{NAMESPACE}}\Exception\HttpException;
use {{NAMESPACE}}\Http\HttpClientInterface;
use {{NAMESPACE}}\Http\HttpResponse;
use {{NAMESPACE}}\Http\NativeHttpClient;

/**
 * Base API client with common HTTP functionality.
 *
 * Extend this class to create your SDK's client.
 *
 * @example
 * ```php
 * class MyClient extends BaseClient
 * {
 *     public function getUser(int $id): array
 *     {
 *         return $this->get("/users/{$id}");
 *     }
 *
 *     public function createUser(array $data): array
 *     {
 *         return $this->postJson('/users', $data);
 *     }
 * }
 * ```
 */
abstract class BaseClient
{
    protected HttpClientInterface $httpClient;

    /**
     * Create a new client instance.
     *
     * @param ConfigInterface $config The SDK configuration
     * @param HttpClientInterface|null $httpClient Optional HTTP client for testing
     */
    public function __construct(
        protected ConfigInterface $config,
        ?HttpClientInterface $httpClient = null
    ) {
        $this->httpClient = $httpClient ?? new NativeHttpClient();
    }

    /**
     * Get the configuration instance.
     */
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * Send a POST request with JSON body.
     *
     * @param string $path API endpoint path
     * @param array<string, mixed> $payload Request payload
     * @param array<string, string> $headers Additional headers
     * @return array<string, mixed> Decoded response
     * @throws HttpException
     */
    protected function postJson(string $path, array $payload, array $headers = []): array
    {
        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $response = $this->httpClient->request(
            'POST',
            $this->config->getBaseUri() . $path,
            array_merge($defaultHeaders, $headers),
            $payload
        );

        return $this->handleResponse($response);
    }

    /**
     * Send a GET request.
     *
     * @param string $path API endpoint path
     * @param array<string, string> $query Query parameters
     * @param array<string, string> $headers Additional headers
     * @return array<string, mixed> Decoded response
     * @throws HttpException
     */
    protected function get(string $path, array $query = [], array $headers = []): array
    {
        $defaultHeaders = [
            'Accept' => 'application/json',
        ];

        $url = $this->config->getBaseUri() . $path;
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $response = $this->httpClient->request(
            'GET',
            $url,
            array_merge($defaultHeaders, $headers),
            []
        );

        return $this->handleResponse($response);
    }

    /**
     * Send a PUT request with JSON body.
     *
     * @param string $path API endpoint path
     * @param array<string, mixed> $payload Request payload
     * @param array<string, string> $headers Additional headers
     * @return array<string, mixed> Decoded response
     * @throws HttpException
     */
    protected function putJson(string $path, array $payload, array $headers = []): array
    {
        $defaultHeaders = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        $response = $this->httpClient->request(
            'PUT',
            $this->config->getBaseUri() . $path,
            array_merge($defaultHeaders, $headers),
            $payload
        );

        return $this->handleResponse($response);
    }

    /**
     * Send a DELETE request.
     *
     * @param string $path API endpoint path
     * @param array<string, string> $headers Additional headers
     * @return array<string, mixed> Decoded response
     * @throws HttpException
     */
    protected function delete(string $path, array $headers = []): array
    {
        $defaultHeaders = [
            'Accept' => 'application/json',
        ];

        $response = $this->httpClient->request(
            'DELETE',
            $this->config->getBaseUri() . $path,
            array_merge($defaultHeaders, $headers),
            []
        );

        return $this->handleResponse($response);
    }

    /**
     * Handle HTTP response and convert to array.
     *
     * Override this method to customize response handling.
     *
     * @param HttpResponse $response The HTTP response
     * @return array<string, mixed> Decoded response data
     * @throws HttpException If the response indicates an error
     */
    protected function handleResponse(HttpResponse $response): array
    {
        if ($response->statusCode >= 500) {
            throw new HttpException(
                sprintf('Server error (HTTP %d)', $response->statusCode),
                $response->statusCode,
                $response->body
            );
        }

        if ($response->statusCode >= 400) {
            throw new HttpException(
                sprintf('Client error (HTTP %d)', $response->statusCode),
                $response->statusCode,
                $response->body
            );
        }

        if (empty($response->body)) {
            return [];
        }

        $decoded = json_decode($response->body, true);

        if (!is_array($decoded)) {
            throw new HttpException(
                'Unable to decode response as JSON',
                $response->statusCode,
                $response->body
            );
        }

        return $decoded;
    }
}
