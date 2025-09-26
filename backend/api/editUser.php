<?php
require_once "../db.php";
session_start();

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
        $stmt->execute([$username, $email, $role, $id]);
        echo json_encode(["status" => "success", "message" => "User updated"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
