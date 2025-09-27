<?php
session_start();
require_once "../db.php"; // Adjust path based on your folder structure

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Basic validation
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        die("Please fill in all required fields.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->rowCount() > 0) {
        die("Email already registered.");
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into DB
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
    $stmt->execute([
        'name' => $name,
        'email' => $email,
        'password' => $passwordHash,
        'role' => $role
    ]);

    // Save session info
    $_SESSION['role'] = $role;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email; // add email for profile page

    // Redirect based on role
    if ($role === 'admin') {
        header("Location: ../admin/admin-dashboard.php"); // Use .php instead of .html if you convert dashboards to PHP
        exit();
    } else {
        header("Location: ../user/profile.php"); // Use .php instead of .html
        exit();
    }
} else {
    // If accessed directly via GET
    header("Location: ../../frontend/register.html");
    exit();
}
?>
