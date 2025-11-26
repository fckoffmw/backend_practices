<?php
/**
 * Скачивание PDF файла из базы данных
 */

require_once __DIR__ . '/../config.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    die('File ID required');
}

$fileId = (int)$_GET['id'];

// Получаем файл из БД
if (isLoggedIn()) {
    if (isAdmin()) {
        // Админ может скачать любой файл
        $stmt = $conn->prepare("SELECT * FROM pdf_files WHERE id = ?");
        $stmt->bind_param("i", $fileId);
    } else {
        // Обычный пользователь - только свои файлы
        $stmt = $conn->prepare("SELECT * FROM pdf_files WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $fileId, $_SESSION['user_id']);
    }
    $stmt->execute();
    $file = $stmt->get_result()->fetch_assoc();
} else {
    http_response_code(401);
    die('Authentication required');
}

if (!$file) {
    http_response_code(404);
    die('File not found');
}

// Отправляем файл
header('Content-Type: ' . $file['mime_type']);
header('Content-Disposition: attachment; filename="' . $file['original_name'] . '"');
header('Content-Length: ' . $file['file_size']);
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');

echo $file['file_data'];
exit;
?>

