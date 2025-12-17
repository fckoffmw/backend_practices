<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesStatistic;
use App\Services\ChartGeneratorService;

/**
 * Контроллер статистики
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class StatisticsController extends Controller
{
    private ChartGeneratorService $chartGenerator;

    public function __construct(ChartGeneratorService $chartGenerator)
    {
        $this->chartGenerator = $chartGenerator;
    }

    public function index(Request $request)
    {
        // Получаем статистику по категориям
        $categoryStats = SalesStatistic::getCategoryStats();
        
        // Получаем статистику по регионам
        $regionStats = SalesStatistic::getRegionStats();
        
        // Получаем статистику по месяцам
        $monthlyStats = SalesStatistic::getMonthlyStats();
        
        // Получаем топ продукты
        $topProducts = SalesStatistic::getTopProducts(10);

        // Генерируем графики
        $charts = [];
        
        if ($categoryStats->isNotEmpty()) {
            $charts['bar'] = $this->chartGenerator->generateBarChart(
                $categoryStats->toArray(),
                'Выручка по категориям'
            );
        }
        
        if ($regionStats->isNotEmpty()) {
            $charts['pie'] = $this->chartGenerator->generatePieChart(
                $regionStats->toArray(),
                'Продажи по регионам'
            );
        }
        
        if ($monthlyStats->isNotEmpty()) {
            $charts['line'] = $this->chartGenerator->generateLineChart(
                $monthlyStats->toArray(),
                'Динамика продаж по месяцам'
            );
        }

        return view('statistics.index', compact(
            'categoryStats',
            'regionStats', 
            'monthlyStats',
            'topProducts',
            'charts'
        ));
    }

    public function generateFixtures(Request $request)
    {
        $count = $request->input('count', 50);
        
        if ($count > 1000) {
            return back()->withErrors(['count' => 'Максимальное количество записей: 1000']);
        }

        // Используем Laravel Factory для генерации данных
        SalesStatistic::factory()->count($count)->create();

        return back()->with('success', "Сгенерировано {$count} записей статистики");
    }
}