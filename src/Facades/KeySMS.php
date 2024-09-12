<?php

namespace KeySMS\Facades;

use KeySMS\Client;

use Illuminate\Support\Facades\Facade;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\BindingResolutionException;
use KeySMS\Providers\KeySMSServiceProvider;
use TypeError;
use RuntimeException;

/**
 * @method static array get(string $endpoint, array $query, Closure|null $validate)
 * @method static array post(string $endpoint, array $payload, Closure|null $validate)
 * @method static array put(string $endpoint, array $payload, Closure|null $validate)
 * @method static array delete(string $endpoint, array $payload, Closure|null $validate)
 */
class KeySMS extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Client::class;
    }

    /**
     * This method is used to initialize the KeySMS standalone without Laravel
     * @param string $username 
     * @param string $apiKey 
     * @param array $options 
     * @return void 
     * @throws BindingResolutionException 
     * @throws TypeError 
     * @throws RuntimeException 
     */
    public static function init(string $username, string $apiKey, array $options = []): void
    {
        $app = new Container;

        $app->instance('app', $app);
        $app->singleton('config', fn() => new Repository([
            'keysms' => [
                'username' => $username,
                'api_key' => $apiKey,
                'options' => array_merge([
                    'host' => 'https://app.keysms.no',
                ], $options)
            ]
        ]));

        Facade::setFacadeApplication($app);

        $provider = new KeySMSServiceProvider($app);

        $provider->register();
        $provider->boot();
    }
}
