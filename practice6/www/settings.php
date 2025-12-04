<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $theme = $_POST['theme'] ?? 'light';
    $language = $_POST['language'] ?? 'ru';
    
    // Обновление в БД
    $stmt = $pdo->prepare("
        INSERT INTO user_settings (user_id, theme, language) 
        VALUES (?, ?, ?) 
        ON DUPLICATE KEY UPDATE theme = ?, language = ?
    ");
    $stmt->execute([
        $_SESSION['user_id'], 
        $theme, 
        $language,
        $theme,
        $language
    ]);
    
    // Обновление сессии
    $_SESSION['theme'] = $theme;
    $_SESSION['language'] = $language;
    
    // Обновление cookies
    setcookie('user_theme', $theme, time() + 86400 * 365, '/');
    setcookie('user_language', $language, time() + 86400 * 365, '/');
    
    header('Location: settings.php?saved=1');
    exit;
}

require_once 'header.php';
?>

<div class="card">
    <h1>Настройки</h1>
    
    <?php if (isset($_GET['saved'])): ?>
        <p style="color: green;">Настройки успешно сохранены!</p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Тема оформления:</label>
        <select name="theme">
            <option value="light" <?= ($theme === 'light') ? 'selected' : '' ?>>Светлая</option>
            <option value="dark" <?= ($theme === 'dark') ? 'selected' : '' ?>>Темная</option>
            <option value="colorblind" <?= ($theme === 'colorblind') ? 'selected' : '' ?>>Для дальтоников</option>
        </select>
        
        <label>Язык интерфейса:</label>
        <select name="language">
            <option value="ru" <?= ($language === 'ru') ? 'selected' : '' ?>>Русский</option>
            <option value="en" <?= ($language === 'en') ? 'selected' : '' ?>>English</option>
            <option value="de" <?= ($language === 'de') ? 'selected' : '' ?>>Deutsch</option>
        </select>
        
        <button type="submit" class="btn">Сохранить</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
