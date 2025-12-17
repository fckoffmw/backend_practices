# Practice 8 — Интеграция в Laravel Framework

## Описание

Интеграция функциональности из Practice 7 (Clean Architecture + MVC) в Laravel фреймворк с сохранением всей функциональности.

## Критерии выбора Laravel Framework

### 1. Архитектурная совместимость
- **MVC из коробки** - Laravel нативно поддерживает MVC паттерн
- **Service Container** - встроенный IoC контейнер для Dependency Injection
- **Repository Pattern** - легко интегрируется с Eloquent ORM
- **Clean Architecture** - Laravel позволяет организовать код по принципам чистой архитектуры

### 2. Функциональные преимущества
- **Eloquent ORM** - мощная ORM для работы с базой данных
- **Миграции и сидеры** - управление схемой БД и тестовыми данными
- **Middleware** - система промежуточного ПО для аутентификации и авторизации
- **Blade Templates** - мощный шаблонизатор
- **Artisan CLI** - консольные команды для автоматизации

### 3. Экосистема и инструменты
- **Laravel Sail** - Docker окружение из коробки
- **Redis поддержка** - встроенная поддержка Redis для сессий и кеширования
- **Validation** - встроенная валидация данных
- **Testing** - PHPUnit интеграция и фабрики моделей

### 4. Производительность и масштабируемость
- **Кеширование** - многоуровневая система кеширования
- **Queue система** - обработка фоновых задач
- **Event система** - слабосвязанная архитектура через события

### 5. Сообщество и поддержка
- **Популярность** - самый популярный PHP фреймворк
- **Документация** - отличная официальная документация
- **Экосистема пакетов** - огромное количество готовых решений
- **LTS версии** - долгосрочная поддержка

## Применение к проекту Practice 7

### Миграция архитектуры

1. **Entities → Eloquent Models**
   - `SalesStatistic` → `App\Models\SalesStatistic`
   - Использование Eloquent ORM вместо PDO

2. **Controllers → Laravel Controllers**
   - Сохранение логики контроллеров
   - Использование Laravel Request/Response
   - Middleware для аутентификации

3. **Services → Laravel Services**
   - Регистрация в Service Provider
   - Dependency Injection через конструктор

4. **Views → Blade Templates**
   - Конвертация PHP шаблонов в Blade
   - Использование компонентов и layouts

5. **Repository → Eloquent Repository**
   - Адаптация Repository паттерна под Eloquent
   - Использование Query Builder

### Сохранение функциональности

Вся функциональность Practice 7 будет сохранена:

- ✅ **Аутентификация и авторизация** - Laravel Auth + Middleware
- ✅ **Управление пользователями** - Eloquent User Model
- ✅ **Статистика продаж** - SalesStatistic Model + Repository
- ✅ **Генерация графиков** - ChartGenerator Service
- ✅ **SVG генератор** - DrawerService
- ✅ **Сортировка массивов** - SortService
- ✅ **Админ-панель** - AdminController + Middleware
- ✅ **Redis сессии** - Laravel Session Driver
- ✅ **Docker окружение** - Laravel Sail

## Технологии

- **Laravel 10.x** - PHP фреймворк
- **PHP 8.1+** - язык программирования
- **MySQL 8.0** - реляционная БД
- **Redis 7** - кеширование и сессии
- **Docker** - контейнеризация
- **Nginx** - веб-сервер
- **Composer** - менеджер зависимостей

## Структура проекта

```
practice8/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Laravel Controllers
│   │   ├── Middleware/         # Middleware для авторизации
│   │   └── Requests/           # Form Requests для валидации
│   ├── Models/                 # Eloquent Models
│   ├── Services/               # Бизнес-сервисы из Practice 7
│   ├── Repositories/           # Repository паттерн
│   └── Providers/              # Service Providers
├── database/
│   ├── migrations/             # Миграции БД
│   ├── seeders/                # Сидеры для тестовых данных
│   └── factories/              # Model Factories
├── resources/
│   ├── views/                  # Blade шаблоны
│   └── js/css/                 # Frontend ресурсы
├── routes/
│   └── web.php                 # Веб-маршруты
├── docker-compose.yml          # Docker конфигурация
└── .env                        # Конфигурация окружения
```

## Запуск проекта

### Быстрый запуск (рекомендуется)
```bash
cd practice8
chmod +x start.sh
./start.sh
```

### Ручной запуск

#### Шаг 1: Запустить Docker окружение
```bash
cd practice8
docker-compose up -d --build
```

#### Шаг 2: Установить зависимости
```bash
docker-compose exec app composer install
```

#### Шаг 3: Настроить приложение
```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

#### Шаг 4: Создать директории
```bash
docker-compose exec app mkdir -p public/charts
docker-compose exec app chmod 755 public/charts
```

#### Шаг 5: Открыть в браузере
```
http://localhost:8088
```

### Тестовые пользователи
- **Администратор:** admin@practice8.local / password123
- **Пользователь:** user@practice8.local / password123

## Преимущества интеграции в Laravel

1. **Стандартизация** - следование Laravel конвенциям
2. **Производительность** - оптимизации фреймворка
3. **Безопасность** - встроенные механизмы защиты
4. **Тестирование** - удобные инструменты для тестирования
5. **Развертывание** - стандартные инструменты деплоя
6. **Поддержка** - долгосрочная поддержка LTS версий

## Сравнение с Practice 7

| Аспект | Practice 7 (Custom) | Practice 8 (Laravel) |
|--------|--------------------|--------------------|
| Архитектура | Clean Architecture | Laravel + Clean Architecture |
| ORM | PDO + Repository | Eloquent + Repository |
| Шаблоны | PHP Templates | Blade Templates |
| Роутинг | Custom Router | Laravel Router |
| DI Container | Custom Container | Laravel Service Container |
| Middleware | Custom | Laravel Middleware |
| Валидация | Manual | Laravel Validation |
| Тестирование | Manual | PHPUnit + Laravel Testing |
| CLI | Custom Scripts | Artisan Commands |
| Кеширование | Manual | Laravel Cache |