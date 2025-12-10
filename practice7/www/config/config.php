<?php

return [
    'database' => [
        'host' => $_ENV['DB_HOST'] ?? 'db',
        'name' => $_ENV['DB_NAME'] ?? 'appDB',
        'user' => $_ENV['DB_USER'] ?? 'user',
        'password' => $_ENV['DB_PASS'] ?? 'password',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    
    'redis' => [
        'host' => $_ENV['REDIS_HOST'] ?? 'redis',
        'port' => $_ENV['REDIS_PORT'] ?? 6379,
        'timeout' => 2.5,
    ],
    
    'session' => [
        'name' => 'PRACTICE7_SESSION',
        'lifetime' => 3600, // 1 hour
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
    ],
    
    'app' => [
        'name' => 'Practice 7 - Clean Architecture',
        'version' => '1.0.0',
        'debug' => true,
        'timezone' => 'Europe/Moscow',
    ],
    
    'paths' => [
        'charts' => __DIR__ . '/../public/charts/',
        'uploads' => __DIR__ . '/../public/uploads/',
    ],
];