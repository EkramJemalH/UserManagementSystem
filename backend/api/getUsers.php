<?php
require_once "../db.php";
session_start();

// Only admin can access
if ($_SESSION['role'] != 'admin') {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, username, email, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["status" => "success", "data" => $users]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
