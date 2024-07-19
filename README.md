# KeySMS for PHP / Laravel

This package implements a fluent interface for working with the KeySMS API.

## Installation

```bash
composer require apilitylabs/keysms
```

## Laravel

Configure the following variables in your .env file / environment

```ini
KEYSMS_USERNAME=<your username>
KEYSMS_API_KEY=<your api key>
```

If you have a verified sender alias, and would like to configure this globally as the default sender:

```ini
KEYSMS_DEFAULT_SENDER="Acme Inc"
```

### Usage

```php
<?php

use KeySMS\SMS;

SMS::to('+4781549300')
    ->from('Acme Inc')
    ->message('Hello, World!');
```

The message will automatically invoke the `send()` method when the app is terminated, just like job dispatching works in Laravel.
You may also explictly invoke this method to send the message immediately.

You may pass multiple receivers:

```php
SMS::to(['+4781549300', '+4799999999']);
```

We recommend that you implement the `KeySMS\Contracts\PhoneNumber` interface to your user models.
You can then simply just pass the user model directly to this method:

```php
$user = Auth::user();

SMS::to($user)->message('Hello!');
```

### Notifications

This package provides a Laravel notification channel that you can use.

In your notification simply enable the channel, and implment the `toSMS($notifiable)` method:

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

use KeySMS\SMS;

class HelloWorld extends Notification implements ShouldQueue
{
    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['keysms'];
    }

    public function toSMS($notifiable)
    {
        return 'Hello, World!';
    }
}
```

You can then simply dispatch the notification as usual:

```php
<?php

use App\Notifications\HelloWorld;

$user = Auth::user();

$user->notify(new HelloWorld);
```

## PHP

This library can be used standalone without Laravel.

### Usage

```php
<?php

use KeySMS\Facades\KeySMS;
use KeySMS\SMS;

// Called once to initialise the KeySMS client
KeySMS::init(<your username>, <your api key>);

SMS::to('+4781549300')
    ->message('Hello, World!')
    ->send();
```

---

Copyright &copy; ApilityLabs 2024