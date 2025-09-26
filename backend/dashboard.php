<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php"); // redirect to login if not admin
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
    </style>
</head>
<body>
    <h1>Welcome, Admin <?php echo $_SESSION['username']; ?></h1>

    <!-- Add User Form -->
    <h2>Add New User</h2>
    <form id="addUserForm">
        Username: <input type="text" name="username" required>
        Email: <input type="email" name="email" required>
        Password: <input type="password" name="password" required>
        Role:
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit">Add User</button>
    </form>

    <!-- Users Table -->
    <h2>All Users</h2>
    <table id="usersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
    // Fetch users
    async function loadUsers() {
        const res = await fetch('api/getUsers.php');
        const data = await res.json();
        if(data.status === 'success') {
            const tbody = document.querySelector('#usersTable tbody');
            tbody.innerHTML = '';
            data.data.forEach(user => {
                tbody.innerHTML += `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.username}</td>
                        <td>${user.email}</td>
                        <td>${user.role}</td>
                        <td>
                            <button onclick="editUser(${user.id})">Edit</button>
                            <button onclick="deleteUser(${user.id})">Delete</button>
                        </td>
                    </tr>
                `;
            });
        }
    }

    // Add user
    document.getElementById('addUserForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const res = await fetch('api/addUser.php', { method: 'POST', body: formData });
        const data = await res.json();
        alert(data.message);
        if(data.status === 'success') loadUsers();
        e.target.reset();
    });

    // Delete user
    async function deleteUser(id) {
        if(confirm('Are you sure?')) {
            const formData = new FormData();
            formData.append('id', id);
            const res = await fetch('api/deleteUser.php', { method: 'POST', body: formData });
            const data = await res.json();
            alert(data.message);
            if(data.status === 'success') loadUsers();
        }
    }

    function editUser(id) {
        // Can open a modal or redirect to edit page (not fully implemented here)
        alert('Edit function for user ' + id);
    }

    loadUsers(); // Initial load
    </script>
</body>
</html>
