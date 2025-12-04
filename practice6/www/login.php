<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch();
    
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        // Загрузка настроек пользователя
        $stmt = $pdo->prepare("SELECT theme, language FROM user_settings WHERE user_id = ?");
        $stmt->execute([$user['ID']]);
        $settings = $stmt->fetch();
        
        if ($settings) {
            $_SESSION['theme'] = $settings['theme'];
            $_SESSION['language'] = $settings['language'];
        }
        
        header('Location: index.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}

require_once 'header.php';
?>

<div class="card">
    <h1>Вход в систему</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label>Логин:</label>
        <input type="text" name="login" required>
        
        <label>Пароль:</label>
        <input type="password" name="password" required>
        
        <button type="submit" class="btn">Войти</button>
    </form>
    
    <p>Тестовые учетные записи: admin/password123, user1/password123</p>
</div>

<?php require_once 'footer.php'; ?>
