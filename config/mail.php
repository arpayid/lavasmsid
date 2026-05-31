<?php

return [

    'driver' => env('MAIL_MAILER', 'log'),

    'host' => env('MAIL_HOST', '127.0.0.1'),

    'port' => env('MAIL_PORT', 2525),

    'encryption' => env('MAIL_ENCRYPTION', 'tls'),

    'username' => env('MAIL_USERNAME'),

    'password' => env('MAIL_PASSWORD'),

    'sendmail' => '/usr/sbin/sendmail -bs',

    'markdown' => [
        'theme' => env('MAIL_MARKDOWN_THEME', 'default'),
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

    'log_channel' => env('MAIL_LOG_CHANNEL'),

];
