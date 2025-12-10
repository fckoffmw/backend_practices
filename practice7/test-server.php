#!/usr/bin/env php
<?php

/**
 * Простой тестовый сервер для проверки приложения без Docker
 * Запуск: php test-server.php
 * Открыть: http://localhost:8000
 */

// Проверяем наличие composer
if (!file_exists(__DIR__ . '/www/vendor/autoload.php')) {
    echo "Установка зависимостей Composer...\n";
    chdir(__DIR__ . '/www');
    system('composer install');
}

// Запускаем встроенный сервер PHP
echo "Запуск тестового сервера на http://localhost:8000\n";
echo "Нажмите Ctrl+C для остановки\n\n";

chdir(__DIR__ . '/www/public');
system('php -S localhost:8000');