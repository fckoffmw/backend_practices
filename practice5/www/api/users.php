<?php
/**
 * REST API для пользователей
 * GET - список всех пользователей или конкретного по id
 * POST - создание нового пользователя
 * PUT - обновление пользователя
 * DELETE - удаление пользователя
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Получить конкретного пользователя
                $stmt = $conn->prepare("
                    SELECT u.ID, u.login, u.name, u.surname, u.is_admin, u.created_at,
                           us.theme, us.language
                    FROM users u
                    LEFT JOIN user_settings us ON u.ID = us.user_id
                    WHERE u.ID = ?
                ");
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                
                if ($result) {
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'User not found']);
                }
            } else {
                // Получить всех пользователей
                $result = $conn->query("
                    SELECT u.ID, u.login, u.name, u.surname, u.is_admin, u.created_at,
                           us.theme, us.language
                    FROM users u
                    LEFT JOIN user_settings us ON u.ID = us.user_id
                    ORDER BY u.ID
                ");
                echo json_encode($result->fetch_all(MYSQLI_ASSOC), JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['login']) || empty($data['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Login and password are required']);
                break;
            }
            
            // Создаём пользователя
            $stmt = $conn->prepare("
                INSERT INTO users (login, password, name, surname, is_admin) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $isAdmin = $data['is_admin'] ?? 0;
            $stmt->bind_param("ssssi", 
                $data['login'], 
                $data['password'], 
                $data['name'] ?? null, 
                $data['surname'] ?? null, 
                $isAdmin
            );
            
            if ($stmt->execute()) {
                $userId = $conn->insert_id;
                
                // Создаём настройки по умолчанию
                $stmt2 = $conn->prepare("
                    INSERT INTO user_settings (user_id, theme, language) 
                    VALUES (?, 'light', 'ru')
                ");
                $stmt2->bind_param("i", $userId);
                $stmt2->execute();
                
                http_response_code(201);
                echo json_encode([
                    'status' => 'User created',
                    'id' => $userId
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create user: ' . $conn->error]);
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID is required']);
                break;
            }
            
            // Обновляем пользователя
            $updates = [];
            $params = [];
            $types = '';
            
            if (isset($data['name'])) {
                $updates[] = 'name = ?';
                $params[] = $data['name'];
                $types .= 's';
            }
            if (isset($data['surname'])) {
                $updates[] = 'surname = ?';
                $params[] = $data['surname'];
                $types .= 's';
            }
            if (isset($data['password'])) {
                $updates[] = 'password = ?';
                $params[] = $data['password'];
                $types .= 's';
            }
            
            if (empty($updates)) {
                http_response_code(400);
                echo json_encode(['error' => 'No fields to update']);
                break;
            }
            
            $params[] = $data['id'];
            $types .= 'i';
            
            $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'User updated']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update user']);
            }
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'User ID is required']);
                break;
            }
            
            $stmt = $conn->prepare("DELETE FROM users WHERE ID = ?");
            $stmt->bind_param("i", $data['id']);
            
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                echo json_encode(['status' => 'User deleted']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'User not found']);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>

