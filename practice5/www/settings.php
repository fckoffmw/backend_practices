<?php
/**
 * Страница настроек пользователя
 * Согласование контента: тема, язык
 */

require_once __DIR__ . '/config.php';

$success = '';
$error = '';

// Обработка формы сохранения настроек
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTheme = $_POST['theme'] ?? 'light';
    $newLanguage = $_POST['language'] ?? 'ru';
    
    // Валидация
    $allowedThemes = ['light', 'dark', 'colorblind'];
    $allowedLanguages = ['ru', 'en', 'de'];
    
    if (!in_array($newTheme, $allowedThemes)) {
        $newTheme = 'light';
    }
    if (!in_array($newLanguage, $allowedLanguages)) {
        $newLanguage = 'ru';
    }
    
    // Сохраняем настройки
    if (saveUserSettings($newTheme, $newLanguage)) {
        $success = true;
        // Перезагружаем страницу для применения настроек
        header("Location: /settings.php?saved=1");
        exit;
    } else {
        $error = 'Ошибка сохранения настроек';
    }
}

if (isset($_GET['saved'])) {
    $success = true;
}

$pageTitle = 'Настройки — Practice 5';
require_once __DIR__ . '/header.php';
?>

<div class="card settings-form">
    <h2><?= $t['settings'] ?></h2>
    
    <?php if ($success): ?>
        <div class="alert alert-success">
            <?php if ($lang === 'ru'): ?>
                Настройки успешно сохранены!
            <?php elseif ($lang === 'en'): ?>
                Settings saved successfully!
            <?php else: ?>
                Einstellungen erfolgreich gespeichert!
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="theme"><?= $t['theme'] ?></label>
            <select id="theme" name="theme">
                <option value="light" <?= $theme === 'light' ? 'selected' : '' ?>>
                    ☀️ <?= $t['light'] ?>
                </option>
                <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>
                    🌙 <?= $t['dark'] ?>
                </option>
                <option value="colorblind" <?= $theme === 'colorblind' ? 'selected' : '' ?>>
                    👁️ <?= $t['colorblind'] ?>
                </option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="language"><?= $t['language'] ?></label>
            <select id="language" name="language">
                <option value="ru" <?= $lang === 'ru' ? 'selected' : '' ?>>
                    🇷🇺 Русский
                </option>
                <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>
                    🇬🇧 English
                </option>
                <option value="de" <?= $lang === 'de' ? 'selected' : '' ?>>
                    🇩🇪 Deutsch
                </option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary"><?= $t['save'] ?></button>
    </form>
</div>

<div class="card">
    <h2>
        <?php if ($lang === 'ru'): ?>
            Информация о хранении данных
        <?php elseif ($lang === 'en'): ?>
            Data Storage Information
        <?php else: ?>
            Informationen zur Datenspeicherung
        <?php endif; ?>
    </h2>
    
    <table>
        <tr>
            <th>
                <?php if ($lang === 'ru'): ?>Тип данных<?php elseif ($lang === 'en'): ?>Data Type<?php else: ?>Datentyp<?php endif; ?>
            </th>
            <th>
                <?php if ($lang === 'ru'): ?>Хранилище<?php elseif ($lang === 'en'): ?>Storage<?php else: ?>Speicher<?php endif; ?>
            </th>
        </tr>
        <tr>
            <td>Session ID</td>
            <td>Redis (tcp://redis:6379)</td>
        </tr>
        <tr>
            <td>
                <?php if ($lang === 'ru'): ?>Настройки (для гостей)<?php elseif ($lang === 'en'): ?>Settings (for guests)<?php else: ?>Einstellungen (für Gäste)<?php endif; ?>
            </td>
            <td>Cookies (30 days)</td>
        </tr>
        <tr>
            <td>
                <?php if ($lang === 'ru'): ?>Настройки (авторизованные)<?php elseif ($lang === 'en'): ?>Settings (logged in)<?php else: ?>Einstellungen (angemeldet)<?php endif; ?>
            </td>
            <td>MySQL (user_settings) + Cookies</td>
        </tr>
        <tr>
            <td>PDF files</td>
            <td>MySQL (LONGBLOB)</td>
        </tr>
    </table>
    
    <?php if ($redis): ?>
        <p style="margin-top: 1rem; color: var(--success);">
            ✅ Redis: 
            <?php if ($lang === 'ru'): ?>подключен<?php elseif ($lang === 'en'): ?>connected<?php else: ?>verbunden<?php endif; ?>
        </p>
    <?php else: ?>
        <p style="margin-top: 1rem; color: var(--error);">
            ❌ Redis: 
            <?php if ($lang === 'ru'): ?>не подключен<?php elseif ($lang === 'en'): ?>not connected<?php else: ?>nicht verbunden<?php endif; ?>
        </p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

