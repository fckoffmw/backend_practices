<?php
/**
 * Выход из системы
 * Удаление сессии из Redis
 */

require_once __DIR__ . '/config.php';

// Очищаем сессию
$_SESSION = [];

// Удаляем cookie сессии
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Уничтожаем сессию
session_destroy();

// Перенаправляем на главную
header('Location: /index.php');
exit;
?>

