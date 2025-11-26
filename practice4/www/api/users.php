<?php
header('Content-Type: application/json');
require_once '../config.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM users WHERE ID = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            echo json_encode($result);
        } else {
            $result = $conn->query("SELECT * FROM users");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO users (name, surname) VALUES (?, ?)");
        $stmt->bind_param("ss", $data['name'], $data['surname']);
        $stmt->execute();
        echo json_encode(['status' => 'User added']);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE users SET name=?, surname=? WHERE ID=?");
        $stmt->bind_param("ssi", $data['name'], $data['surname'], $data['id']);
        $stmt->execute();
        echo json_encode(['status' => 'User updated']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM users WHERE ID=?");
        $stmt->bind_param("i", $data['id']);
        $stmt->execute();
        echo json_encode(['status' => 'User deleted']);
        break;
}
?>
