<?php
return [
    'url' => env('KULESHOV_AUTH_URL', 'https://example.com'),
    'security_key' => env('KULESHOV_AUTH_SECURITY_KEY', 'your-security-key'),
    'gates' => [
        'view_all_chats' => function ($user) {
                return true;
//              Пользовательская логика, определяющая, может ли пользователь просматривать все чаты
//              return $user->hasRole('admin');
        },
    ],
];