# Practice 7 — Рефакторинг на Clean Architecture + MVC

## Описание

Рефакторинг информационной системы из практик 1-6 с переходом от процедурной парадигмы к ООП с применением Clean Architecture и паттерна MVC.

## Архитектура

### Clean Architecture (Чистая архитектура)

Система организована в виде концентрических слоев:

1. **Entities (Сущности)** - бизнес-объекты
2. **Use Cases (Варианты использования)** - бизнес-логика
3. **Interface Adapters (Адаптеры интерфейсов)** - MVC слой
4. **Frameworks & Drivers (Фреймворки и драйверы)** - внешние зависимости

### MVC Pattern (на слое Interface Adapters)

- **Model** - работа с данными через Repository pattern
- **View** - шаблоны представления
- **Controller** - обработка HTTP-запросов

## Структура проекта

```
practice7/
├── docker-compose.yml
├── db/
│   └── init.sql
├── php/
│   └── Dockerfile
├── nginx/
│   └── nginx.conf
└── www/
    ├── public/                 # Единая точка входа
    │   ├── index.php          # Front Controller
    │   ├── assets/            # Статические файлы
    │   └── charts/            # Генерируемые графики
    ├── src/                   # Исходный код приложения
    │   ├── Core/              # Ядро фреймворка
    │   ├── Entities/          # Бизнес-сущности
    │   ├── UseCases/          # Бизнес-логика
    │   ├── Controllers/       # MVC Controllers
    │   ├── Models/            # MVC Models (Repository)
    │   ├── Views/             # MVC Views (шаблоны)
    │   ├── Services/          # Сервисы приложения
    │   └── Infrastructure/    # Внешние зависимости
    ├── config/
    │   └── config.php         # Конфигурация
    ├── composer.json
    └── vendor/                # Зависимости
```

## Принципы рефакторинга

1. **Разделение ответственности** - каждый класс имеет одну ответственность
2. **Инверсия зависимостей** - зависимость от абстракций, а не от конкретных реализаций
3. **Единая точка входа** - все запросы проходят через Front Controller
4. **Repository Pattern** - абстракция доступа к данным
5. **Dependency Injection** - внедрение зависимостей
6. **PSR-4 Autoloading** - автозагрузка классов

## Технологии

- **PHP 8.1** + Apache
- **MySQL 8.0** - реляционная БД
- **Redis 7** - хранение сессий
- **Nginx** - реверс-прокси
- **Composer** - менеджер зависимостей
- **Clean Architecture** - архитектурный подход
- **MVC Pattern** - паттерн проектирования

## Запуск проекта

### Шаг 1: Запустить Docker Compose
```bash
cd practice7
docker-compose -f docker-compose-simple.yml up -d --build
```

### Шаг 2: Установить зависимости Composer
```bash
docker exec practice7_web composer install
```

### Шаг 3: Сгенерировать фикстуры (опционально)
```bash
docker exec practice7_web php generate_fixtures.php 50
```

### Шаг 4: Открыть в браузере
```
http://localhost:8087
```

## Тестирование

### Авторизация
- Логин: `admin`, пароль: `password123` (администратор)
- Логин: `user1`, пароль: `password123` (пользователь)

### Основные страницы
- **Главная:** http://localhost:8087/
- **Пользователи:** http://localhost:8087/users
- **Статистика:** http://localhost:8087/statistics
- **Настройки:** http://localhost:8087/settings (требует авторизации)
- **Сервисы:** http://localhost:8087/services
  - **SVG Генератор:** http://localhost:8087/services/drawer
  - **Сортировка массивов:** http://localhost:8087/services/sort
  - **Админ-панель:** http://localhost:8087/services/admin (только для admin)

### Проверка архитектуры

1. **Clean Architecture слои:**
   - `src/Entities/` - бизнес-сущности
   - `src/UseCases/` - варианты использования
   - `src/Controllers/` - MVC контроллеры
   - `src/Models/` - Repository паттерн
   - `src/Views/` - шаблоны представления
   - `src/Services/` - сервисы приложения
   - `src/Infrastructure/` - внешние зависимости

2. **MVC Pattern:**
   - **Model:** Repository классы в `src/Models/`
   - **View:** Шаблоны в `src/Views/`
   - **Controller:** Контроллеры в `src/Controllers/`

3. **Dependency Injection:**
   - DI контейнер в `src/Core/Container.php`
   - Регистрация сервисов в `src/Core/Application.php`

4. **Single Responsibility:**
   - Каждый класс имеет одну ответственность
   - Разделение бизнес-логики и представления

## Сравнение с предыдущими практиками

### До рефакторинга (Practice 1-6):
- ❌ Процедурный код
- ❌ Смешивание HTML и PHP
- ❌ Прямые SQL-запросы в контроллерах
- ❌ Отсутствие разделения ответственности
- ❌ Дублирование кода

### После рефакторинга (Practice 7):
- ✅ Объектно-ориентированный подход
- ✅ Clean Architecture
- ✅ MVC Pattern
- ✅ Repository Pattern
- ✅ Dependency Injection
- ✅ PSR-4 Autoloading
- ✅ Единая точка входа (Front Controller)
- ✅ Разделение ответственности

## Остановка проекта

```bash
docker-compose down
```

Для полной очистки:
```bash
docker-compose down -v
```