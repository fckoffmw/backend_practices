<h1><?= htmlspecialchars($title) ?></h1>

<!-- Общая статистика -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value"><?= number_format($stats['total_sales']) ?></div>
        <div class="stat-label">Всего продаж</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= number_format($stats['total_revenue'], 2) ?> ₽</div>
        <div class="stat-label">Общая выручка</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= number_format($stats['average_order_value'], 2) ?> ₽</div>
        <div class="stat-label">Средний чек</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?= $stats['categories_count'] ?></div>
        <div class="stat-label">Категорий</div>
    </div>
</div>

<!-- Графики -->
<h2>Графики</h2>
<div class="charts-grid">
    <div class="chart-item">
        <h3>Выручка по категориям</h3>
        <img src="/charts/<?= $charts['bar'] ?>" alt="Столбчатая диаграмма">
    </div>
    
    <div class="chart-item">
        <h3>Продажи по регионам</h3>
        <img src="/charts/<?= $charts['pie'] ?>" alt="Круговая диаграмма">
    </div>
    
    <div class="chart-item">
        <h3>Динамика продаж</h3>
        <img src="/charts/<?= $charts['line'] ?>" alt="Линейный график">
    </div>
</div>

<!-- Данные в таблицах -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 20px; margin: 30px 0;">
    
    <!-- Выручка по категориям -->
    <div>
        <h3>Выручка по категориям</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Категория</th>
                    <th>Выручка</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['categories'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td><?= number_format($item['total_revenue'], 2) ?> ₽</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Продажи по регионам -->
    <div>
        <h3>Продажи по регионам</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Регион</th>
                    <th>Продажи</th>
                    <th>Выручка</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['regions'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['region']) ?></td>
                    <td><?= number_format($item['sales_count']) ?></td>
                    <td><?= number_format($item['total_revenue'], 2) ?> ₽</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
</div>

<!-- Динамика по месяцам -->
<h3>Динамика продаж по месяцам</h3>
<table class="table">
    <thead>
        <tr>
            <th>Месяц</th>
            <th>Количество продаж</th>
            <th>Выручка</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($data['months'] as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['month']) ?></td>
            <td><?= number_format($item['sales_count']) ?></td>
            <td><?= number_format($item['total_revenue'], 2) ?> ₽</td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Управление данными -->
<div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
    <h3>Управление данными</h3>
    <p>Перегенерировать тестовые данные для статистики:</p>
    
    <form method="POST" action="/statistics/generate-fixtures" style="display: inline-block;">
        <input type="number" name="count" value="50" min="10" max="1000" 
               style="width: 80px; padding: 5px; margin-right: 10px;">
        <button type="submit" class="btn btn-secondary">Сгенерировать данные</button>
    </form>
    
    <p style="margin-top: 10px; font-size: 14px; color: #666;">
        <strong>Внимание:</strong> Это действие удалит все существующие данные статистики.
    </p>
</div>