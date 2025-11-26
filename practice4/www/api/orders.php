<?php
header('Content-Type: application/json');
require_once '../config.php';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (isset($_GET['id'])) {
            $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            echo json_encode($result);
        } else {
            $result = $conn->query("SELECT * FROM orders");
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("INSERT INTO orders (user_id, product, amount) VALUES (?, ?, ?)");
        $stmt->bind_param("isd", $data['user_id'], $data['product'], $data['amount']);
        $stmt->execute();
        echo json_encode(['status' => 'Order added']);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("UPDATE orders SET product=?, amount=? WHERE order_id=?");
        $stmt->bind_param("sdi", $data['product'], $data['amount'], $data['order_id']);
        $stmt->execute();
        echo json_encode(['status' => 'Order updated']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $conn->prepare("DELETE FROM orders WHERE order_id=?");
        $stmt->bind_param("i", $data['order_id']);
        $stmt->execute();
        echo json_encode(['status' => 'Order deleted']);
        break;

}
?>
