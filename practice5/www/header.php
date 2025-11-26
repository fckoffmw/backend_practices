<?php
/**
 * Общий заголовок для всех страниц
 * Включает согласование контента (тема, язык, логин)
 */

require_once __DIR__ . '/config.php';

// Получаем настройки пользователя
$userSettings = getUserSettings();
$theme = $userSettings['theme'];
$lang = $userSettings['language'];
$login = $userSettings['login'];

// Получаем переводы
$t = getTranslations($lang);

// Текущий пользователь
$currentUser = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Practice 5' ?></title>
    <link rel="stylesheet" href="/styles.php?theme=<?= urlencode($theme) ?>">
</head>
<body>
    <header class="header">
        <h1>Practice 5 — <?= $t['welcome'] ?></h1>
        <nav>
            <a href="/index.php"><?= $t['home'] ?></a>
            <a href="/services.php"><?= $t['services'] ?></a>
            <a href="/pdf/index.php"><?= $t['my_files'] ?></a>
            <a href="/settings.php"><?= $t['settings'] ?></a>
            <?php if (isLoggedIn() && isAdmin()): ?>
                <a href="/admin.php"><?= $t['admin_panel'] ?></a>
            <?php endif; ?>
        </nav>
        <div class="user-info">
            <?php if (isLoggedIn()): ?>
                <span><?= htmlspecialchars($currentUser['login']) ?></span>
                <a href="/logout.php" class="btn btn-danger"><?= $t['logout'] ?></a>
            <?php else: ?>
                <span><?= $t['guest'] ?></span>
                <a href="/login.php" class="btn btn-primary"><?= $t['login'] ?></a>
            <?php endif; ?>
        </div>
    </header>
    <main class="container">

