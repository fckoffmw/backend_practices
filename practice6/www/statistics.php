<?php
require_once 'config.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once 'header.php';

use App\ChartGenerator;

// Проверка наличия фикстур
$stmt = $pdo->query("SELECT COUNT(*) as count FROM sales_statistics");
$fixturesCount = $stmt->fetch()['count'];

if ($fixturesCount == 0) {
    echo '<div class="card">';
    echo '<h1>Статистика продаж</h1>';
    echo '<p>Фикстуры еще не сгенерированы. <a href="generate_fixtures.php" class="btn">Сгенерировать фикстуры</a></p>';
    echo '</div>';
    require_once 'footer.php';
    exit;
}

// Создание директории для графиков
$chartsDir = __DIR__ . '/charts';
if (!is_dir($chartsDir)) {
    mkdir($chartsDir, 0755, true);
}

// Генерация графиков
$generator = new ChartGenerator($pdo);

try {
    $barChart = $generator->generateBarChart();
    $pieChart = $generator->generatePieChart();
    $lineChart = $generator->generateLineChart();
    $chartsGenerated = true;
} catch (Exception $e) {
    $chartsGenerated = false;
    $error = $e->getMessage();
}
?>

<div class="card">
    <h1>Статистика продаж</h1>
    <p>Всего записей в базе данных: <strong><?= $fixturesCount ?></strong></p>
    <p><a href="generate_fixtures.php" class="btn">Перегенерировать фикстуры</a></p>
</div>

<?php if ($chartsGenerated): ?>

<div class="card">
    <h2>График 1: Выручка по категориям (Столбчатая диаграмма)</h2>
    <p>Данный график показывает общую выручку по каждой категории товаров.</p>
    <div class="chart-container">
        <img src="charts/bar_chart.png?t=<?= time() ?>" alt="Bar Chart">
    </div>
</div>

<div class="card">
    <h2>График 2: Распределение продаж по регионам (Круговая диаграмма)</h2>
    <p>Данный график показывает процентное распределение количества продаж по регионам.</p>
    <div class="chart-container">
        <img src="charts/pie_chart.png?t=<?= time() ?>" alt="Pie Chart">
    </div>
</div>

<div class="card">
    <h2>График 3: Динамика продаж по месяцам (Линейный график)</h2>
    <p>Данный график показывает изменение выручки во времени по месяцам.</p>
    <div class="chart-container">
        <img src="charts/line_chart.png?t=<?= time() ?>" alt="Line Chart">
    </div>
</div>

<?php else: ?>
<div class="card">
    <h2>Ошибка генерации графиков</h2>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
</div>
<?php endif; ?>

<div class="card">
    <h2>Таблица данных (первые 10 записей)</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Товар</th>
            <th>Категория</th>
            <th>Цена</th>
            <th>Количество</th>
            <th>Выручка</th>
            <th>Дата</th>
            <th>Регион</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT * FROM sales_statistics ORDER BY id LIMIT 10");
        while ($row = $stmt->fetch()):
        ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['product_name']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= number_format($row['price'], 2) ?> ₽</td>
            <td><?= $row['quantity'] ?></td>
            <td><?= number_format($row['revenue'], 2) ?> ₽</td>
            <td><?= $row['sale_date'] ?></td>
            <td><?= htmlspecialchars($row['region']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>
