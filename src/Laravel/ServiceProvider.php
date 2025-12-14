<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Laravel;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Laravel Service Provider.
 *
 * This service provider registers the SDK client with Laravel's service container.
 *
 * ## Configuration
 *
 * After installing the package, publish the configuration file:
 *
 * ```bash
 * php artisan vendor:publish --provider="{{NAMESPACE}}\Laravel\ServiceProvider"
 * ```
 *
 * Then configure your credentials in `config/sdk.php` or via environment variables.
 *
 * ## Usage
 *
 * Once configured, you can inject the client or use the facade:
 *
 * ```php
 * // Dependency Injection
 * public function __construct(private Client $client) {}
 *
 * // Facade (if using Facade class)
 * SDK::someMethod();
 * ```
 */
class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/sdk.php',
            'sdk'
        );

        // Register your client as a singleton
        // Example:
        // $this->app->singleton(Client::class, function ($app) {
        //     return new Client(new Config(
        //         apiKey: config('sdk.api_key'),
        //         baseUri: config('sdk.base_uri'),
        //         sandbox: config('sdk.sandbox', true),
        //     ));
        // });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/sdk.php' => config_path('sdk.php'),
            ], 'config');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            // Client::class,
        ];
    }
}
