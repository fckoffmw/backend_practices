<?php
/**
 * Админ-панель с системными командами
 * Из Practice 2
 */

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/admin_lib.php';
require_once __DIR__ . '/../../header.php';

// Проверка прав администратора
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    echo '<div class="card"><h1>Access Denied</h1><p>Administrator privileges required.</p></div>';
    require_once __DIR__ . '/../../footer.php';
    exit;
}

$output = '';
$selectedCmd = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cmd'])) {
    $selectedCmd = $_POST['cmd'];
    $output = run_command($selectedCmd);
}

$commands = allowed_commands();
?>

<div class="card">
    <h1>Admin Panel - System Commands</h1>
    <p>Execute allowed system commands.</p>
    
    <form method="POST">
        <label>Select Command:</label>
        <select name="cmd" required>
            <option value="">-- Select --</option>
            <?php foreach ($commands as $key => $cmd): ?>
                <option value="<?= $key ?>" <?= $selectedCmd === $key ? 'selected' : '' ?>>
                    <?= htmlspecialchars($key) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Execute</button>
    </form>
    
    <?php if ($output !== ''): ?>
        <h3>Output:</h3>
        <pre style="background: #f5f5f5; padding: 1rem; border-radius: 4px; overflow-x: auto;"><?= htmlspecialchars($output) ?></pre>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../footer.php'; ?>
