<?php

namespace App\Core;

/**
 * Простой роутер для обработки HTTP маршрутов
 */
class Router
{
    private array $routes = [];

    /**
     * Регистрация GET маршрута
     */
    public function get(string $uri, string $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Регистрация POST маршрута
     */
    public function post(string $uri, string $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Добавление маршрута
     */
    private function addRoute(string $method, string $uri, string $action): void
    {
        [$controller, $actionMethod] = explode('@', $action);
        
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $actionMethod,
        ];
    }

    /**
     * Поиск маршрута
     */
    public function resolve(string $method, string $uri): ?array
    {
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $this->convertToRegex($route['uri']);
            
            if (preg_match($pattern, $uri, $matches)) {
                // Извлекаем параметры из URL
                $params = array_slice($matches, 1);
                
                return [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => $params,
                ];
            }
        }

        return null;
    }

    /**
     * Преобразование URI в регулярное выражение
     */
    private function convertToRegex(string $uri): string
    {
        // Заменяем {param} на ([^/]+)
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $uri);
        
        // Экранируем слеши и добавляем якоря
        $pattern = '#^' . str_replace('/', '\/', $pattern) . '$#';
        
        return $pattern;
    }
}