<?php
// Include database connection
require_once "../db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Email and password are required"]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            echo json_encode([
                "status" => "success",
                "message" => "Login successful",
                "role" => $user['role']
            ]);
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
        }

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
