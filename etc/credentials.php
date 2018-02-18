<?php
declare(strict_types=1);

include __DIR__ . '/secrets-helper.php';

return [
    'database' => [
        'host' => SecretHelper::read('database.host'),
        'db'   => SecretHelper::read('database.db'),
        'port' => SecretHelper::read('database.port'),
        'user' => SecretHelper::read('database.user'),
        'pass' => SecretHelper::read('database.pass'),
    ],
];
