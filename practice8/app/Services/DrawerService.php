<?php

namespace App\Services;

/**
 * Laravel сервис для генерации SVG изображений
 * Миграция из Practice 7 с адаптацией под Laravel
 */
class DrawerService
{
    /**
     * Генерация SVG на основе числа
     */
    public function generateSvg(string $number): string
    {
        // Валидация входных данных
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException('Введите корректное число');
        }

        $num = (int) $number;
        
        if ($num < 0) {
            throw new \InvalidArgumentException('Число должно быть положительным');
        }

        if ($num > 999999) {
            throw new \InvalidArgumentException('Число слишком большое (максимум 999999)');
        }

        // Параметры SVG
        $width = 400;
        $height = 300;
        $centerX = $width / 2;
        $centerY = $height / 2;

        // Генерируем цвета на основе числа
        $colors = $this->generateColors($num);
        
        // Создаем SVG
        $svg = $this->createSvgHeader($width, $height);
        
        // Добавляем фон
        $svg .= $this->createBackground($width, $height, $colors['background']);
        
        // Добавляем геометрические фигуры на основе цифр числа
        $digits = str_split((string) $num);
        $svg .= $this->createShapesFromDigits($digits, $centerX, $centerY, $colors);
        
        // Добавляем текст с числом
        $svg .= $this->createText($number, $centerX, $centerY + 100, $colors['text']);
        
        // Добавляем декоративные элементы
        $svg .= $this->createDecorations($num, $width, $height, $colors);
        
        $svg .= '</svg>';

