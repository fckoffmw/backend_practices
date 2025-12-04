<?php
namespace App;

class ChartGenerator
{
    private $pdo;
    private $watermarkPath;
    
    public function __construct($pdo, $watermarkPath = null)
    {
        $this->pdo = $pdo;
        $this->watermarkPath = $watermarkPath ?: __DIR__ . '/../assets/watermark.png';
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
        
        // Создание изображения
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);
        
        // Цвета
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $blue = imagecolorallocate($image, 70, 130, 180);
        $gray = imagecolorallocate($image, 200, 200, 200);
        
        imagefill($image, 0, 0, $white);
        
        // Заголовок
        $title = 'Revenue by Category';
        $font = 5;
        $titleWidth = imagefontwidth($font) * strlen($title);
        imagestring($image, $font, (int)(($width - $titleWidth) / 2), 20, $title, $black);
        
        // Параметры графика
        $margin = 80;
        $chartWidth = $width - 2 * $margin;
        $chartHeight = $height - 2 * $margin;
        $barWidth = $chartWidth / count($categories) - 10;
        $maxRevenue = max($revenues);
        
        // Рисование осей
        imageline($image, $margin, $height - $margin, $width - $margin, $height - $margin, $black);
        imageline($image, $margin, $margin, $margin, $height - $margin, $black);
        
        // Рисование столбцов
        foreach ($revenues as $i => $revenue) {
            $barHeight = ($revenue / $maxRevenue) * $chartHeight;
            $x = (int)($margin + $i * ($barWidth + 10) + 5);
            $y = (int)($height - $margin - $barHeight);
            
            imagefilledrectangle($image, $x, $y, (int)($x + $barWidth), $height - $margin, $blue);
            
            // Подписи категорий
            $label = substr($categories[$i], 0, 8);
            imagestring($image, 2, $x, $height - $margin + 5, $label, $black);
            
            // Значения
            $value = number_format($revenue, 0, '.', ' ');
            imagestring($image, 2, $x, $y - 15, $value, $black);
        }
        
        // Добавление водяного знака
        $this->addWatermark($image);
        
        // Сохранение
        $outputPath = __DIR__ . '/../charts/bar_chart.png';
        imagepng($image, $outputPath);
        imagedestroy($image);
        
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
        $total = array_sum($counts);
        
        // Создание изображения
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);
        
        // Цвета
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $colors = [
            imagecolorallocate($image, 255, 99, 71),
            imagecolorallocate($image, 70, 130, 180),
            imagecolorallocate($image, 144, 238, 144),
            imagecolorallocate($image, 255, 215, 0),
            imagecolorallocate($image, 221, 160, 221)
        ];
        
        imagefill($image, 0, 0, $white);
        
        // Заголовок
        $title = 'Sales Distribution by Region';
        $font = 5;
        $titleWidth = imagefontwidth($font) * strlen($title);
        imagestring($image, $font, (int)(($width - $titleWidth) / 2), 20, $title, $black);
        
        // Параметры круговой диаграммы
        $centerX = (int)($width / 2 - 100);
        $centerY = (int)($height / 2 + 20);
        $diameter = 300;
        
        // Рисование секторов
        $startAngle = 0;
        foreach ($counts as $i => $count) {
            $angle = ($count / $total) * 360;
            $endAngle = $startAngle + $angle;
            
            imagefilledarc($image, $centerX, $centerY, $diameter, $diameter, 
                          (int)$startAngle, (int)$endAngle, $colors[$i % count($colors)], IMG_ARC_PIE);
            
            $startAngle = $endAngle;
        }
        
        // Легенда
        $legendX = $width - 200;
        $legendY = 100;
        foreach ($regions as $i => $region) {
            $y = $legendY + $i * 30;
            imagefilledrectangle($image, $legendX, $y, $legendX + 20, $y + 20, $colors[$i % count($colors)]);
            $percent = round(($counts[$i] / $total) * 100, 1);
            imagestring($image, 3, $legendX + 30, $y + 5, "$region ($percent%)", $black);
        }
        
        // Добавление водяного знака
        $this->addWatermark($image);
        
        // Сохранение
        $outputPath = __DIR__ . '/../charts/pie_chart.png';
        imagepng($image, $outputPath);
        imagedestroy($image);
        
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
        
        // Создание изображения
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);
        
        // Цвета
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $red = imagecolorallocate($image, 220, 20, 60);
        $gray = imagecolorallocate($image, 200, 200, 200);
        
        imagefill($image, 0, 0, $white);
        
        // Заголовок
        $title = 'Sales Dynamics by Month';
        $font = 5;
        $titleWidth = imagefontwidth($font) * strlen($title);
        imagestring($image, $font, (int)(($width - $titleWidth) / 2), 20, $title, $black);
        
        // Параметры графика
        $margin = 80;
        $chartWidth = $width - 2 * $margin;
        $chartHeight = $height - 2 * $margin;
        $maxRevenue = max($revenues);
        $stepX = $chartWidth / (count($months) - 1);
        
        // Рисование осей
        imageline($image, $margin, $height - $margin, $width - $margin, $height - $margin, $black);
        imageline($image, $margin, $margin, $margin, $height - $margin, $black);
        
        // Рисование сетки
        for ($i = 0; $i <= 5; $i++) {
            $y = (int)($margin + ($chartHeight / 5) * $i);
            imageline($image, $margin, $y, $width - $margin, $y, $gray);
        }
        
        // Рисование линии и точек
        $points = [];
        foreach ($revenues as $i => $revenue) {
            $x = (int)($margin + $i * $stepX);
            $y = (int)($height - $margin - ($revenue / $maxRevenue) * $chartHeight);
            $points[] = ['x' => $x, 'y' => $y];
            
            // Точка
            imagefilledellipse($image, $x, $y, 8, 8, $red);
            
            // Подпись месяца
            $label = substr($months[$i], 5);
            imagestring($image, 2, $x - 10, $height - $margin + 5, $label, $black);
        }
        
        // Соединение точек линиями
        for ($i = 0; $i < count($points) - 1; $i++) {
            imageline($image, (int)$points[$i]['x'], (int)$points[$i]['y'], 
                     (int)$points[$i + 1]['x'], (int)$points[$i + 1]['y'], $red);
        }
        
        // Добавление водяного знака
        $this->addWatermark($image);
        
        // Сохранение
        $outputPath = __DIR__ . '/../charts/line_chart.png';
        imagepng($image, $outputPath);
        imagedestroy($image);
        
        return $outputPath;
    }
    
    /**
     * Добавление полупрозрачного водяного знака
     */
    private function addWatermark($image)
    {
        // Создание водяного знака из текста
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
        
        imagedestroy($watermark);
    }
}
