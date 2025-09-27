<?php
$host = 'localhost';
$db   = 'ums_db';
$user = 'ums_user';
$pass = 'YourStrongPassword';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // for testing only
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
