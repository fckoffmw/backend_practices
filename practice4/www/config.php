<?php
$host = 'db';
$dbname = 'appDB';
$user = 'user';
$pass = 'password';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

$conn->set_charset("utf8");
?>
