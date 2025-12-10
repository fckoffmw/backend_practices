<?php

/**
 * Отладочная страница для проверки аутентификации
 */

// Автозагрузка классов
require_once __DIR__ . '/../vendor/autoload.php';

// Загрузка конфигурации
$config = require_once __DIR__ . '/../config/config.php';

use App\Infrastructure\Database\DatabaseConnection;
use App\Models\UserRepository;

try {
    echo "<h1>Отладка аутентификации</h1>";
    
    // Подключение к БД
    $db = new DatabaseConnection($config['database']);
    $userRepository = new UserRepository($db);
    
    echo "<h2>1. Проверка подключения к БД</h2>";
    $users = $userRepository->findAll();
    echo "Найдено пользователей: " . count($users) . "<br>";
    
    echo "<h2>2. Список пользователей</h2>";
    foreach ($users as $user) {
        echo "ID: " . $user->getId() . 
             ", Логин: " . $user->getLogin() . 
             ", Имя: " . $user->getName() . 
             ", Роль: " . $user->getRole() . 
             ", Тема: " . $user->getTheme() . 
             ", Язык: " . $user->getLanguage() . "<br>";
    }
    
    echo "<h2>3. Проверка пароля для admin</h2>";
    $admin = $userRepository->findByLogin('admin');
    if ($admin) {
        echo "Пользователь admin найден<br>";
        echo "Хеш пароля: " . substr($admin->getPassword(), 0, 20) . "...<br>";
        
        $testPassword = 'password123';
        $isValid = $admin->verifyPassword($testPassword);
        echo "Проверка пароля '$testPassword': " . ($isValid ? 'УСПЕХ' : 'ОШИБКА') . "<br>";
        
        // Проверим хеш напрямую
        $directCheck = password_verify($testPassword, $admin->getPassword());
        echo "Прямая проверка password_verify: " . ($directCheck ? 'УСПЕХ' : 'ОШИБКА') . "<br>";
        
        // Создадим новый хеш для сравнения
        $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
        echo "Новый хеш для '$testPassword': " . substr($newHash, 0, 20) . "...<br>";
        
    } else {
        echo "Пользователь admin НЕ найден<br>";
    }
    
    echo "<h2>4. Проверка сессий</h2>";
    session_start();
    echo "Session ID: " . session_id() . "<br>";
    echo "Данные сессии: " . print_r($_SESSION, true) . "<br>";
    
} catch (Exception $e) {
    echo "<h2>ОШИБКА:</h2>";
    echo $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

?>

<h2>5. Тест аутентификации</h2>
<form method="POST">
    <input type="text" name="login" placeholder="Логин" value="admin"><br><br>
    <input type="password" name="password" placeholder="Пароль" value="password123"><br><br>
    <button type="submit">Проверить</button>
</form>

<?php
if ($_POST) {
    echo "<h3>Результат проверки:</h3>";
    
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    
    echo "Логин: $login<br>";
    echo "Пароль: $password<br>";
    
    try {
        $user = $userRepository->findByLogin($login);
        if ($user) {
            echo "Пользователь найден<br>";
            $isValid = $user->verifyPassword($password);
            echo "Пароль " . ($isValid ? 'ПРАВИЛЬНЫЙ' : 'НЕПРАВИЛЬНЫЙ') . "<br>";
            
            if ($isValid) {
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_login'] = $user->getLogin();
                $_SESSION['user_name'] = $user->getName();
                echo "Сессия создана!<br>";
            }
        } else {
            echo "Пользователь НЕ найден<br>";
        }
    } catch (Exception $e) {
        echo "Ошибка: " . $e->getMessage() . "<br>";
    }
}
?>