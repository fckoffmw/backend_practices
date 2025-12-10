<?php

namespace App\Core;

/**
 * Простой DI контейнер для внедрения зависимостей
 */
class Container
{
    private array $bindings = [];
    private array $instances = [];

    /**
     * Регистрация сервиса в контейнере
     */
    public function bind(string $abstract, $concrete = null): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Получение сервиса из контейнера
     */
    public function get(string $abstract)
    {
        // Если уже создан экземпляр - возвращаем его (singleton)
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        // Если есть привязка
        if (isset($this->bindings[$abstract])) {
            $concrete = $this->bindings[$abstract];
            
            // Если это замыкание - выполняем его
            if (is_callable($concrete)) {
                $instance = $concrete();
            } else {
                $instance = $concrete;
            }
            
            // Сохраняем экземпляр
            $this->instances[$abstract] = $instance;
            return $instance;
        }

        // Пытаемся создать экземпляр класса автоматически
        if (class_exists($abstract)) {
            $instance = new $abstract();
            $this->instances[$abstract] = $instance;
            return $instance;
        }

        throw new \Exception("Unable to resolve {$abstract}");
    }

    /**
     * Проверка наличия сервиса
     */
    public function has(string $abstract): bool
    {
        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]);
    }
}