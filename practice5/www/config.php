<?php
/**
 * Конфигурационный файл
 * Подключение к MySQL и Redis, инициализация сессий
 */

// Отключаем вывод ошибок mysqli
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

// ==================== ПАРАМЕТРЫ ПОДКЛЮЧЕНИЯ ====================

// MySQL
define('DB_HOST', getenv('DB_HOST') ?: 'db');
define('DB_NAME', getenv('DB_NAME') ?: 'appDB');
define('DB_USER', getenv('DB_USER') ?: 'user');
define('DB_PASS', getenv('DB_PASS') ?: 'password');

// Redis
define('REDIS_HOST', getenv('REDIS_HOST') ?: 'redis');
define('REDIS_PORT', getenv('REDIS_PORT') ?: 6379);

// ==================== ПОДКЛЮЧЕНИЕ К MYSQL ====================

$maxAttempts = 10;
$attempt = 0;
$conn = null;

while ($attempt < $maxAttempts) {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (!$conn->connect_errno) {
            break;
        }
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
    die(json_encode(['error' => 'Database connection failed']));
}

$conn->set_charset("utf8mb4");

// ==================== ПОДКЛЮЧЕНИЕ К REDIS ====================

$redis = null;
try {
    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);
    $redis->ping();
} catch (Exception $e) {
    error_log("Redis connection failed: " . $e->getMessage());
    $redis = null;
}

// ==================== ИНИЦИАЛИЗАЦИЯ СЕССИЙ ====================

// Сессии хранятся в Redis (настроено в php.ini)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ ====================

/**
 * Получить текущего пользователя из сессии
 */
