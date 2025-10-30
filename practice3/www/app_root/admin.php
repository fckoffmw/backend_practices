<?php
require_once 'config.php';
header('Content-Type: text/html; charset=utf-8');

// Получаем имя пользователя, установленное Apache (или из PHP_AUTH если доступно)
$user = $_SERVER['REMOTE_USER'] ?? $_SERVER['PHP_AUTH_USER'] ?? null;

if (!$user) {
    // Обычно Apache сам запрашивает авторизацию; на всякий случай запрашиваем её из PHP
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Auth required";
    exit;
}

// Проверяем, что пользователь есть в БД и является админом
$stmt = $conn->prepare("SELECT is_admin FROM users WHERE login = ? LIMIT 1");
if ($stmt === false) {
    http_response_code(500);
    echo "Database error (prepare failed)";
    exit;
}
$stmt->bind_param("s", $user);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row || empty($row['is_admin'])) {
    header('HTTP/1.0 403 Forbidden');
    echo "Forbidden";
    exit;
}

echo "<h1>Admin area</h1><p>Welcome, " . htmlspecialchars($user, ENT_QUOTES, 'UTF-8') . "</p>";
?>
