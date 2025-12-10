<?php

return [
    'database' => [
        'host' => 'localhost',
        'name' => 'test_db',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    ],
    
    'redis' => [
        'host' => 'localhost',
        'port' => 6379,
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
        'name' => 'Practice 7 - Clean Architecture (Test Mode)',
        'version' => '1.0.0',
        'debug' => true,
        'timezone' => 'Europe/Moscow',
    ],
    
    'paths' => [
        'charts' => __DIR__ . '/../public/charts/',
        'uploads' => __DIR__ . '/../public/uploads/',
    ],
];