<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

/**
 * Factory для генерации тестовых данных SalesStatistic
 * Миграция из Practice 7 с адаптацией под Laravel Factories
 */
class SalesStatisticFactory extends Factory
{
    /**
     * Определение состояния модели по умолчанию
     */
    public function definition(): array
    {
        $products = [
            'Смартфон iPhone 15', 'Ноутбук MacBook Pro', 'Планшет iPad Air',
            'Наушники AirPods', 'Умные часы Apple Watch', 'Телевизор Samsung',
            'Холодильник LG', 'Стиральная машина Bosch', 'Микроволновка Panasonic',
            'Пылесос Dyson', 'Кофемашина Nespresso', 'Блендер Vitamix',
            'Фотоаппарат Canon', 'Игровая консоль PlayStation', 'Клавиатура Logitech',
            'Мышь Razer', 'Монитор Dell', 'Принтер HP', 'Роутер ASUS',
            'Внешний диск Seagate'
        ];

        $categories = [
            'Электроника', 'Бытовая техника', 'Компьютеры', 'Мобильные устройства',
            'Аудио и видео', 'Игры и развлечения', 'Фото и видео', 'Сетевое оборудование'
        ];

        $regions = [
            'Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург',
            'Казань', 'Нижний Новгород', 'Челябинск', 'Самара',
            'Омск', 'Ростов-на-Дону', 'Уфа', 'Красноярск'
        ];

        $price = $this->faker->randomFloat(2, 1000, 150000);
        $quantity = $this->faker->numberBetween(1, 10);

        return [
            'product_name' => $this->faker->randomElement($products),
            'category' => $this->faker->randomElement($categories),
            'price' => $price,
            'quantity' => $quantity,
            'revenue' => $price * $quantity,
            'sale_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
            'region' => $this->faker->randomElement($regions),
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->unique()->safeEmail(),
        ];
    }
}