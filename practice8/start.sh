#!/bin/bash

# Скрипт быстрого запуска Practice 8 - Laravel Integration
echo "🚀 Запуск Practice 8 - Laravel Integration"

# Проверка Docker
if ! command -v docker &> /dev/null; then
    echo "❌ Docker не установлен. Установите Docker для продолжения."
    exit 1
fi

if ! command -v docker-compose &> /dev/null; then
    echo "❌ Docker Compose не установлен. Установите Docker Compose для продолжения."
    exit 1
fi

echo "📦 Запуск Docker контейнеров..."
docker-compose up -d --build

echo "⏳ Ожидание запуска сервисов..."
sleep 10

echo "📚 Установка зависимостей Composer..."
docker-compose exec app composer install --no-dev --optimize-autoloader

echo "📁 Создание необходимых директорий..."
docker-compose exec app mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs public/charts

echo "🔐 Настройка прав доступа..."
docker-compose exec app chown -R www:www bootstrap/cache storage public/charts
docker-compose exec app chmod -R 775 bootstrap/cache storage public/charts

echo "🔑 Генерация ключа приложения..."
docker-compose exec app php artisan key:generate

echo "🗄️ Выполнение миграций..."
docker-compose exec app php artisan migrate --force

echo "🌱 Заполнение базы данных тестовыми данными..."
docker-compose exec app php artisan db:seed --force

echo "🧹 Очистка кеша..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear

echo "✅ Practice 8 успешно запущен!"
echo ""
echo "🌐 Откройте в браузере: http://localhost:8088"
echo ""
echo "👤 Тестовые пользователи:"
echo "   Администратор: admin@practice8.local / password123"
echo "   Пользователь: user@practice8.local / password123"
echo ""
echo "🛠️ Полезные команды:"
echo "   Остановить: docker-compose down"
echo "   Логи: docker-compose logs -f"
echo "   Консоль PHP: docker-compose exec app bash"
echo ""