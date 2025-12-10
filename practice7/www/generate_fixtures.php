#!/usr/bin/env php
<?php

/**
 * Консольная команда для генерации фикстур
 * Использование: php generate_fixtures.php [количество]
 */

require_once __DIR__ . '/vendor/autoload.php';

// Загрузка конфигурации
$config = require_once __DIR__ . '/config/config.php';

use App\Infrastructure\Database\DatabaseConnection;
use App\Models\SalesStatisticRepository;
use App\Services\FixtureGeneratorService;

try {
    echo "Генерация фикстур для Practice 7...\n";
    
    // Подключение к БД
    $db = new DatabaseConnection($config['database']);
    $repository = new SalesStatisticRepository($db);
    $generator = new FixtureGeneratorService($repository);
    
    // Количество записей
    $count = isset($argv[1]) ? (int) $argv[1] : 50;
    
    if ($count < 1 || $count > 1000) {
        echo "Ошибка: количество записей должно быть от 1 до 1000\n";
        exit(1);
    }
    
    echo "Генерация {$count} записей...\n";
    
    $generated = $generator->generateFixtures($count);
    
    echo "Успешно сгенерировано {$generated} записей!\n";
    echo "Теперь можно открыть /statistics для просмотра графиков.\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
    exit(1);
}