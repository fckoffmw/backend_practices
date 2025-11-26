<?php
/**
 * Админ-панель (только для администраторов)
 * Управление пользователями, файлами, просмотр сессий Redis
 */

require_once __DIR__ . '/config.php';

// Проверка прав администратора
if (!isLoggedIn()) {
    header('Location: /login.php');
    exit;
}

if (!isAdmin()) {
    http_response_code(403);
    $pageTitle = 'Доступ запрещён';
    require_once __DIR__ . '/header.php';
    echo '<div class="card"><h2>403 — Доступ запрещён</h2><p>Эта страница доступна только администраторам.</p></div>';
    require_once __DIR__ . '/footer.php';
    exit;
}

// Получаем статистику
$usersCount = $conn->query("SELECT COUNT(*) as cnt FROM users")->fetch_assoc()['cnt'];
$ordersCount = $conn->query("SELECT COUNT(*) as cnt FROM orders")->fetch_assoc()['cnt'];
$filesCount = $conn->query("SELECT COUNT(*) as cnt FROM pdf_files")->fetch_assoc()['cnt'];
$totalFileSize = $conn->query("SELECT SUM(file_size) as total FROM pdf_files")->fetch_assoc()['total'] ?? 0;

// Получаем все сессии из Redis (если доступно)
$activeSessions = [];
if ($redis) {
    try {
        $keys = $redis->keys('PHPREDIS_SESSION:*');
        foreach ($keys as $key) {
            $sessionId = str_replace('PHPREDIS_SESSION:', '', $key);
            $ttl = $redis->ttl($key);
            $data = $redis->get($key);
            $activeSessions[] = [
                'id' => $sessionId,
                'ttl' => $ttl,
                'size' => strlen($data)
            ];
        }
    } catch (Exception $e) {
        // Redis может быть недоступен
    }
}

$pageTitle = 'Админ панель — Practice 5';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>
        <?php if ($lang === 'ru'): ?>
            Панель администратора
        <?php elseif ($lang === 'en'): ?>
            Administrator Panel
        <?php else: ?>
            Administratorbereich
        <?php endif; ?>
    </h2>
    <p>
        <?php if ($lang === 'ru'): ?>
            Добро пожаловать, <?= htmlspecialchars($currentUser['login']) ?>! Вы вошли как администратор.
        <?php elseif ($lang === 'en'): ?>
            Welcome, <?= htmlspecialchars($currentUser['login']) ?>! You are logged in as administrator.
        <?php else: ?>
            Willkommen, <?= htmlspecialchars($currentUser['login']) ?>! Sie sind als Administrator angemeldet.
        <?php endif; ?>
    </p>
</div>

<div class="grid">
    <div class="card">
        <h2>📊 
            <?php if ($lang === 'ru'): ?>Статистика<?php elseif ($lang === 'en'): ?>Statistics<?php else: ?>Statistiken<?php endif; ?>
        </h2>
        <table>
            <tr>
                <td><?= $t['users'] ?></td>
                <td><strong><?= $usersCount ?></strong></td>
            </tr>
            <tr>
                <td><?= $t['orders'] ?></td>
                <td><strong><?= $ordersCount ?></strong></td>
            </tr>
            <tr>
                <td>PDF 
                    <?php if ($lang === 'ru'): ?>файлов<?php else: ?>files<?php endif; ?>
                </td>
                <td><strong><?= $filesCount ?></strong></td>
            </tr>
            <tr>
                <td>
                    <?php if ($lang === 'ru'): ?>Размер файлов<?php elseif ($lang === 'en'): ?>Files size<?php else: ?>Dateigröße<?php endif; ?>
                </td>
                <td><strong><?= number_format($totalFileSize / 1024, 1) ?> KB</strong></td>
            </tr>
            <tr>
                <td>
                    <?php if ($lang === 'ru'): ?>Активных сессий<?php elseif ($lang === 'en'): ?>Active sessions<?php else: ?>Aktive Sitzungen<?php endif; ?>
                </td>
                <td><strong><?= count($activeSessions) ?></strong></td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h2>🔴 Redis 
            <?php if ($lang === 'ru'): ?>Сессии<?php elseif ($lang === 'en'): ?>Sessions<?php else: ?>Sitzungen<?php endif; ?>
        </h2>
        <?php if ($redis): ?>
            <p style="color: var(--success);">✅ Redis 
                <?php if ($lang === 'ru'): ?>подключен<?php elseif ($lang === 'en'): ?>connected<?php else: ?>verbunden<?php endif; ?>
            </p>
            <?php if (empty($activeSessions)): ?>
                <p>
                    <?php if ($lang === 'ru'): ?>Нет активных сессий<?php else: ?>No active sessions<?php endif; ?>
                </p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Session ID</th>
                        <th>TTL (sec)</th>
                        <th>Size</th>
                    </tr>
                    <?php foreach ($activeSessions as $sess): ?>
                    <tr>
                        <td><code><?= substr($sess['id'], 0, 20) ?>...</code></td>
                        <td><?= $sess['ttl'] ?></td>
                        <td><?= $sess['size'] ?> bytes</td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        <?php else: ?>
            <p style="color: var(--error);">❌ Redis 
                <?php if ($lang === 'ru'): ?>не подключен<?php else: ?>not connected<?php endif; ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <h2>👥 
        <?php if ($lang === 'ru'): ?>Все пользователи<?php elseif ($lang === 'en'): ?>All Users<?php else: ?>Alle Benutzer<?php endif; ?>
    </h2>
    <?php
    $users = $conn->query("
        SELECT u.*, us.theme, us.language 
        FROM users u 
        LEFT JOIN user_settings us ON u.ID = us.user_id 
        ORDER BY u.ID
    ")->fetch_all(MYSQLI_ASSOC);
    ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Login</th>
            <th><?= $t['name'] ?></th>
            <th><?= $t['surname'] ?></th>
            <th>Admin</th>
            <th><?= $t['theme'] ?></th>
            <th><?= $t['language'] ?></th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['ID'] ?></td>
            <td><strong><?= htmlspecialchars($user['login']) ?></strong></td>
            <td><?= htmlspecialchars($user['name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($user['surname'] ?? '-') ?></td>
            <td><?= $user['is_admin'] ? '✅' : '❌' ?></td>
            <td><?= $user['theme'] ?? 'light' ?></td>
            <td><?= strtoupper($user['language'] ?? 'ru') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="card">
    <h2>📦 
        <?php if ($lang === 'ru'): ?>Все заказы<?php elseif ($lang === 'en'): ?>All Orders<?php else: ?>Alle Bestellungen<?php endif; ?>
    </h2>
    <?php
    $orders = $conn->query("
        SELECT o.*, u.login as user_login 
        FROM orders o 
        LEFT JOIN users u ON o.user_id = u.ID 
        ORDER BY o.created_at DESC
    ")->fetch_all(MYSQLI_ASSOC);
    ?>
    <table>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Product</th>
            <th>Amount</th>
            <th><?= $t['date'] ?></th>
        </tr>
        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['order_id'] ?></td>
            <td><?= htmlspecialchars($order['user_login']) ?></td>
            <td><?= htmlspecialchars($order['product']) ?></td>
            <td><?= number_format($order['amount'], 2) ?></td>
            <td><?= $order['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="card">
    <h2>🔧 
        <?php if ($lang === 'ru'): ?>Системные команды<?php else: ?>System Commands<?php endif; ?>
    </h2>
    <p>
        <a href="/services/admin/admin.php" class="btn btn-primary">
            <?php if ($lang === 'ru'): ?>Открыть<?php else: ?>Open<?php endif; ?>
        </a>
    </p>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

