<?php
require_once "../db.php";
session_start();

if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(["status" => "success", "message" => "User deleted"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
