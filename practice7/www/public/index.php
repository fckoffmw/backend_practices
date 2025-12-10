<?php

/**
 * Front Controller - единая точка входа в приложение
 * Practice 7 - Clean Architecture + MVC Refactoring
 */

// Автозагрузка классов
require_once __DIR__ . '/../vendor/autoload.php';

// Загрузка конфигурации
$config = require_once __DIR__ . '/../config/config.php';

// Создание и запуск приложения
use App\Core\Application;

try {
    $app = new Application($config);
    $app->run();
} catch (Exception $e) {
    // В продакшене здесь должно быть логирование
    if ($config['app']['debug']) {
        echo "<h1>Application Error</h1>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    } else {
        echo "500 - Internal Server Error";
    }
}