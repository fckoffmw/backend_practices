<?php

namespace App\Services;

/**
 * Laravel сервис для генерации графиков
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class ChartGeneratorService
{
    private string $chartsPath;

    public function __construct()
    {
        $this->chartsPath = public_path('charts/');
        
        // Создаем директорию если не существует
        if (!is_dir($this->chartsPath)) {
            mkdir($this->chartsPath, 0755, true);
        }
    }

    /**
     * Генерация столбчатой диаграммы
     */
    public function generateBarChart(array $data, string $title): string
    {
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);
        
        // Цвета
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $blue = imagecolorallocate($image, 54, 162, 235);
        
        // Заливка фона
        imagefill($image, 0, 0, $white);
        
        // Заголовок
        imagestring($image, 5, ($width - strlen($title) * 10) / 2, 20, $title, $black);
        
        if (empty($data)) {
            imagestring($image, 3, 50, 100, 'No data available', $black);
        } else {
            // Параметры графика
            $chartX = 80;
            $chartY = 80;
            $chartWidth = $width - 160;
            $chartHeight = $height - 160;
            
            // Максимальное значение
            $maxValue = max(array_column($data, 'total_revenue'));
            
            // Ширина столбца
            $barWidth = $chartWidth / count($data) - 10;
            
            // Рисуем столбцы
            $x = $chartX;
            foreach ($data as $item) {
                $barHeight = (int)(($item['total_revenue'] / $maxValue) * $chartHeight);
                $barY = (int)($chartY + $chartHeight - $barHeight);
                
                // Столбец
                imagefilledrectangle($image, (int)$x, $barY, (int)($x + $barWidth), (int)($chartY + $chartHeight), $blue);
                
                // Подпись категории
                imagestring($image, 2, (int)$x, (int)($chartY + $chartHeight + 10), 
                    substr($item['category'], 0, 8), $black);
                
                // Значение
                imagestring($image, 2, (int)$x, $barY - 20, 
                    number_format($item['total_revenue'], 0), $black);
                
                $x += $barWidth + 10;
            }
            
            // Оси
            imageline($image, $chartX, $chartY, $chartX, $chartY + $chartHeight, $black);
            imageline($image, $chartX, $chartY + $chartHeight, $chartX + $chartWidth, $chartY + $chartHeight, $black);
        }
        
        // Водяной знак
        $this->addWatermark($image, $width, $height);
        
        // Сохранение
        $filename = 'bar_chart_' . time() . '.png';
        $filepath = $this->chartsPath . $filename;
        imagepng($image, $filepath);
        imagedestroy($image);
        
        return $filename;
    }

    /**
     * Генерация круговой диаграммы
     */
    public function generatePieChart(array $data, string $title): string
    {
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);
        
        // Цвета
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $colors = [
            imagecolorallocate($image, 255, 99, 132),
            imagecolorallocate($image, 54, 162, 235),
            imagecolorallocate($image, 255, 205, 86),
            imagecolorallocate($image, 75, 192, 192),
            imagecolorallocate($image, 153, 102, 255),
        ];
        
        // Заливка фона
        imagefill($image, 0, 0, $white);
        
        // Заголовок
        imagestring($image, 5, ($width - strlen($title) * 10) / 2, 20, $title, $black);
        
        if (empty($data)) {
            imagestring($image, 3, 50, 100, 'No data available', $black);
        } else {
            // Параметры круга
            $centerX = (int)($width / 2);
            $centerY = (int)($height / 2 + 20);
            $radius = 150;
            
            // Общее количество
            $total = array_sum(array_column($data, 'count'));
            
            // Рисуем сектора
            $startAngle = 0;
            $colorIndex = 0;
            
            foreach ($data as $item) {
                $angle = ($item['count'] / $total) * 360;
                $endAngle = $startAngle + $angle;
                
                // Сектор
                imagefilledarc($image, $centerX, $centerY, $radius * 2, $radius * 2,
                    (int)$startAngle, (int)$endAngle, $colors[$colorIndex % count($colors)], IMG_ARC_PIE);
                
                // Подпись
                $labelAngle = deg2rad($startAngle + $angle / 2);
                $labelX = (int)($centerX + cos($labelAngle) * ($radius + 30));
                $labelY = (int)($centerY + sin($labelAngle) * ($radius + 30));
                
                $label = isset($item['region']) ? $item['region'] : $item['category'];
                imagestring($image, 2, $labelX - 20, $labelY - 10, 
                    $label . ' (' . $item['count'] . ')', $black);
                
                $startAngle = $endAngle;
                $colorIndex++;
            }
        }
        
        // Водяной знак
        $this->addWatermark($image, $width, $height);
        
        // Сохранение
        $filename = 'pie_chart_' . time() . '.png';
        $filepath = $this->chartsPath . $filename;
        imagepng($image, $filepath);
        imagedestroy($image);
        
        return $filename;
    }

    /**
     * Генерация линейного графика
     */
    public function generateLineChart(array $data, string $title): string
    {
        $width = 800;
        $height = 600;
        $image = imagecreatetruecolor($width, $height);
        
        // Цвета
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $red = imagecolorallocate($image, 255, 99, 132);
        
        // Заливка фона
        imagefill($image, 0, 0, $white);
        
        // Заголовок
        imagestring($image, 5, ($width - strlen($title) * 10) / 2, 20, $title, $black);
        
        if (empty($data)) {
            imagestring($image, 3, 50, 100, 'No data available', $black);
        } else {
            // Параметры графика
            $chartX = 80;
            $chartY = 80;
            $chartWidth = $width - 160;
            $chartHeight = $height - 160;
            
            // Максимальное значение
            $maxValue = max(array_column($data, 'total_revenue'));
            
            // Рисуем линию
            $stepX = $chartWidth / (count($data) - 1);
            $prevX = (int)$chartX;
            $prevY = (int)($chartY + $chartHeight - (($data[0]['total_revenue'] / $maxValue) * $chartHeight));
            
            for ($i = 1; $i < count($data); $i++) {
                $x = (int)($chartX + $i * $stepX);
                $y = (int)($chartY + $chartHeight - (($data[$i]['total_revenue'] / $maxValue) * $chartHeight));
                
                // Линия
                imageline($image, $prevX, $prevY, $x, $y, $red);
                
                // Точка
                imagefilledellipse($image, $x, $y, 8, 8, $red);
                
                $prevX = $x;
                $prevY = $y;
            }
            
            // Подписи месяцев
            for ($i = 0; $i < count($data); $i++) {
                $x = (int)($chartX + $i * $stepX);
                $monthLabel = $data[$i]['month'] ?? ($i + 1);
                imagestring($image, 2, $x - 15, (int)($chartY + $chartHeight + 10), 
                    (string)$monthLabel, $black);
            }
            
            // Оси
            imageline($image, $chartX, $chartY, $chartX, $chartY + $chartHeight, $black);
            imageline($image, $chartX, $chartY + $chartHeight, $chartX + $chartWidth, $chartY + $chartHeight, $black);
        }
        
        // Водяной знак
        $this->addWatermark($image, $width, $height);
        
        // Сохранение
        $filename = 'line_chart_' . time() . '.png';
        $filepath = $this->chartsPath . $filename;
        imagepng($image, $filepath);
        imagedestroy($image);
        
        return $filename;
    }

    /**
     * Добавление водяного знака
     */
    private function addWatermark($image, int $width, int $height): void
    {
        $watermarkText = 'Practice 8 - Laravel © 2024';
        $watermarkColor = imagecolorallocatealpha($image, 128, 128, 128, 50);
        
        // Позиция в правом нижнем углу
        $textWidth = strlen($watermarkText) * 8;
        $x = $width - $textWidth - 10;
        $y = $height - 20;
        
        imagestring($image, 3, $x, $y, $watermarkText, $watermarkColor);
    }
}