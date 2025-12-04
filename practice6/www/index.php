<?php
require_once 'config.php';
require_once 'header.php';
?>

<div class="card">
    <h1><?= $t['welcome'] ?></h1>
    <p>Практическая работа 6: Генерация фикстур, построение графиков и добавление водяных знаков.</p>
    
    <h2>Функциональность:</h2>
    <ul>
        <li>Генерация 50+ фикстур с помощью Faker</li>
        <li>Построение 3 типов графиков с помощью GD</li>
        <li>Добавление водяных знаков с помощью GD</li>
        <li>Страница статистики для отображения графиков</li>
    </ul>
    
    <p><a href="statistics.php" class="btn">Перейти к статистике</a></p>
</div>

<div class="card">
    <h2>Пользователи системы</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Администратор</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT ID, login, name, surname, is_admin FROM users");
        while ($row = $stmt->fetch()):
        ?>
        <tr>
            <td><?= $row['ID'] ?></td>
            <td><?= htmlspecialchars($row['login']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['surname']) ?></td>
            <td><?= $row['is_admin'] ? 'Да' : 'Нет' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php require_once 'footer.php'; ?>
