<?php

namespace App\Core;

use App\Core\Router;
use App\Core\Container;
use App\Infrastructure\Database\DatabaseConnection;
use App\Infrastructure\Session\RedisSessionHandler;

/**
 * Главный класс приложения - Front Controller
 */
class Application
{
    private Container $container;
    private Router $router;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->container = new Container();
        $this->router = new Router();
        
        $this->bootstrap();
    }

    /**
     * Инициализация приложения
     */
    private function bootstrap(): void
    {
        // Настройка временной зоны
        date_default_timezone_set($this->config['app']['timezone']);
        
        // Регистрация сервисов в контейнере
        $this->registerServices();
        
        // Настройка сессий
        $this->setupSession();
        
        // Регистрация маршрутов
        $this->registerRoutes();
    }

    /**
     * Регистрация сервисов в DI контейнере
     */
    private function registerServices(): void
    {
        // Конфигурация
        $this->container->bind('config', $this->config);
        
        // База данных
        $this->container->bind(DatabaseConnection::class, function() {
            return new DatabaseConnection($this->config['database']);
        });
        
        // Redis сессии
        $this->container->bind(RedisSessionHandler::class, function() {
            return new RedisSessionHandler($this->config['redis']);
        });
    }

    /**
     * Настройка сессий с Redis
     */
    private function setupSession(): void
    {
        try {
            $sessionHandler = $this->container->get(RedisSessionHandler::class);
            session_set_save_handler($sessionHandler, true);
        } catch (\Exception $e) {
            // Fallback to default session handler if Redis is not available
            error_log("Redis session handler failed, using default: " . $e->getMessage());
        }
        
        session_name($this->config['session']['name']);
        session_start();
    }

    /**
     * Регистрация маршрутов
     */
    private function registerRoutes(): void
    {
        // Главная страница
        $this->router->get('/', 'HomeController@index');
        
        // Аутентификация
        $this->router->get('/login', 'AuthController@showLogin');
        $this->router->post('/login', 'AuthController@login');
        $this->router->post('/logout', 'AuthController@logout');
        
        // Пользователи
        $this->router->get('/users', 'UserController@index');
        $this->router->get('/users/{id}', 'UserController@show');
        
        // Настройки
        $this->router->get('/settings', 'SettingsController@index');
        $this->router->post('/settings', 'SettingsController@update');
        
        // Статистика и графики
        $this->router->get('/statistics', 'StatisticsController@index');
        $this->router->post('/statistics/generate-fixtures', 'StatisticsController@generateFixtures');
        
        // Сервисы
        $this->router->get('/services', 'ServiceController@index');
        $this->router->get('/services/drawer', 'DrawerController@index');
        $this->router->post('/services/drawer/generate', 'DrawerController@generate');
        $this->router->get('/services/sort', 'SortController@index');
        $this->router->post('/services/sort', 'SortController@index');
        $this->router->post('/services/sort/sort', 'SortController@sort');
        $this->router->get('/services/admin', 'AdminController@index');
        $this->router->post('/services/admin/execute', 'AdminController@execute');
        
        // API
        $this->router->get('/api/users', 'Api\\UserController@index');
        $this->router->post('/api/users', 'Api\\UserController@store');
    }

    /**
     * Запуск приложения
     */
    public function run(): void
    {
        try {
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];
            
            $route = $this->router->resolve($method, $uri);
            
            if ($route) {
                $this->executeController($route['controller'], $route['action'], $route['params']);
            } else {
                $this->handleNotFound();
            }
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }

    /**
     * Выполнение контроллера
     */
    private function executeController(string $controller, string $action, array $params): void
    {
        $controllerClass = "App\\Controllers\\{$controller}";
        
        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }
        
        $controllerInstance = new $controllerClass($this->container);
        
        if (!method_exists($controllerInstance, $action)) {
            throw new \Exception("Action {$action} not found in {$controllerClass}");
        }
        
        call_user_func_array([$controllerInstance, $action], $params);
    }

    /**
     * Обработка 404 ошибки
     */
    private function handleNotFound(): void
    {
        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Обработка ошибок
     */
    private function handleError(\Exception $e): void
    {
        http_response_code(500);
        
        if ($this->config['app']['debug']) {
            echo "<h1>Error</h1>";
            echo "<p>{$e->getMessage()}</p>";
            echo "<pre>{$e->getTraceAsString()}</pre>";
        } else {
            echo "500 - Internal Server Error";
        }
    }
}