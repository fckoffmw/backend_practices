<?php

namespace App\Services;

use App\Entities\SalesStatistic;
use App\Models\SalesStatisticRepository;
use Faker\Factory;

/**
 * Сервис для генерации тестовых данных
 */
class FixtureGeneratorService
{
    private SalesStatisticRepository $repository;
    private \Faker\Generator $faker;

    public function __construct(SalesStatisticRepository $repository)
    {
        $this->repository = $repository;
        $this->faker = Factory::create('ru_RU');
    }

    /**
     * Генерация фикстур
     */
    public function generateFixtures(int $count = 50): int
    {
        // Очищаем существующие данные
        $this->repository->truncate();
        
        $categories = ['Электроника', 'Одежда', 'Книги', 'Спорт', 'Дом и сад', 'Автомобили'];
        $regions = ['Москва', 'Санкт-Петербург', 'Екатеринбург', 'Новосибирск', 'Казань'];
        
        $generated = 0;
        
        for ($i = 0; $i < $count; $i++) {
            $price = $this->faker->randomFloat(2, 100, 50000);
            $quantity = $this->faker->numberBetween(1, 100);
            
            $statistic = new SalesStatistic(
                productName: $this->faker->words(3, true),
                category: $this->faker->randomElement($categories),
                price: $price,
                quantity: $quantity,
                saleDate: $this->faker->dateTimeBetween('-1 year', 'now'),
                region: $this->faker->randomElement($regions),
                customerName: $this->faker->name(),
                customerEmail: $this->faker->email()
            );
            
            $this->repository->save($statistic);
            $generated++;
        }
        
        return $generated;
    }

    /**
     * Проверка наличия данных
     */
    public function hasData(): bool
    {
        return $this->repository->getTotalSales() > 0;
    }
}