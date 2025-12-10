<?php

namespace App\Controllers;

use App\Core\Controller;
use App\UseCases\GenerateStatistics;
use App\Models\SalesStatisticRepository;
use App\Services\ChartGeneratorService;
use App\Services\FixtureGeneratorService;
use App\Infrastructure\Database\DatabaseConnection;

/**
 * Контроллер статистики
 */
class StatisticsController extends Controller
{
    private GenerateStatistics $generateStatistics;
    private FixtureGeneratorService $fixtureGenerator;

    public function __construct($container)
    {
        parent::__construct($container);
        
        $db = $this->container->get(DatabaseConnection::class);
        $salesRepository = new SalesStatisticRepository($db);
        $chartGenerator = new ChartGeneratorService($this->config['paths']['charts']);
        
        $this->generateStatistics = new GenerateStatistics($salesRepository, $chartGenerator);
        $this->fixtureGenerator = new FixtureGeneratorService($salesRepository);
    }

    /**
     * Показать страницу статистики
     */
    public function index(): void
    {
        // Проверяем наличие данных
        if (!$this->fixtureGenerator->hasData()) {
            $this->render('statistics/no_data', [
                'title' => 'Статистика',
                'user' => $this->getCurrentUser(),
            ]);
            return;
        }

        // Генерируем графики
        $barChart = $this->generateStatistics->generateBarChart();
        $pieChart = $this->generateStatistics->generatePieChart();
        $lineChart = $this->generateStatistics->generateLineChart();

        // Получаем общую статистику
        $overallStats = $this->generateStatistics->getOverallStatistics();
        
        // Получаем данные для таблиц
        $revenueByCategory = $this->generateStatistics->getRevenueByCategory();
        $salesByRegion = $this->generateStatistics->getSalesByRegion();
        $salesByMonth = $this->generateStatistics->getSalesByMonth();

        $this->render('statistics/index', [
            'title' => 'Статистика продаж',
            'user' => $this->getCurrentUser(),
            'charts' => [
                'bar' => $barChart,
                'pie' => $pieChart,
                'line' => $lineChart,
            ],
            'stats' => $overallStats,
            'data' => [
                'categories' => $revenueByCategory,
                'regions' => $salesByRegion,
                'months' => $salesByMonth,
            ],
        ]);
    }

    /**
     * Генерация фикстур
     */
    public function generateFixtures(): void
    {
        $count = (int) ($_POST['count'] ?? 50);
        $generated = $this->fixtureGenerator->generateFixtures($count);
        
        $_SESSION['success_message'] = "Сгенерировано {$generated} записей";
        $this->redirect('/statistics');
    }
}