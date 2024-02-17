<?php
return [
    'url' => env('KULESHOV_AUTH_URL', 'https://example.com'),
    'security_key' => env('KULESHOV_AUTH_SECURITY_KEY', 'your-security-key'),
    'policies' => [
        'chat_room' => \Maksatsaparbekov\KuleshovAuth\Policies\ChatPolicy::class,
    ],
];