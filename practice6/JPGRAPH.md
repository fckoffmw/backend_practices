# JpGraph — Сторонний модуль для построения графиков

## Что такое JpGraph?

**JpGraph** — это профессиональная PHP-библиотека для создания графиков и диаграмм. Это **сторонний модуль**, который устанавливается через Composer.

### Почему JpGraph, а не GD?

- **GD** — встроенное расширение PHP (не сторонний модуль)
- **JpGraph** — сторонняя библиотека (соответствует требованиям задания)

## Установка

### Через Composer:
```bash
composer require amenadiel/jpgraph
```

### В composer.json:
```json
{
    "require": {
        "amenadiel/jpgraph": "^4.0"
    }
}
```

## Использование в Practice 6

### 1. Столбчатая диаграмма (Bar Chart)
```php
use Amenadiel\JpGraph\Graph;
use Amenadiel\JpGraph\Plot;

$graph = new Graph\Graph(800, 600);
$graph->SetScale('textlin');
$graph->title->Set('Revenue by Category');

$barplot = new Plot\BarPlot($revenues);
$barplot->SetFillColor('steelblue');
$barplot->value->Show();

$graph->Add($barplot);
$graph->Stroke('bar_chart.png');
```

### 2. Круговая диаграмма (Pie Chart)
```php
$graph = new Graph\PieGraph(800, 600);
$graph->title->Set('Sales Distribution by Region');

$pieplot = new Plot\PiePlot($counts);
$pieplot->SetLegends($regions);
$pieplot->value->Show();

$graph->Add($pieplot);
$graph->Stroke('pie_chart.png');
```

### 3. Линейный график (Line Chart)
```php
$graph = new Graph\Graph(800, 600);
$graph->SetScale('textlin');
$graph->title->Set('Sales Dynamics by Month');

$lineplot = new Plot\LinePlot($revenues);
$lineplot->SetColor('red');
$lineplot->SetWeight(2);

$graph->Add($lineplot);
$graph->Stroke('line_chart.png');
```

## Преимущества JpGraph

1. **Сторонний модуль** — соответствует требованиям задания
2. **Профессиональные графики** — красивые и настраиваемые
3. **Множество типов** — bar, pie, line, scatter, radar и др.
4. **Легенды и подписи** — автоматическое форматирование
5. **Экспорт в PNG/JPEG** — готовые изображения

## Водяные знаки

После создания графика с помощью JpGraph, мы добавляем водяной знак с помощью GD:

```php
private function addWatermark($imagePath)
{
    $image = imagecreatefrompng($imagePath);
    // ... добавление водяного знака с помощью GD
    imagepng($image, $imagePath);
    imagedestroy($image);
}
```

## Итого

- **Генерация графиков:** JpGraph (сторонний модуль) ✅
- **Водяные знаки:** GD (встроенное расширение) ✅
- **Генерация фикстур:** Faker (сторонний модуль) ✅

Все требования задания выполнены!
