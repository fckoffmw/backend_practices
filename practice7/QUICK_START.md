# Быстрый запуск Practice 7

## Команды для запуска

### 1. Запуск контейнеров
```bash
cd practice7
docker-compose -f docker-compose-simple.yml up -d --build
```

### 2. Установка зависимостей
```bash
docker exec practice7_web composer install
```

### 3. Генерация тестовых данных (опционально)
```bash
docker exec practice7_web php generate_fixtures.php 50
```

## Доступ к системе

- **URL:** http://localhost:8087/
- **Логин:** admin
- **Пароль:** password123

## Основные страницы

- **Главная:** http://localhost:8087/
- **Вход:** http://localhost:8087/login
- **Пользователи:** http://localhost:8087/users
- **Статистика:** http://localhost:8087/statistics
- **Настройки:** http://localhost:8087/settings (требует авторизации)

## Отладочные страницы

- **Демо архитектуры:** http://localhost:8087/demo.php
- **Отладка аутентификации:** http://localhost:8087/debug-auth.php
- **Тест в браузере:** http://localhost:8087/test-auth.html

## Остановка

```bash
docker-compose -f docker-compose-simple.yml down
```

Для полной очистки данных:
```bash
docker-compose -f docker-compose-simple.yml down -v
```

## Тестовые пользователи

| Логин | Пароль | Роль |
|-------|--------|------|
| admin | password123 | Администратор |
| user1 | password123 | Пользователь |
| user2 | password123 | Пользователь |
| user3 | password123 | Пользователь |
| user4 | password123 | Пользователь |

## Проверка работы

1. Откройте http://localhost:8087/
2. Нажмите "Войти"
3. Введите admin/password123
4. Должно появиться "Привет, Admin!"
5. Перейдите в "Статистика" для просмотра графиков