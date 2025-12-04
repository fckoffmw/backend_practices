# Practice 6 — Генерация фикстур, графики и водяные знаки

## Описание

Практическая работа 6 добавляет следующую функциональность к практикам 1-5:

1. **Генерация фикстур** — 50+ записей с 9 полями с использованием библиотеки Faker
2. **Построение графиков** — 3 типа графиков (столбчатая, круговая, линейная) с использованием GD
3. **Водяные знаки** — полупрозрачные водяные знаки на графиках с помощью GD
4. **Страница статистики** — отображение всех графиков и данных

## Структура проекта

```
practice6/
├── docker-compose.yml      # Конфигурация Docker
├── db/
│   └── init.sql            # Инициализация БД
├── php/
│   └── Dockerfile          # Образ PHP с GD, Redis, MySQL
├── nginx/
│   └── nginx.conf          # Конфигурация Nginx
└── www/
    ├── config.php          # Подключение к MySQL и Redis
    ├── header.php          # Общий заголовок
    ├── footer.php          # Общий футер
    ├── styles.php          # Динамические CSS
    ├── index.php           # Главная страница
    ├── login.php           # Авторизация
    ├── logout.php          # Выход
    ├── settings.php        # Настройки пользователя
    ├── services.php        # Список сервисов
    ├── admin.php           # Админ-панель
    ├── statistics.php      # Страница статистики с графиками
    ├── generate_fixtures.php # Генерация фикстур
    ├── composer.json       # Зависимости (Faker)
    ├── src/
    │   └── ChartGenerator.php # Класс для генерации графиков
    └── charts/             # Директория для графиков
```

## Используемые технологии

### 1. Composer — менеджер зависимостей PHP

**Установка зависимостей:**
```bash
composer install
```

**Использование в проекте:**
```bash
composer require fakerphp/faker
```

### 2. Faker — библиотека для генерации фикстур

**Установка:**
```bash
composer require fakerphp/faker
```

**Использование:**
```php
use Faker\Factory;

$faker = Factory::create('ru_RU');
$name = $faker->name();
$email = $faker->email();
$price = $faker->randomFloat(2, 100, 50000);
```

### 3. GD — библиотека для работы с изображениями

**Установка в Dockerfile:**
```dockerfile
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
```

**Основные функции:**
- `imagecreatetruecolor()` — создание изображения
- `imagecolorallocate()` — выделение цвета
- `imagecolorallocatealpha()` — цвет с прозрачностью
- `imageline()`, `imagefilledrectangle()`, `imagefilledellipse()` — рисование фигур
- `imagefilledarc()` — рисование секторов (круговая диаграмма)
- `imagestring()` — вывод текста
- `imagecopy()` — копирование изображений (водяной знак)
- `imagepng()` — сохранение в PNG

## Запуск проекта

### Шаг 1: Запустить Docker Compose
```bash
cd practice6
docker-compose up -d --build
```

### Шаг 2: Установить зависимости Composer
```bash
docker exec practice6_web composer install
```

### Шаг 3: Сгенерировать фикстуры
```bash
docker exec practice6_web php generate_fixtures.php
```

### Шаг 4: Открыть в браузере
```
http://localhost:8086
http://localhost:8086/statistics.php
```

## Тестирование

### 1. Генерация фикстур

**Запуск:**
```bash
docker exec practice6_web php generate_fixtures.php
```

**Проверка в БД:**
```bash
docker exec practice6_db mysql -u user -ppassword appDB -e "SELECT COUNT(*) FROM sales_statistics;"
docker exec practice6_db mysql -u user -ppassword appDB -e "SELECT * FROM sales_statistics LIMIT 5;"
```

**Ожидаемый результат:** 50 записей с полями:
- product_name, category, price, quantity, revenue
- sale_date, region, customer_name, customer_email

### 2. Построение графиков

**Страница:** http://localhost:8086/statistics.php

**Графики:**
1. **Столбчатая диаграмма** — выручка по категориям
2. **Круговая диаграмма** — распределение продаж по регионам
3. **Линейный график** — динамика продаж по месяцам

