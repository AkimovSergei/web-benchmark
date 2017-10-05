<?php

return [
    'mail' => [
        'driver' => 'smtp',
        'smtp' => array(
            'host' => 'smtp.mailtrap.io',
            'port' => 2525,
            'username' => '52da6094be66a0',
            'password' => 'a42844ca469f5e',
            'timeout' => 5
        ),
        'from' => [
            'email' => 'report@web.benchmark',
            'name' => 'Web Benchmark',
        ],
        'to' => [
            'sergii.akimov@gmail.com'
        ],
    ],
    'sms' => [
        'api_key' => 'API_KEY_EXAMPLE',
        'from' => 'Web Benchmark',
    ]
];
