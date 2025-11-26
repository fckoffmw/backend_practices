<?php
/**
 * Страница авторизации
 * Сессии хранятся в Redis
 */

require_once __DIR__ . '/config.php';

$error = '';
$success = '';

// Обработка формы входа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginInput = trim($_POST['login'] ?? '');
    $passwordInput = trim($_POST['password'] ?? '');
    
    if (empty($loginInput) || empty($passwordInput)) {
        $error = 'Заполните все поля';
    } else {
        // Проверяем пользователя в БД
        $stmt = $conn->prepare("SELECT ID, login, password, is_admin FROM users WHERE login = ?");
        $stmt->bind_param("s", $loginInput);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        if ($user && $user['password'] === $passwordInput) {
            // Успешная авторизация - сохраняем в сессию (Redis)
            $_SESSION['user_id'] = $user['ID'];
            $_SESSION['user_login'] = $user['login'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            // Устанавливаем cookie с логином
            setcookie('user_login', $user['login'], time() + (30 * 24 * 60 * 60), '/');
            
            // Загружаем настройки пользователя и сохраняем в cookies
            $stmt2 = $conn->prepare("SELECT theme, language FROM user_settings WHERE user_id = ?");
            $stmt2->bind_param("i", $user['ID']);
            $stmt2->execute();
            $settings = $stmt2->get_result()->fetch_assoc();
            
            if ($settings) {
                setcookie('user_theme', $settings['theme'], time() + (30 * 24 * 60 * 60), '/');
                setcookie('user_language', $settings['language'], time() + (30 * 24 * 60 * 60), '/');
            }
            
            header('Location: /index.php');
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}

$pageTitle = 'Вход — Practice 5';
require_once __DIR__ . '/header.php';
?>

<div class="login-container">
    <div class="card">
        <h2><?= $t['login'] ?></h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="login"><?= $t['username'] ?></label>
                <input type="text" id="login" name="login" required 
                       value="<?= htmlspecialchars($_POST['login'] ?? '') ?>"
                       placeholder="admin, user1, user2...">
            </div>
            
            <div class="form-group">
                <label for="password"><?= $t['password'] ?></label>
                <input type="password" id="password" name="password" required
                       placeholder="password123">
            </div>
            
            <button type="submit" class="btn btn-primary"><?= $t['login'] ?></button>
        </form>
        
        <p style="margin-top: 1rem; color: var(--text-secondary);">
            <?php if ($lang === 'ru'): ?>
                Тестовые аккаунты: admin, user1, user2, user3, user4<br>
                Пароль для всех: <code>password123</code>
            <?php elseif ($lang === 'en'): ?>
                Test accounts: admin, user1, user2, user3, user4<br>
                Password for all: <code>password123</code>
            <?php else: ?>
                Testkonten: admin, user1, user2, user3, user4<br>
                Passwort für alle: <code>password123</code>
            <?php endif; ?>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

