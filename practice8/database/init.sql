-- Инициализация базы данных для Practice 8
-- Миграция структуры из Practice 7 в Laravel

-- Создание базы данных (если не существует)
CREATE DATABASE IF NOT EXISTS laravel_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE laravel_app;

-- Пользователи будут созданы через Laravel миграции
-- Статистика продаж будет создана через Laravel миграции

-- Вставка начальных данных (будет выполнено через сидеры)
-- INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
-- ('Администратор', 'admin@practice8.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
-- ('Пользователь', 'user@practice8.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', NOW(), NOW());

-- Настройки для оптимизации
SET GLOBAL innodb_buffer_pool_size = 128M;
SET GLOBAL max_connections = 200;