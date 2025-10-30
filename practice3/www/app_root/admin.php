<?php
require_once 'config.php';

header('Content-Type: text/html; charset=utf-8');

// Проверка HTTP Basic Auth
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Auth required";
    exit;
}

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$stmt = $conn->prepare("SELECT password_hash FROM admin_users WHERE username=?");
$stmt->bind_param("s", $user);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res || !password_verify($pass, $res['password_hash'])) {
    header('HTTP/1.0 401 Unauthorized');
    echo "Invalid credentials";
    exit;
}

echo "<h1>Admin area</h1><p>Welcome, {$user}</p>";
?>
