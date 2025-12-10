<h1><?= htmlspecialchars($title) ?></h1>

<p>Всего пользователей: <strong><?= count($users) ?></strong></p>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Роль</th>
            <th>Тема</th>
            <th>Язык</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $userItem): ?>
        <tr>
            <td><?= $userItem->getId() ?></td>
            <td><?= htmlspecialchars($userItem->getLogin()) ?></td>
            <td><?= htmlspecialchars($userItem->getName()) ?></td>
            <td><?= htmlspecialchars($userItem->getSurname()) ?></td>
            <td>
                <span style="padding: 2px 8px; border-radius: 4px; font-size: 12px; 
                     background: <?= $userItem->getRole() === 'admin' ? '#dc3545' : '#28a745' ?>; 
                     color: white;">
                    <?= htmlspecialchars($userItem->getRole()) ?>
                </span>
            </td>
            <td><?= htmlspecialchars($userItem->getTheme()) ?></td>
            <td><?= htmlspecialchars($userItem->getLanguage()) ?></td>
            <td>
                <a href="/users/<?= $userItem->getId() ?>" class="btn btn-secondary" style="font-size: 12px; padding: 4px 8px;">
                    Просмотр
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if (empty($users)): ?>
<div style="text-align: center; padding: 40px;">
    <p>Пользователи не найдены.</p>
</div>
<?php endif; ?>