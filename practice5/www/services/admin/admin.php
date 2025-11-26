<?php
/**
 * Админ-панель с системными командами
 * Из Practice 2, с добавлением авторизации
 */

require_once __DIR__ . '/admin_lib.php';
require_once __DIR__ . '/../../config.php';

$userSettings = getUserSettings();
$theme = $userSettings['theme'];
$lang = $userSettings['language'];
$t = getTranslations($lang);

$cmd = $_GET['cmd'] ?? null;
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= $t['admin_panel'] ?> — Practice 5</title>
    <link rel="stylesheet" href="/styles.php?theme=<?= urlencode($theme) ?>">
</head>
<body>
    <header class="header">
        <h1><?= $t['admin_panel'] ?></h1>
        <nav>
            <a href="/index.php"><?= $t['home'] ?></a>
            <a href="/services.php"><?= $t['services'] ?></a>
        </nav>
    </header>
    <main class="container">
        <div class="card">
            <h2>
                <?php if ($lang === 'ru'): ?>
                    Системные команды
                <?php elseif ($lang === 'en'): ?>
                    System Commands
                <?php else: ?>
                    Systembefehle
                <?php endif; ?>
            </h2>
            <div class="services-grid">
                <?php foreach (allowed_commands() as $k => $c): ?>
                    <a href="?cmd=<?= $k ?>" class="service-card">
                        <h3><?= htmlspecialchars($k) ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($cmd): ?>
        <div class="card">
            <h2>
                <?php if ($lang === 'ru'): ?>
                    Результат команды
                <?php elseif ($lang === 'en'): ?>
                    Command Result
                <?php else: ?>
                    Befehlsergebnis
                <?php endif; ?>
                "<?= htmlspecialchars($cmd) ?>"
            </h2>
            <pre style="background: var(--bg); padding: 1rem; overflow-x: auto; border-radius: 4px;"><?= htmlspecialchars(run_command($cmd)) ?></pre>
        </div>
        <?php endif; ?>
    </main>
</body>
</html>

