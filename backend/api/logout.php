<?php
session_start();
session_unset();
session_destroy();
header("Location: /backend/login.php"); // redirect to login.php
exit();
?>
