<?php
// Отключаем глобальный режим выбрасывания исключений (если включён)
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$host = 'db';
$dbname = 'appDB';
$user = 'user';
$pass = 'password';

$maxAttempts = 10;
$attempt = 0;
$conn = null;

while ($attempt < $maxAttempts) {
    try {
        $conn = new mysqli($host, $user, $pass, $dbname);
        if (!$conn->connect_errno) {
            break;
        }
        // если соединение создано, но есть ошибка — логируем
        error_log("DB connect attempt {$attempt} failed: " . $conn->connect_error);
    } catch (mysqli_sql_exception $e) {
        error_log("DB connect attempt {$attempt} exception: " . $e->getMessage());
        $conn = null;
    }
    $attempt++;
    sleep(2);
}

if (!$conn || $conn->connect_errno) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Database connection failed', 'msg' => $conn->connect_error ?? 'connection refused']);
    exit;
}

$conn->set_charset("utf8");
?>
