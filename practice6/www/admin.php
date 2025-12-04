<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header('Location: index.php');
    exit;
}

require_once 'header.php';
?>

<div class="card">
    <h1>Панель администратора</h1>
    
    <h2>Статистика системы</h2>
    <?php
    $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $salesCount = $pdo->query("SELECT COUNT(*) FROM sales_statistics")->fetchColumn();
    ?>
    
    <ul>
        <li>Пользователей: <?= $usersCount ?></li>
        <li>Заказов: <?= $ordersCount ?></li>
        <li>Записей статистики: <?= $salesCount ?></li>
    </ul>
    
    <p><a href="generate_fixtures.php" class="btn">Перегенерировать фикстуры</a></p>
</div>

<?php require_once 'footer.php'; ?>
