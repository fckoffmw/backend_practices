<?php
header('Content-Type: application/json');
require_once './config.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$entity = $path[1] ?? '';

if ($entity === 'users') {
    switch ($method) {
        case 'GET':
            $result = $conn->query("SELECT * FROM users");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("INSERT INTO users (name, surname) VALUES (?, ?)");
            $stmt->bind_param("ss", $data['name'], $data['surname']);
            $stmt->execute();
            echo json_encode(['status' => 'User added']);
            break;
    }
} elseif ($entity === 'orders') {
    switch ($method) {
        case 'GET':
            $result = $conn->query("SELECT * FROM orders");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("INSERT INTO orders (user_id, product, amount) VALUES (?, ?, ?)");
            $stmt->bind_param("isd", $data['user_id'], $data['product'], $data['amount']);
            $stmt->execute();
            echo json_encode(['status' => 'Order added']);
            break;
    }
} else {
    echo json_encode(['error' => 'Invalid endpoint']);
}
