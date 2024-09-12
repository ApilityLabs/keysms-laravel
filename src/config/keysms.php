<?php

return [
    'keysms' => [
        'username' => env('KEYSMS_USERNAME'),
        'api_key' => env('KEYSMS_API_KEY'),
        'options' => [
            'host' => env('KEYSMS_HOST', 'https://app.keysms.no'),
            'default_sender' => env('KEYSMS_DEFAULT_SENDER', null),
        ]
    ]
];
