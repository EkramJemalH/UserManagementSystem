<?php
session_start();
require_once "/db.php"; // adjust path to your DB connection

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin-dashboard.php");
            exit();
        } else {
            header("Location: profile.php");
            exit();
        }
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../frontend/css/style.css">
</head>
<body class="login-page">

    <form class="login-form" action="" method="POST">
        <h1>Log in</h1>

        <?php if($error): ?>
            <p style="color:red; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <label>Email</label>
        <input type="email" name="email" placeholder="example@gmail.com" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login" name="submit">

        <p>Not registered yet? <a href="register.php">Create an Account</a></p>
    </form>

</body>
</html>
