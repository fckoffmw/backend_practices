<?php

namespace App\Core;

/**
 * Базовый контроллер для всех MVC контроллеров
 */
abstract class Controller
{
    protected Container $container;
    protected array $config;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->config = $container->get('config');
    }

    /**
     * Рендеринг представления
     */
    protected function render(string $view, array $data = []): void
    {
        $viewPath = __DIR__ . "/../Views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found");
        }

        // Извлекаем переменные в локальную область видимости
        extract($data);
        
        // Подключаем заголовок
        include __DIR__ . '/../Views/layouts/header.php';
        
        // Подключаем основной контент
        include $viewPath;
        
        // Подключаем футер
        include __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Возврат JSON ответа
     */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Редирект
     */
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    /**
     * Проверка аутентификации
     */
    protected function requireAuth(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    /**
     * Получение текущего пользователя
     */
    protected function getCurrentUser(): ?array
    {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        // Здесь можно получить полные данные пользователя из БД
        return [
            'id' => $_SESSION['user_id'],
            'login' => $_SESSION['user_login'] ?? '',
            'name' => $_SESSION['user_name'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'user',
        ];
    }
}