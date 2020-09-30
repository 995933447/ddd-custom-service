<?php
return [
    'default' => 'redis',

    'drivers' => [
        'redis' => [
            'prefix' => 'tplay_customer_admin_default_server:',
            'connection' => 'www'
        ]
    ]
];