        return $svg;
    }

    /**
     * Генерация цветовой палитры на основе числа
     */
    private function generateColors(int $number): array
    {
        // Используем число для генерации псевдослучайных цветов
        $seed = $number % 360;
        
        return [
            'background' => "hsl({$seed}, 20%, 95%)",
            'primary' => "hsl({$seed}, 70%, 50%)",
            'secondary' => "hsl(" . (($seed + 120) % 360) . ", 60%, 60%)",
            'accent' => "hsl(" . (($seed + 240) % 360) . ", 80%, 40%)",
            'text' => "hsl({$seed}, 50%, 20%)",
        ];
    }

    /**
     * Создание заголовка SVG
     */
    private function createSvgHeader(int $width, int $height): string
    {
        return "<svg width=\"{$width}\" height=\"{$height}\" xmlns=\"http://www.w3.org/2000/svg\">\n";
    }

    /**
     * Создание фона
     */
    private function createBackground(int $width, int $height, string $color): string
    {
        return "  <rect width=\"{$width}\" height=\"{$height}\" fill=\"{$color}\" />\n";
    }

    /**
     * Создание фигур на основе цифр
     */
    private function createShapesFromDigits(array $digits, float $centerX, float $centerY, array $colors): string
    {
        $svg = '';
        $angleStep = 360 / count($digits);
        $radius = 80;

        foreach ($digits as $index => $digit) {
            $angle = deg2rad($index * $angleStep);
            $x = $centerX + cos($angle) * $radius;
            $y = $centerY + sin($angle) * $radius;
            
            $svg .= $this->createShapeForDigit((int) $digit, $x, $y, $colors, $index);
        }

        return $svg;
    }

    /**
     * Создание фигуры для конкретной цифры
     */
    private function createShapeForDigit(int $digit, float $x, float $y, array $colors, int $index): string
    {
        $colorIndex = $index % 3;
        $color = match($colorIndex) {
            0 => $colors['primary'],
            1 => $colors['secondary'],
            2 => $colors['accent'],
        };

        return match($digit) {
            0 => $this->createCircle($x, $y, 15, $color),
            1 => $this->createLine($x, $y - 15, $x, $y + 15, $color),
            2 => $this->createRect($x - 10, $y - 10, 20, 20, $color),
            3 => $this->createTriangle($x, $y, 15, $color),
            4 => $this->createDiamond($x, $y, 12, $color),
            5 => $this->createPentagon($x, $y, 12, $color),
            6 => $this->createHexagon($x, $y, 12, $color),
            7 => $this->createStar($x, $y, 15, $color),
            8 => $this->createOctagon($x, $y, 12, $color),
            9 => $this->createSpiral($x, $y, 15, $color),
        };
    }

    // Методы создания фигур (аналогично Practice 7)
    private function createCircle(float $x, float $y, float $r, string $color): string
    {
        return "  <circle cx=\"{$x}\" cy=\"{$y}\" r=\"{$r}\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createLine(float $x1, float $y1, float $x2, float $y2, string $color): string
    {
        return "  <line x1=\"{$x1}\" y1=\"{$y1}\" x2=\"{$x2}\" y2=\"{$y2}\" stroke=\"{$color}\" stroke-width=\"3\" />\n";
    }

    private function createRect(float $x, float $y, float $width, float $height, string $color): string
    {
        return "  <rect x=\"{$x}\" y=\"{$y}\" width=\"{$width}\" height=\"{$height}\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createTriangle(float $x, float $y, float $size, string $color): string
    {
        $points = sprintf("%.1f,%.1f %.1f,%.1f %.1f,%.1f",
            $x, $y - $size,
            $x - $size, $y + $size,
            $x + $size, $y + $size
        );
        return "  <polygon points=\"{$points}\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createDiamond(float $x, float $y, float $size, string $color): string
    {
        $points = sprintf("%.1f,%.1f %.1f,%.1f %.1f,%.1f %.1f,%.1f",
            $x, $y - $size,
            $x + $size, $y,
            $x, $y + $size,
            $x - $size, $y
        );
        return "  <polygon points=\"{$points}\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createPentagon(float $x, float $y, float $size, string $color): string
    {
        $points = [];
        for ($i = 0; $i < 5; $i++) {
            $angle = deg2rad($i * 72 - 90);
            $px = $x + cos($angle) * $size;
            $py = $y + sin($angle) * $size;
            $points[] = sprintf("%.1f,%.1f", $px, $py);
        }
        return "  <polygon points=\"" . implode(' ', $points) . "\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createHexagon(float $x, float $y, float $size, string $color): string
    {
        $points = [];
        for ($i = 0; $i < 6; $i++) {
            $angle = deg2rad($i * 60);
            $px = $x + cos($angle) * $size;
            $py = $y + sin($angle) * $size;
            $points[] = sprintf("%.1f,%.1f", $px, $py);
        }
        return "  <polygon points=\"" . implode(' ', $points) . "\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createStar(float $x, float $y, float $size, string $color): string
    {
        $points = [];
        for ($i = 0; $i < 10; $i++) {
            $angle = deg2rad($i * 36 - 90);
            $radius = ($i % 2 === 0) ? $size : $size * 0.5;
            $px = $x + cos($angle) * $radius;
            $py = $y + sin($angle) * $radius;
            $points[] = sprintf("%.1f,%.1f", $px, $py);
        }
        return "  <polygon points=\"" . implode(' ', $points) . "\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createOctagon(float $x, float $y, float $size, string $color): string
    {
        $points = [];
        for ($i = 0; $i < 8; $i++) {
            $angle = deg2rad($i * 45);
            $px = $x + cos($angle) * $size;
            $py = $y + sin($angle) * $size;
            $points[] = sprintf("%.1f,%.1f", $px, $py);
        }
        return "  <polygon points=\"" . implode(' ', $points) . "\" fill=\"{$color}\" opacity=\"0.8\" />\n";
    }

    private function createSpiral(float $x, float $y, float $size, string $color): string
    {
        $path = "M {$x} {$y}";
        $steps = 20;
        
        for ($i = 1; $i <= $steps; $i++) {
            $angle = deg2rad($i * 18);
            $radius = ($i / $steps) * $size;
            $px = $x + cos($angle) * $radius;
            $py = $y + sin($angle) * $radius;
            $path .= " L {$px} {$py}";
        }
        
        return "  <path d=\"{$path}\" stroke=\"{$color}\" stroke-width=\"2\" fill=\"none\" opacity=\"0.8\" />\n";
    }

    private function createText(string $text, float $x, float $y, string $color): string
    {
        return "  <text x=\"{$x}\" y=\"{$y}\" text-anchor=\"middle\" font-family=\"Arial, sans-serif\" font-size=\"24\" font-weight=\"bold\" fill=\"{$color}\">{$text}</text>\n";
    }

    private function createDecorations(int $number, int $width, int $height, array $colors): string
    {
        $svg = '';
        
        // Добавляем случайные точки на основе числа
        $pointCount = ($number % 20) + 5;
        
        for ($i = 0; $i < $pointCount; $i++) {
            $x = ($number * 17 + $i * 23) % $width;
            $y = ($number * 13 + $i * 19) % $height;
            $r = ($number + $i) % 5 + 1;
            
            $svg .= "  <circle cx=\"{$x}\" cy=\"{$y}\" r=\"{$r}\" fill=\"{$colors['accent']}\" opacity=\"0.3\" />\n";
        }
        
        return $svg;
    }
}