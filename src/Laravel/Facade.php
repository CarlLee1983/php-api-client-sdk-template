<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Laravel;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * Laravel Facade for the SDK client.
 *
 * ## Usage
 *
 * Add an alias in your `config/app.php`:
 *
 * ```php
 * 'aliases' => [
 *     'SDK' => {{NAMESPACE}}\Laravel\Facade::class,
 * ]
 * ```
 *
 * Or use it directly:
 *
 * ```php
 * use {{NAMESPACE}}\Laravel\Facade as SDK;
 *
 * SDK::someMethod();
 * ```
 *
 * @method static mixed yourMethod()
 *
 * @see \{{NAMESPACE}}\Client
 */
class Facade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        // Return the class name that this facade resolves to
        // Example: return Client::class;
        return 'sdk';
    }
}
