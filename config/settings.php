<?php

declare(strict_types=1);

return [
    'app' => [
        'env' => $_ENV['APP_ENV'] ?? 'production',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
        'url' => $_ENV['APP_URL'] ?? 'http://localhost:8080',
    ],
    'logger' => [
        'name' => 'DonorDocs',
        'path' => $_ENV['LOG_PATH'] ?? __DIR__ . '/../storage/logs/app.log',
    ],
    'db' => [
        'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' => (int) ($_ENV['DB_PORT'] ?? 3306),
        'database' => $_ENV['DB_DATABASE'] ?? 'donordocs',
        'username' => $_ENV['DB_USERNAME'] ?? 'donordocs_user',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
    'timezone' => $_ENV['TIMEZONE'] ?? 'America/New_York',
];
