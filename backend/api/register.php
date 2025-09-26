<?php
// Include database connection
require_once "../db.php";

// Check if form data is sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']); // 'admin' or 'user'

    // Simple validation
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        echo json_encode(["status" => "error", "message" => "All fields are required"]);
        exit;
    }

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(["status" => "error", "message" => "Email already registered"]);
            exit;
        }

        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $passwordHash, $role]);

        echo json_encode(["status" => "success", "message" => "User registered successfully"]);

    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
