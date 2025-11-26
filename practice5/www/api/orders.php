<?php
/**
 * REST API для заказов
 * GET - список всех заказов или конкретного по id
 * POST - создание нового заказа
 * PUT - обновление заказа
 * DELETE - удаление заказа
 */

header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../config.php';

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Получить конкретный заказ
                $stmt = $conn->prepare("
                    SELECT o.*, u.login as user_login, u.name as user_name
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.ID
                    WHERE o.order_id = ?
                ");
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                
                if ($result) {
                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                } else {
                    http_response_code(404);
                    echo json_encode(['error' => 'Order not found']);
                }
            } elseif (isset($_GET['user_id'])) {
                // Получить заказы пользователя
                $stmt = $conn->prepare("
                    SELECT o.*, u.login as user_login
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.ID
                    WHERE o.user_id = ?
                    ORDER BY o.created_at DESC
                ");
                $stmt->bind_param("i", $_GET['user_id']);
                $stmt->execute();
                echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC), JSON_UNESCAPED_UNICODE);
            } else {
                // Получить все заказы
                $result = $conn->query("
                    SELECT o.*, u.login as user_login, u.name as user_name
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.ID
                    ORDER BY o.created_at DESC
                ");
                echo json_encode($result->fetch_all(MYSQLI_ASSOC), JSON_UNESCAPED_UNICODE);
            }
            break;

        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['user_id']) || empty($data['product']) || !isset($data['amount'])) {
                http_response_code(400);
                echo json_encode(['error' => 'user_id, product and amount are required']);
                break;
            }
            
            $stmt = $conn->prepare("
                INSERT INTO orders (user_id, product, amount) 
                VALUES (?, ?, ?)
            ");
            $stmt->bind_param("isd", $data['user_id'], $data['product'], $data['amount']);
            
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode([
                    'status' => 'Order created',
                    'order_id' => $conn->insert_id
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to create order: ' . $conn->error]);
            }
            break;

        case 'PUT':
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['order_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'order_id is required']);
                break;
            }
            
            $updates = [];
            $params = [];
            $types = '';
            
            if (isset($data['product'])) {
                $updates[] = 'product = ?';
                $params[] = $data['product'];
                $types .= 's';
            }
            if (isset($data['amount'])) {
                $updates[] = 'amount = ?';
                $params[] = $data['amount'];
                $types .= 'd';
            }
            
            if (empty($updates)) {
                http_response_code(400);
                echo json_encode(['error' => 'No fields to update']);
                break;
            }
            
            $params[] = $data['order_id'];
            $types .= 'i';
            
            $sql = "UPDATE orders SET " . implode(', ', $updates) . " WHERE order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);
            
            if ($stmt->execute()) {
                echo json_encode(['status' => 'Order updated']);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to update order']);
            }
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['order_id'])) {
                http_response_code(400);
                echo json_encode(['error' => 'order_id is required']);
                break;
            }
            
            $stmt = $conn->prepare("DELETE FROM orders WHERE order_id = ?");
            $stmt->bind_param("i", $data['order_id']);
            
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                echo json_encode(['status' => 'Order deleted']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Order not found']);
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

