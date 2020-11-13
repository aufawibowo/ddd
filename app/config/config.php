<?php

use Phalcon\Config;

return new Config([
    'app' => [
        'env'       => getenv('APP_ENV'),
        'timezone'  => getenv('APP_TIMEZONE'),
        'baseUrl'   => getenv('APP_URL'),
        'time'      => microtime(true),
        'key'       => getenv('APP_KEY'),
        'storage_path' => BASE_PATH . '/storage',
        'photo_root_path' => getenv('PHOTO_ROOT_PATH')
    ],
    'database' => [
        'adapter'   => getenv('DB_ADAPTER'),
        'host'      => getenv('DB_HOST'),
        'port'      => getenv('DB_PORT'),
        'username'  => getenv('DB_USERNAME'),
        'password'  => getenv('DB_PASSWORD'),
        'name'      => getenv('DB_DATABASE')
    ],
    'redis' => [
        'host' => getenv('REDIS_HOST'),
        'port' => getenv('REDIS_PORT')
    ],
    'services' => [
        'sms' => [
            'endpoint' => getenv('SMS_GATEWAY_ENDPOINT'),
            'username' => getenv('SMS_GATEWAY_USERNAME'),
            'password' => getenv('SMS_GATEWAY_PASSWORD')
        ],
        'apitu' => [
            'baseUrl' => getenv('APITU_BASE_URL')
        ]
    ],
    'mail' => [
        'host'          => getenv('MAIL_HOST'),
        'port'          => getenv('MAIL_PORT'),
        'username'      => getenv('MAIL_USERNAME'),
        'password'      => getenv('MAIL_PASSWORD'),
        'encryption'    => getenv('MAIL_ENCRYPTION'),
    ]
]);