function getCurrentUser(): ?array {
    global $conn;
    
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    $stmt = $conn->prepare("
        SELECT u.*, us.theme, us.language 
        FROM users u 
        LEFT JOIN user_settings us ON u.ID = us.user_id 
        WHERE u.ID = ?
    ");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    
    return $result;
}

/**
 * Получить настройки пользователя (из сессии, cookies или БД)
 */
function getUserSettings(): array {
    global $conn;
    
    $defaults = [
        'login' => 'guest',
        'theme' => 'light',
        'language' => 'ru'
    ];
    
    // 1. Приоритет: данные из сессии
    if (isset($_SESSION['user_id'])) {
        $user = getCurrentUser();
        if ($user) {
            return [
                'login' => $user['login'],
                'theme' => $user['theme'] ?? $defaults['theme'],
                'language' => $user['language'] ?? $defaults['language']
            ];
        }
    }
    
    // 2. Второй приоритет: cookies
    $settings = $defaults;
    
    if (isset($_COOKIE['user_theme'])) {
        $settings['theme'] = in_array($_COOKIE['user_theme'], ['light', 'dark', 'colorblind']) 
            ? $_COOKIE['user_theme'] 
            : 'light';
    }
    
    if (isset($_COOKIE['user_language'])) {
        $settings['language'] = in_array($_COOKIE['user_language'], ['ru', 'en', 'de']) 
            ? $_COOKIE['user_language'] 
            : 'ru';
    }
    
    if (isset($_COOKIE['user_login'])) {
        $settings['login'] = htmlspecialchars($_COOKIE['user_login']);
    }
    
    return $settings;
}

/**
 * Сохранить настройки в cookies (для гостей) и в БД (для авторизованных)
 */
function saveUserSettings(string $theme, string $language): bool {
    global $conn;
    
    // Сохраняем в cookies (30 дней)
    $expiry = time() + (30 * 24 * 60 * 60);
    setcookie('user_theme', $theme, $expiry, '/');
    setcookie('user_language', $language, $expiry, '/');
    
    // Если пользователь авторизован - сохраняем в БД
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        
        // Проверяем, есть ли уже настройки
        $check = $conn->prepare("SELECT id FROM user_settings WHERE user_id = ?");
        $check->bind_param("i", $userId);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        
        if ($exists) {
            // Обновляем существующие
            $stmt = $conn->prepare("UPDATE user_settings SET theme = ?, language = ? WHERE user_id = ?");
            $stmt->bind_param("ssi", $theme, $language, $userId);
        } else {
            // Создаём новые
            $stmt = $conn->prepare("INSERT INTO user_settings (user_id, theme, language) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $userId, $theme, $language);
        }
        return $stmt->execute();
    }
    
    return true;
}

/**
 * Проверка авторизации
 */
function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Проверка прав администратора
 */
function isAdmin(): bool {
    $user = getCurrentUser();
    return $user && $user['is_admin'];
}

/**
 * Переводы для мультиязычности
 */
function getTranslations(string $lang): array {
    $translations = [
        'ru' => [
            'welcome' => 'Добро пожаловать',
            'login' => 'Войти',
            'logout' => 'Выйти',
            'register' => 'Регистрация',
            'settings' => 'Настройки',
            'users' => 'Пользователи',
            'orders' => 'Заказы',
            'upload_pdf' => 'Загрузить PDF',
            'my_files' => 'Мои файлы',
            'services' => 'Сервисы',
            'admin_panel' => 'Админ панель',
            'theme' => 'Тема',
            'language' => 'Язык',
            'light' => 'Светлая',
            'dark' => 'Тёмная',
            'colorblind' => 'Для дальтоников',
            'save' => 'Сохранить',
            'guest' => 'Гость',
            'drawer' => 'Рисование (SVG)',
            'sorter' => 'Сортировка',
            'about' => 'О проекте',
            'home' => 'Главная',
            'username' => 'Логин',
            'password' => 'Пароль',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'file' => 'Файл',
            'download' => 'Скачать',
            'delete' => 'Удалить',
            'size' => 'Размер',
            'date' => 'Дата',
        ],
        'en' => [
            'welcome' => 'Welcome',
            'login' => 'Login',
            'logout' => 'Logout',
            'register' => 'Register',
            'settings' => 'Settings',
            'users' => 'Users',
            'orders' => 'Orders',
            'upload_pdf' => 'Upload PDF',
            'my_files' => 'My Files',
            'services' => 'Services',
            'admin_panel' => 'Admin Panel',
            'theme' => 'Theme',
            'language' => 'Language',
            'light' => 'Light',
            'dark' => 'Dark',
            'colorblind' => 'Colorblind',
            'save' => 'Save',
            'guest' => 'Guest',
            'drawer' => 'Drawer (SVG)',
            'sorter' => 'Sorter',
            'about' => 'About',
            'home' => 'Home',
            'username' => 'Username',
            'password' => 'Password',
            'name' => 'Name',
            'surname' => 'Surname',
            'file' => 'File',
            'download' => 'Download',
            'delete' => 'Delete',
            'size' => 'Size',
            'date' => 'Date',
        ],
        'de' => [
            'welcome' => 'Willkommen',
            'login' => 'Anmelden',
            'logout' => 'Abmelden',
            'register' => 'Registrieren',
            'settings' => 'Einstellungen',
            'users' => 'Benutzer',
            'orders' => 'Bestellungen',
            'upload_pdf' => 'PDF hochladen',
            'my_files' => 'Meine Dateien',
            'services' => 'Dienste',
            'admin_panel' => 'Admin-Bereich',
            'theme' => 'Thema',
            'language' => 'Sprache',
            'light' => 'Hell',
            'dark' => 'Dunkel',
            'colorblind' => 'Farbenblind',
            'save' => 'Speichern',
            'guest' => 'Gast',
            'drawer' => 'Zeichnung (SVG)',
            'sorter' => 'Sortierung',
            'about' => 'Über',
            'home' => 'Startseite',
            'username' => 'Benutzername',
            'password' => 'Passwort',
            'name' => 'Name',
            'surname' => 'Nachname',
            'file' => 'Datei',
            'download' => 'Herunterladen',
            'delete' => 'Löschen',
            'size' => 'Größe',
            'date' => 'Datum',
        ]
    ];
    
    return $translations[$lang] ?? $translations['ru'];
}

