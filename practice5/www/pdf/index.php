<?php
/**
 * Модуль загрузки и выдачи PDF файлов
 * Файлы хранятся в MySQL (LONGBLOB)
 */

require_once __DIR__ . '/../config.php';

$success = '';
$error = '';

// Обработка загрузки файла
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    if (!isLoggedIn()) {
        $error = 'Необходима авторизация для загрузки файлов';
    } else {
        $file = $_FILES['pdf_file'];
        
        // Проверка ошибок загрузки
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $error = 'Ошибка при загрузке файла (код: ' . $file['error'] . ')';
        } 
        // Проверка типа файла
        elseif ($file['type'] !== 'application/pdf' && !str_ends_with(strtolower($file['name']), '.pdf')) {
            $error = 'Разрешены только PDF файлы';
        }
        // Проверка размера (макс. 50MB)
        elseif ($file['size'] > 50 * 1024 * 1024) {
            $error = 'Максимальный размер файла: 50MB';
        }
        else {
            // Читаем содержимое файла
            $fileData = file_get_contents($file['tmp_name']);
            $fileName = uniqid('pdf_') . '.pdf';
            $originalName = $file['name'];
            $mimeType = 'application/pdf';
            $fileSize = $file['size'];
            $userId = $_SESSION['user_id'];
            
            // Сохраняем в БД
            $stmt = $conn->prepare("
                INSERT INTO pdf_files (user_id, filename, original_name, mime_type, file_size, file_data) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $null = null;
            $stmt->bind_param("isssis", $userId, $fileName, $originalName, $mimeType, $fileSize, $null);
            $stmt->send_long_data(5, $fileData);
            
            if ($stmt->execute()) {
                $success = 'Файл успешно загружен!';
            } else {
                $error = 'Ошибка сохранения файла в БД: ' . $conn->error;
            }
        }
    }
}

// Обработка удаления файла
if (isset($_GET['delete']) && isLoggedIn()) {
    $fileId = (int)$_GET['delete'];
    
    // Проверяем права (только свои файлы или админ)
    $stmt = $conn->prepare("SELECT user_id FROM pdf_files WHERE id = ?");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $fileInfo = $stmt->get_result()->fetch_assoc();
    
    if ($fileInfo && ($fileInfo['user_id'] === $_SESSION['user_id'] || isAdmin())) {
        $stmt = $conn->prepare("DELETE FROM pdf_files WHERE id = ?");
        $stmt->bind_param("i", $fileId);
        if ($stmt->execute()) {
            $success = 'Файл удалён';
        }
    }
}

// Получаем список файлов
if (isLoggedIn()) {
    if (isAdmin()) {
        // Админ видит все файлы
        $result = $conn->query("
            SELECT pf.*, u.login as owner_login 
            FROM pdf_files pf 
            LEFT JOIN users u ON pf.user_id = u.ID 
            ORDER BY pf.uploaded_at DESC
        ");
    } else {
        // Обычный пользователь видит только свои
        $stmt = $conn->prepare("
            SELECT pf.*, u.login as owner_login 
            FROM pdf_files pf 
            LEFT JOIN users u ON pf.user_id = u.ID 
            WHERE pf.user_id = ? 
            ORDER BY pf.uploaded_at DESC
        ");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
    }
    $files = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $files = [];
}

$pageTitle = 'PDF файлы — Practice 5';
require_once __DIR__ . '/../header.php';
?>

<div class="card">
    <h2><?= $t['upload_pdf'] ?></h2>
    
    <?php if (!isLoggedIn()): ?>
        <div class="alert alert-warning">
            <?php if ($lang === 'ru'): ?>
                Для загрузки файлов необходимо <a href="/login.php">войти в систему</a>
            <?php elseif ($lang === 'en'): ?>
                Please <a href="/login.php">login</a> to upload files
            <?php else: ?>
                Bitte <a href="/login.php">anmelden</a> um Dateien hochzuladen
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="pdf_file"><?= $t['file'] ?> (PDF, max 50MB)</label>
                <input type="file" id="pdf_file" name="pdf_file" accept=".pdf,application/pdf" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= $t['upload_pdf'] ?></button>
        </form>
    <?php endif; ?>
</div>

<div class="card">
    <h2><?= $t['my_files'] ?></h2>
    
    <?php if (empty($files)): ?>
        <p style="color: var(--text-secondary);">
            <?php if ($lang === 'ru'): ?>
                Нет загруженных файлов
            <?php elseif ($lang === 'en'): ?>
                No uploaded files
            <?php else: ?>
                Keine hochgeladenen Dateien
            <?php endif; ?>
        </p>
    <?php else: ?>
        <table>
            <tr>
                <th><?= $t['file'] ?></th>
                <th><?= $t['size'] ?></th>
                <th><?= $t['date'] ?></th>
                <?php if (isAdmin()): ?>
                    <th>Owner</th>
                <?php endif; ?>
                <th>Actions</th>
            </tr>
            <?php foreach ($files as $file): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($file['original_name']) ?></strong>
                    </td>
                    <td><?= number_format($file['file_size'] / 1024, 1) ?> KB</td>
                    <td><?= date('d.m.Y H:i', strtotime($file['uploaded_at'])) ?></td>
                    <?php if (isAdmin()): ?>
                        <td><?= htmlspecialchars($file['owner_login']) ?></td>
                    <?php endif; ?>
                    <td>
                        <a href="/pdf/download.php?id=<?= $file['id'] ?>" class="btn btn-success"><?= $t['download'] ?></a>
                        <a href="/pdf/index.php?delete=<?= $file['id'] ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('<?php if ($lang === 'ru'): ?>Удалить файл?<?php else: ?>Delete file?<?php endif; ?>')">
                            <?= $t['delete'] ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

<div class="card">
    <h2>
        <?php if ($lang === 'ru'): ?>
            Техническая информация
        <?php elseif ($lang === 'en'): ?>
            Technical Information
        <?php else: ?>
            Technische Informationen
        <?php endif; ?>
    </h2>
    <p>
        <?php if ($lang === 'ru'): ?>
            PDF файлы хранятся непосредственно в реляционной базе данных MySQL 
            в поле типа <code>LONGBLOB</code> (до 4GB на файл).
        <?php elseif ($lang === 'en'): ?>
            PDF files are stored directly in the MySQL relational database 
            in a <code>LONGBLOB</code> field (up to 4GB per file).
        <?php else: ?>
            PDF-Dateien werden direkt in der MySQL-Datenbank 
            in einem <code>LONGBLOB</code>-Feld gespeichert (bis zu 4GB pro Datei).
        <?php endif; ?>
    </p>
</div>

<?php require_once __DIR__ . '/../footer.php'; ?>

