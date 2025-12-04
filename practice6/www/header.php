<?php
// Определение языка и темы
$theme = $_SESSION['theme'] ?? $_COOKIE['user_theme'] ?? 'light';
$language = $_SESSION['language'] ?? $_COOKIE['user_language'] ?? 'ru';
$user_login = $_SESSION['user_login'] ?? null;

// Переводы
$translations = [
    'ru' => [
        'title' => 'Практика 6 - Статистика и Графики',
        'home' => 'Главная',
        'statistics' => 'Статистика',
        'services' => 'Сервисы',
        'settings' => 'Настройки',
        'login' => 'Войти',
        'logout' => 'Выйти',
        'admin' => 'Админ',
        'welcome' => 'Добро пожаловать'
    ],
    'en' => [
        'title' => 'Practice 6 - Statistics and Charts',
        'home' => 'Home',
        'statistics' => 'Statistics',
        'services' => 'Services',
        'settings' => 'Settings',
        'login' => 'Login',
        'logout' => 'Logout',
        'admin' => 'Admin',
        'welcome' => 'Welcome'
    ],
    'de' => [
        'title' => 'Praxis 6 - Statistik und Diagramme',
        'home' => 'Startseite',
        'statistics' => 'Statistik',
        'services' => 'Dienste',
        'settings' => 'Einstellungen',
        'login' => 'Anmelden',
        'logout' => 'Abmelden',
        'admin' => 'Admin',
        'welcome' => 'Willkommen'
    ]
];

$t = $translations[$language];
?>
<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $t['title'] ?></title>
    <link rel="stylesheet" href="styles.php">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-brand"><?= $t['title'] ?></a>
            <ul class="nav-menu">
                <li><a href="index.php"><?= $t['home'] ?></a></li>
                <li><a href="statistics.php"><?= $t['statistics'] ?></a></li>
                <li><a href="services.php"><?= $t['services'] ?></a></li>
                <?php if ($user_login): ?>
                    <li><a href="settings.php"><?= $t['settings'] ?></a></li>
                    <?php if ($_SESSION['is_admin'] ?? false): ?>
                        <li><a href="admin.php"><?= $t['admin'] ?></a></li>
                    <?php endif; ?>
                    <li><a href="logout.php"><?= $t['logout'] ?> (<?= htmlspecialchars($user_login) ?>)</a></li>
                <?php else: ?>
                    <li><a href="login.php"><?= $t['login'] ?></a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <main class="container">
