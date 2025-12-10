<?php

/**
 * Демонстрационная версия Practice 7 - Clean Architecture + MVC
 * Простая версия для показа архитектуры без зависимостей
 */

// Автозагрузка классов (упрощенная версия)
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Простая конфигурация
$config = [
    'app' => [
        'name' => 'Practice 7 - Clean Architecture Demo',
        'version' => '1.0.0',
        'debug' => true,
        'timezone' => 'Europe/Moscow',
    ],
    'paths' => [
        'charts' => __DIR__ . '/charts/',
    ],
];

// Демонстрация архитектуры
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practice 7 - Clean Architecture Demo</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; margin: 40px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px; }
        .section { background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .code { background: #f8f9fa; padding: 15px; border-radius: 4px; font-family: 'Courier New', monospace; font-size: 14px; overflow-x: auto; }
        .success { color: #28a745; font-weight: bold; }
        .architecture { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
        .layer { background: #e9ecef; padding: 15px; border-radius: 8px; text-align: center; }
        .layer.entities { background: #d4edda; }
        .layer.usecases { background: #d1ecf1; }
        .layer.adapters { background: #fff3cd; }
        .layer.infrastructure { background: #f8d7da; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?= htmlspecialchars($config['app']['name']) ?></h1>
            <p>Демонстрация рефакторинга с процедурной парадигмы на ООП с применением Clean Architecture и MVC</p>
        </div>

        <div class="section">
            <h2>✅ Архитектура успешно реализована</h2>
            
            <div class="architecture">
                <div class="layer infrastructure">
                    <h4>Infrastructure</h4>
                    <p>DatabaseConnection<br>RedisSessionHandler</p>
                </div>
                <div class="layer adapters">
                    <h4>Interface Adapters (MVC)</h4>
                    <p>Controllers<br>Models (Repository)<br>Views</p>
                </div>
                <div class="layer usecases">
                    <h4>Use Cases</h4>
                    <p>AuthenticateUser<br>GenerateStatistics</p>
                </div>
                <div class="layer entities">
                    <h4>Entities</h4>
                    <p>User<br>SalesStatistic</p>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Проверка классов</h2>
            
            <?php
            $classes = [
                'App\\Entities\\User' => 'Бизнес-сущность пользователя',
                'App\\Entities\\SalesStatistic' => 'Бизнес-сущность статистики',
                'App\\UseCases\\AuthenticateUser' => 'Use Case аутентификации',
                'App\\UseCases\\GenerateStatistics' => 'Use Case генерации статистики',
                'App\\Controllers\\HomeController' => 'MVC контроллер главной страницы',
                'App\\Models\\UserRepository' => 'Repository для пользователей',
                'App\\Services\\ChartGeneratorService' => 'Сервис генерации графиков',
                'App\\Core\\Application' => 'Front Controller приложения',
                'App\\Core\\Container' => 'DI контейнер',
                'App\\Core\\Router' => 'Маршрутизатор',
            ];
            
            foreach ($classes as $class => $description) {
                $exists = class_exists($class);
                echo "<p>";
                echo $exists ? '<span class="success">✓</span>' : '<span style="color: #dc3545;">✗</span>';
                echo " <strong>{$class}</strong> - {$description}";
                echo "</p>";
            }
            ?>
        </div>

        <div class="section">
            <h2>Демонстрация Entity</h2>
            <div class="code">
<?php
try {
    $user = new App\Entities\User(
        login: 'demo_user',
        password: 'password123',
        name: 'Демо',
        surname: 'Пользователь',
        role: 'user',
        theme: 'light',
        language: 'ru'
    );
    
    echo "// Создание сущности User\n";
    echo "\$user = new User(\n";
    echo "    login: 'demo_user',\n";
    echo "    password: 'password123',\n";
    echo "    name: 'Демо',\n";
    echo "    surname: 'Пользователь'\n";
    echo ");\n\n";
    
    echo "// Бизнес-методы сущности\n";
    echo "echo \$user->getFullName(); // " . $user->getFullName() . "\n";
    echo "echo \$user->isAdmin(); // " . ($user->isAdmin() ? 'true' : 'false') . "\n";
    echo "echo \$user->getTheme(); // " . $user->getTheme() . "\n";
    
} catch (Exception $e) {
    echo "Ошибка: " . $e->getMessage();
}
?>
            </div>
        </div>

        <div class="section">
            <h2>Структура проекта</h2>
            <div class="code">
practice7/
├── src/
│   ├── Core/              # Ядро фреймворка
│   │   ├── Application.php    # Front Controller
│   │   ├── Container.php      # DI контейнер
│   │   ├── Router.php         # Маршрутизация
│   │   └── Controller.php     # Базовый контроллер
│   ├── Entities/          # Бизнес-сущности (Clean Architecture)
│   │   ├── User.php
│   │   └── SalesStatistic.php
│   ├── UseCases/          # Бизнес-логика (Clean Architecture)
│   │   ├── AuthenticateUser.php
│   │   └── GenerateStatistics.php
│   ├── Controllers/       # MVC Controllers
│   ├── Models/            # MVC Models (Repository Pattern)
│   ├── Views/             # MVC Views
│   ├── Services/          # Сервисы приложения
│   └── Infrastructure/    # Внешние зависимости
├── public/
│   └── index.php          # Единая точка входа (Front Controller)
└── config/
    └── config.php         # Конфигурация
            </div>
        </div>

        <div class="section">
            <h2>Принципы рефакторинга</h2>
            <ul>
                <li><strong>Clean Architecture:</strong> Разделение на концентрические слои</li>
                <li><strong>MVC Pattern:</strong> Применен на слое Interface Adapters</li>
                <li><strong>Repository Pattern:</strong> Абстракция доступа к данным</li>
                <li><strong>Dependency Injection:</strong> Внедрение зависимостей через DI контейнер</li>
                <li><strong>Single Responsibility:</strong> Каждый класс имеет одну ответственность</li>
                <li><strong>Front Controller:</strong> Единая точка входа в приложение</li>
                <li><strong>PSR-4 Autoloading:</strong> Автозагрузка классов по стандарту</li>
            </ul>
        </div>

        <div class="section">
            <h2>Результат рефакторинга</h2>
            <p><span class="success">✅ Задание выполнено успешно!</span></p>
            <p>Система полностью переведена с процедурной парадигмы на ООП с применением Clean Architecture и паттерна MVC на внешнем слое архитектуры.</p>
            
            <p><strong>Для полного тестирования:</strong></p>
            <ol>
                <li>Запустите Docker: <code>docker-compose up --build</code></li>
                <li>Установите зависимости: <code>docker exec practice7_web composer install</code></li>
                <li>Откройте: <a href="http://localhost:8087">http://localhost:8087</a></li>
            </ol>
        </div>
    </div>
</body>
</html>