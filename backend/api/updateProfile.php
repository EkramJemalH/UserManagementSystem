<?php
require_once "../db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$username, $email, $id]);
        echo json_encode(["status" => "success", "message" => "Profile updated"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
