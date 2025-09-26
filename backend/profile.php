<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // redirect if not logged in
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?></h1>

    <h2>Your Profile</h2>
    <form id="updateProfileForm">
        Username: <input type="text" name="username" value="<?php echo $_SESSION['username']; ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo $_SESSION['email']; ?>" required><br>
        <button type="submit">Update Profile</button>
    </form>

    <script>
    document.getElementById('updateProfileForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const res = await fetch('api/updateProfile.php', { method: 'POST', body: formData });
        const data = await res.json();
        alert(data.message);
        if(data.status === 'success') location.reload(); // refresh to update displayed info
    });
    </script>
</body>
</html>
