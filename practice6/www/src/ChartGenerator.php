<?php
namespace App;

use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

class ChartGenerator
{
    private $pdo;
    
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        
        // Настройка путей JpGraph
        if (!defined('TTF_DIR')) {
            define('TTF_DIR', __DIR__ . '/../vendor/amenadiel/jpgraph/src/fonts/');
        }
    }
    
    /**
     * График 1: Столбчатая диаграмма - Выручка по категориям
     */
    public function generateBarChart()
    {
        // Получение данных
        $stmt = $this->pdo->query("
            SELECT category, SUM(revenue) as total_revenue 
            FROM sales_statistics 
            GROUP BY category 
            ORDER BY total_revenue DESC
        ");
        $data = $stmt->fetchAll();
        
        $categories = array_column($data, 'category');
        $revenues = array_column($data, 'total_revenue');
        
        // Создание графика с помощью JpGraph
        $graph = new Graph\Graph(800, 600);
        $graph->SetScale('textlin');
        $graph->title->Set('Revenue by Category');
        $graph->xaxis->SetTickLabels($categories);
        $graph->yaxis->title->Set('Revenue');
        
        // Создание столбчатой диаграммы
        $barplot = new Plot\BarPlot($revenues);
        $barplot->SetFillColor('steelblue');
        $barplot->value->Show();
        $barplot->value->SetFormat('%d');
        
        $graph->Add($barplot);
        
        // Сохранение
        $outputPath = __DIR__ . '/../charts/bar_chart.png';
        $graph->Stroke($outputPath);
        
        // Добавление водяного знака с помощью GD
        $this->addWatermark($outputPath);
        
        return $outputPath;
    }
    
    /**
     * График 2: Круговая диаграмма - Распределение продаж по регионам
     */
    public function generatePieChart()
    {
        // Получение данных
        $stmt = $this->pdo->query("
            SELECT region, COUNT(*) as sales_count 
            FROM sales_statistics 
            GROUP BY region
        ");
        $data = $stmt->fetchAll();
        
        $regions = array_column($data, 'region');
        $counts = array_column($data, 'sales_count');
        
        // Создание круговой диаграммы с помощью JpGraph
        $graph = new Graph\PieGraph(800, 600);
        $graph->title->Set('Sales Distribution by Region');
        
        $pieplot = new Plot\PiePlot($counts);
        $pieplot->SetLegends($regions);
        $pieplot->value->SetFormat('%d');
        $pieplot->value->Show();
        
        $graph->Add($pieplot);
        
        // Сохранение
        $outputPath = __DIR__ . '/../charts/pie_chart.png';
        $graph->Stroke($outputPath);
        
        // Добавление водяного знака с помощью GD
        $this->addWatermark($outputPath);
        
        return $outputPath;
    }
    
    /**
     * График 3: Линейный график - Динамика продаж по месяцам
     */
    public function generateLineChart()
    {
        // Получение данных
        $stmt = $this->pdo->query("
            SELECT DATE_FORMAT(sale_date, '%Y-%m') as month, SUM(revenue) as total_revenue 
            FROM sales_statistics 
            GROUP BY month 
            ORDER BY month
        ");
        $data = $stmt->fetchAll();
        
        $months = array_column($data, 'month');
        $revenues = array_column($data, 'total_revenue');
        
        // Создание линейного графика с помощью JpGraph
        $graph = new Graph\Graph(800, 600);
        $graph->SetScale('textlin');
        $graph->title->Set('Sales Dynamics by Month');
        $graph->xaxis->SetTickLabels($months);
        $graph->yaxis->title->Set('Revenue');
        
        // Создание линейного графика
        $lineplot = new Plot\LinePlot($revenues);
        $lineplot->SetColor('red');
        $lineplot->SetWeight(2);
        
        $graph->Add($lineplot);
        
        // Сохранение
        $outputPath = __DIR__ . '/../charts/line_chart.png';
        $graph->Stroke($outputPath);
        
        // Добавление водяного знака с помощью GD
        $this->addWatermark($outputPath);
        
        return $outputPath;
    }
    
    /**
     * Добавление полупрозрачного водяного знака с помощью GD
     */
    private function addWatermark($imagePath)
    {
        // Загружаем существующее изображение
        $image = imagecreatefrompng($imagePath);
        
        if (!$image) {
            return;
        }
        
        // Создание водяного знака
        $watermarkText = 'Practice 6 © 2024';
        $fontSize = 3;
        $textWidth = imagefontwidth($fontSize) * strlen($watermarkText);
        $textHeight = imagefontheight($fontSize);
        
        // Создание временного изображения для водяного знака
        $watermark = imagecreatetruecolor($textWidth + 20, $textHeight + 10);
        $transparent = imagecolorallocatealpha($watermark, 0, 0, 0, 127);
        imagefill($watermark, 0, 0, $transparent);
        
        $textColor = imagecolorallocatealpha($watermark, 128, 128, 128, 50);
        imagestring($watermark, $fontSize, 10, 5, $watermarkText, $textColor);
        
        // Позиция водяного знака (правый нижний угол)
        $destX = imagesx($image) - $textWidth - 30;
        $destY = imagesy($image) - $textHeight - 20;
        
        // Наложение водяного знака с прозрачностью
        imagecopy($image, $watermark, $destX, $destY, 0, 0, 
                 imagesx($watermark), imagesy($watermark));
        
        // Сохранение обратно
        imagepng($image, $imagePath);
        
        imagedestroy($watermark);
        imagedestroy($image);
    }
}
