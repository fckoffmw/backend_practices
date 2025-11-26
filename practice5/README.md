# Practice 5 — Сессии Redis, Согласование контента, PDF Upload

## Описание

Данная практическая работа объединяет функциональность практик 1-4 и добавляет:

1. **Хранение сессий в Redis** — все данные сессий хранятся в Redis
2. **Согласование контента** — индивидуальные настройки пользователя:
   - Логин пользователя
   - Тема оформления (светлая, тёмная, для дальтоников)
   - Язык интерфейса (русский, английский, немецкий)
3. **Загрузка PDF файлов** — загрузка и скачивание PDF с хранением в MySQL (LONGBLOB)

## Структура проекта

```
practice5/
├── docker-compose.yml      # Конфигурация Docker (MySQL, Redis, PHP, Nginx)
├── db/
│   └── init.sql            # Инициализация базы данных
├── php/
│   └── Dockerfile          # Образ PHP с Redis extension
├── nginx/
│   └── nginx.conf          # Конфигурация Nginx
└── www/
    ├── config.php          # Подключение к MySQL и Redis
    ├── header.php          # Общий заголовок с согласованием контента
    ├── footer.php          # Общий футер
    ├── styles.php          # Динамические CSS стили (по теме)
    ├── index.php           # Главная страница
    ├── login.php           # Авторизация
    ├── logout.php          # Выход
    ├── settings.php        # Настройки пользователя
    ├── services.php        # Список сервисов
    ├── admin.php           # Админ-панель
    ├── api/
    │   ├── users.php       # REST API пользователей
    │   └── orders.php      # REST API заказов
    ├── pdf/
    │   ├── index.php       # Загрузка PDF файлов
    │   └── download.php    # Скачивание PDF файлов
    └── services/
        ├── drawer/         # SVG генератор (из Practice 2)
        ├── sort/           # Сортировка массивов (из Practice 2)
        └── admin/          # Системные команды (из Practice 2)
```

## Запуск проекта

### Шаг 1: Перейти в папку practice5
```bash
cd /Users/ekaterinakopylova/Documents/backend_practices/practice5
```

### Шаг 2: Запустить Docker Compose
```bash
docker-compose up --build -d
```

### Шаг 3: Дождаться запуска всех сервисов
```bash
docker-compose logs -f
```

Когда увидите сообщения о готовности MySQL и Redis, можно открывать сайт.

### Шаг 4: Открыть в браузере
```
http://localhost:8080
```

## Тестирование

### 1. Тестирование сессий в Redis

1. Откройте `http://localhost:8080`
2. Войдите под учётной записью `admin` / `password123`
3. В футере страницы отображается Session ID
4. Проверьте сессии в Redis:
   ```bash
   docker exec -it practice5_redis redis-cli
   > KEYS *
   > GET PHPREDIS_SESSION:<session_id>
   ```

### 2. Тестирование согласования контента

1. Откройте `http://localhost:8080/settings.php`
2. Измените тему (светлая/тёмная/для дальтоников)
3. Измените язык (RU/EN/DE)
4. Нажмите "Сохранить"
5. Страница перезагрузится с новыми настройками
6. Откройте DevTools → Application → Cookies — увидите cookies:
   - `user_theme`
   - `user_language`
   - `user_login`

**Источники данных:**
- Для авторизованных пользователей: данные берутся из сессии (Redis) и БД
- Для гостей: данные берутся из cookies

### 3. Тестирование загрузки PDF

1. Войдите в систему
2. Откройте `http://localhost:8080/pdf/index.php`
3. Выберите любой PDF файл (до 50MB)
4. Нажмите "Загрузить PDF"
5. Файл появится в списке
6. Нажмите "Скачать" для проверки выдачи файла

**Проверка хранения в БД:**
```bash
docker exec -it practice5_db mysql -u user -ppassword appDB
mysql> SELECT id, filename, original_name, file_size, uploaded_at FROM pdf_files;
```

### 4. Тестирование REST API

**Получение списка пользователей:**
```bash
curl http://localhost:8080/api/users.php
```

**Получение списка заказов:**
```bash
curl http://localhost:8080/api/orders.php
```

**Создание нового пользователя:**
```bash
curl -X POST http://localhost:8080/api/users.php \
  -H "Content-Type: application/json" \
  -d '{"login":"newuser","password":"pass123","name":"John","surname":"Doe"}'
```

**Создание нового заказа:**
```bash
curl -X POST http://localhost:8080/api/orders.php \
  -H "Content-Type: application/json" \
  -d '{"user_id":2,"product":"Monitor","amount":25000}'
```

### 5. Тестирование сервисов из предыдущих практик

- **Drawer (SVG):** `http://localhost:8080/services/drawer/drawer.php?num=12345`
- **Sorter:** `http://localhost:8080/services/sort/sort.php`
- **Admin commands:** `http://localhost:8080/services/admin/admin.php`

## Тестовые учётные записи

| Логин | Пароль | Роль | Тема | Язык |
|-------|--------|------|------|------|
| admin | password123 | Администратор | dark | ru |
| user1 | password123 | Пользователь | light | en |
| user2 | password123 | Пользователь | colorblind | de |
| user3 | password123 | Пользователь | light | ru |
| user4 | password123 | Пользователь | dark | en |

## Остановка проекта

```bash
docker-compose down
```

Для полной очистки (включая данные):
```bash
docker-compose down -v
```

## Технологии

- **PHP 8.1** + Apache
- **MySQL 8.0** — реляционная БД
- **Redis 7** — хранение сессий
- **Nginx** — реверс-прокси
- **Docker Compose** — оркестрация контейнеров

