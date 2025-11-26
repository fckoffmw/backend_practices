-- Создание базы данных
CREATE DATABASE IF NOT EXISTS appDB;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON appDB.* TO 'user'@'%';
FLUSH PRIVILEGES;

USE appDB;

-- Таблица пользователей (объединяет practice1 и practice3)
CREATE TABLE IF NOT EXISTS users (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  name VARCHAR(50) DEFAULT NULL,
  surname VARCHAR(50) DEFAULT NULL,
  is_admin BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Настройки пользователей для согласования контента
CREATE TABLE IF NOT EXISTS user_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL UNIQUE,
  theme ENUM('light', 'dark', 'colorblind') DEFAULT 'light',
  language ENUM('ru', 'en', 'de') DEFAULT 'ru',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(ID) ON DELETE CASCADE
);

-- Таблица заказов (из practice3/practice4)
CREATE TABLE IF NOT EXISTS orders (
  order_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product VARCHAR(100) NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(ID) ON DELETE CASCADE
);

-- Таблица для хранения PDF файлов
CREATE TABLE IF NOT EXISTS pdf_files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  filename VARCHAR(255) NOT NULL,
  original_name VARCHAR(255) NOT NULL,
  mime_type VARCHAR(100) DEFAULT 'application/pdf',
  file_size INT NOT NULL,
  file_data LONGBLOB NOT NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(ID) ON DELETE CASCADE
);

-- Тестовые данные: пользователи
INSERT INTO users (login, password, name, surname, is_admin) VALUES
  ('admin', 'password123', 'Admin', 'Admin', TRUE),
  ('user1', 'password123', 'Alex', 'Rover', FALSE),
  ('user2', 'password123', 'Bob', 'Marley', FALSE),
  ('user3', 'password123', 'Kate', 'Yandson', FALSE),
  ('user4', 'password123', 'Lilo', 'Black', FALSE);

-- Настройки по умолчанию для пользователей
INSERT INTO user_settings (user_id, theme, language) VALUES
  (1, 'dark', 'ru'),
  (2, 'light', 'en'),
  (3, 'colorblind', 'de'),
  (4, 'light', 'ru'),
  (5, 'dark', 'en');

-- Тестовые заказы
INSERT INTO orders (user_id, product, amount) VALUES
  (2, 'Laptop', 120000.00),
  (3, 'Headphones', 15000.00),
  (4, 'Mouse', 3000.00),
  (5, 'Keyboard', 8000.00);

