<?php

namespace KeySMS\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;

use KeySMS\Client;
use KeySMS\KeySMSNotificationChannel;
use KeySMS\PendingSMS;

class KeySMSServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/keysms.php',
            'keysms'
        );

        $this->app->bind(Client::class, function ($app) {
            return new Client(
                Config::get('keysms.username'),
                Config::get('keysms.api_key'),
                Config::get('keysms.options', []),
            );
        });

        Notification::resolved(function (ChannelManager $service) {
            $service->extend('keysms', function ($app) {
                return new KeySMSNotificationChannel;
            });
        });
    }

    public function boot()
    {
        if (function_exists('config_path')) {
            $this->publishes([
                __DIR__ . '/../config/keysms.php' => config_path('keysms.php'),
            ]);
        }

        PendingSMS::$defaultSender = Config::get('keysms.options.default_sender', null);
    }
}
