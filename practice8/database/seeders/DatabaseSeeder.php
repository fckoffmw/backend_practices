<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\SalesStatistic;

class DatabaseSeeder extends Seeder
{
    /**
     * Заполнение базы данных тестовыми данными
     */
    public function run(): void
    {
        // Создаем тестовых пользователей
        User::factory()->create([
            'name' => 'Администратор',
            'email' => 'admin@practice8.local',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Пользователь',
            'email' => 'user@practice8.local',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        // Создаем дополнительных пользователей
        User::factory(10)->create();

        // Создаем статистику продаж
        SalesStatistic::factory(100)->create();
    }
}