**Проверка файлов:**
```bash
docker exec practice6_web ls -lh /var/www/html/charts/
```

### 3. Водяные знаки

На каждом графике в правом нижнем углу присутствует полупрозрачный текст "Practice 6 © 2024".

**Реализация:**
- Функция `addWatermark()` в классе `ChartGenerator`
- Использование `imagecolorallocatealpha()` с alpha = 50 (прозрачность 60%)

### 4. Сохранение функциональности из практик 1-5

- **Авторизация:** http://localhost:8086/login.php (admin/password123)
- **Настройки:** http://localhost:8086/settings.php (темы, языки)
- **Сессии в Redis:**
```bash
docker exec practice6_redis redis-cli KEYS "*"
```

## Тестовые учетные записи

| Логин | Пароль | Роль |
|-------|--------|------|
| admin | password123 | Администратор |
| user1 | password123 | Пользователь |
| user2 | password123 | Пользователь |

## Остановка проекта

```bash
docker-compose down
```

Для полной очистки:
```bash
docker-compose down -v
```

## Спецификация реализованной функциональности

### 1. Генерация фикстур (generate_fixtures.php)

**Библиотека:** Faker 1.24+

**Таблица:** `sales_statistics`

**Поля (9 полей):**
1. product_name — название товара
2. category — категория (6 вариантов)
3. price — цена (100-50000 руб)
4. quantity — количество (1-100)
5. revenue — выручка (price × quantity)
6. sale_date — дата продажи (за последний год)
7. region — регион (5 вариантов)
8. customer_name — имя покупателя
9. customer_email — email покупателя

**Количество:** 50 записей

### 2. Класс ChartGenerator (src/ChartGenerator.php)

**Библиотека:** GD (встроена в PHP)

**Методы:**

1. **generateBarChart()** — столбчатая диаграмма
   - SQL: `SELECT category, SUM(revenue) FROM sales_statistics GROUP BY category`
   - Функции: `imagefilledrectangle()`, `imageline()`, `imagestring()`
   - Размер: 800×600 px

2. **generatePieChart()** — круговая диаграмма
   - SQL: `SELECT region, COUNT(*) FROM sales_statistics GROUP BY region`
   - Функции: `imagefilledarc()` с флагом `IMG_ARC_PIE`
   - Размер: 800×600 px

3. **generateLineChart()** — линейный график
   - SQL: `SELECT DATE_FORMAT(sale_date, '%Y-%m'), SUM(revenue) FROM sales_statistics GROUP BY month`
   - Функции: `imageline()`, `imagefilledellipse()`
   - Размер: 800×600 px

4. **addWatermark()** — добавление водяного знака
   - Текст: "Practice 6 © 2024"
   - Позиция: правый нижний угол
   - Прозрачность: 60% (alpha = 50)
   - Функции: `imagecolorallocatealpha()`, `imagecopy()`

### 3. Страница статистики (statistics.php)

**Функциональность:**
- Проверка наличия фикстур в БД
- Генерация 3 графиков с помощью `ChartGenerator`
- Отображение графиков с описаниями
- Таблица с первыми 10 записями
- Кнопка для перегенерации фикстур

## Технологии

- **PHP 8.1** + Apache
- **MySQL 8.0** — реляционная БД
- **Redis 7** — хранение сессий
- **Nginx** — реверс-прокси
- **Docker Compose** — оркестрация контейнеров
- **Composer** — менеджер зависимостей
- **Faker** — генерация фикстур
- **GD** — создание графиков и водяных знаков

## Выводы

Реализована следующая функциональность:

1. ✅ **Генерация фикстур:** 50 записей с 9 полями с использованием Faker
2. ✅ **Построение графиков:** 3 типа графиков с использованием GD
3. ✅ **Водяные знаки:** Полупрозрачные водяные знаки на всех графиках
4. ✅ **Страница статистики:** Отображение графиков и данных
5. ✅ **Сохранение функциональности:** Все возможности из практик 1-5 работают

Все требования задания выполнены.
