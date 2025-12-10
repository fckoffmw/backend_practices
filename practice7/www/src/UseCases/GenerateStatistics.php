<?php

namespace App\UseCases;

use App\Models\SalesStatisticRepository;
use App\Services\ChartGeneratorService;

/**
 * Use Case для генерации статистики и графиков
 */
class GenerateStatistics
{
    private SalesStatisticRepository $salesRepository;
    private ChartGeneratorService $chartGenerator;

    public function __construct(
        SalesStatisticRepository $salesRepository,
        ChartGeneratorService $chartGenerator
    ) {
        $this->salesRepository = $salesRepository;
        $this->chartGenerator = $chartGenerator;
    }

    /**
     * Получение статистики по категориям
     */
    public function getRevenueByCategory(): array
    {
        return $this->salesRepository->getRevenueByCategory();
    }

    /**
     * Получение статистики по регионам
     */
    public function getSalesByRegion(): array
    {
        return $this->salesRepository->getSalesByRegion();
    }

    /**
     * Получение динамики продаж по месяцам
     */
    public function getSalesByMonth(): array
    {
        return $this->salesRepository->getSalesByMonth();
    }

    /**
     * Генерация столбчатой диаграммы
     */
    public function generateBarChart(): string
    {
        $data = $this->getRevenueByCategory();
        return $this->chartGenerator->generateBarChart($data, 'Выручка по категориям');
    }

    /**
     * Генерация круговой диаграммы
     */
    public function generatePieChart(): string
    {
        $data = $this->getSalesByRegion();
        return $this->chartGenerator->generatePieChart($data, 'Продажи по регионам');
    }

    /**
     * Генерация линейного графика
     */
    public function generateLineChart(): string
    {
        $data = $this->getSalesByMonth();
        return $this->chartGenerator->generateLineChart($data, 'Динамика продаж');
    }

    /**
     * Получение общей статистики
     */
    public function getOverallStatistics(): array
    {
        $totalSales = $this->salesRepository->getTotalSales();
        $totalRevenue = $this->salesRepository->getTotalRevenue();
        $averageOrderValue = $totalSales > 0 ? $totalRevenue / $totalSales : 0;
        
        return [
            'total_sales' => $totalSales,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $averageOrderValue,
            'categories_count' => count($this->getRevenueByCategory()),
            'regions_count' => count($this->getSalesByRegion()),
        ];
    }
}