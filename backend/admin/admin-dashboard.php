<?php
session_start();

// ✅ Redirect if not logged in or not admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/login.html"); // redirect to login if not admin
    exit();
}

// Optional: get admin name from session
$adminName = $_SESSION['user_name'] ?? 'Admin';

require_once "../db.php"; // database connection

// Fetch all users from DB
$stmt = $conn->prepare("SELECT id, name, email, role FROM users");
$stmt->execute();
$usersFromDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
</head>
<body style="font-family: Arial, sans-serif; margin:0; padding:0; background:#f4f6f8;">

  <!-- Header -->
  <header style="background-color:#e74c3c; color:#fff; padding:20px 40px; display:flex; justify-content:space-between; align-items:center;">
    <h1 style="font-size:28px; margin:0;">Admin Dashboard</h1>
    <span>Welcome, <?php echo htmlspecialchars($adminName); ?>!</span>
    <button id="logoutBtn" style="background:#7a1b11; color:#fff; border:none; padding:10px 18px; font-size:16px; border-radius:6px; cursor:pointer;">Logout</button>
  </header>

  <!-- Controls -->
  <section style="display:flex; justify-content:space-between; padding:20px 40px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.1); margin-top:20px;">
    <input type="text" id="search" placeholder="Search users..." style="padding:10px; font-size:16px; border:1px solid #ccc; border-radius:6px; width:220px;">
    <select id="filterRole" style="padding:10px; font-size:16px; border:1px solid #ccc; border-radius:6px; width:220px;">
      <option value="all">All Roles</option>
      <option value="user">User</option>
      <option value="admin">Admin</option>
    </select>
    <button id="addUserBtn" style="background:#7a1b11; color:#fff; padding:10px 20px; border:none; border-radius:6px; font-size:16px; cursor:pointer;">+ Add User</button>
  </section>

  <!-- User Table -->
  <section style="padding:30px 40px;">
    <table style="width:100%; border-collapse:collapse; background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
      <thead>
        <tr>
          <th style="text-align:left; padding:14px 16px; border-bottom:1px solid #e0e0e0; background:#ecf0f1; font-size:18px;">Full Name</th>
          <th style="text-align:left; padding:14px 16px; border-bottom:1px solid #e0e0e0; background:#ecf0f1; font-size:18px;">Email</th>
          <th style="text-align:left; padding:14px 16px; border-bottom:1px solid #e0e0e0; background:#ecf0f1; font-size:18px;">Role</th>
          <th style="text-align:left; padding:14px 16px; border-bottom:1px solid #e0e0e0; background:#ecf0f1; font-size:18px;">Actions</th>
        </tr>
      </thead>
      <tbody id="userList">
        <!-- Users populated via JS -->
      </tbody>
    </table>
  </section>

  <!-- Add/Edit User Form -->
  <div id="userForm" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.2); width:400px; z-index:1000;">
    <h2 id="formTitle" style="margin-bottom:20px; text-align:center;">Add User</h2>
    <form id="manageUserForm">
      <label style="display:block; margin-top:12px; font-weight:bold;">Full Name</label>
      <input type="text" id="fullName" required style="width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:6px; font-size:16px;">

      <label style="display:block; margin-top:12px; font-weight:bold;">Email</label>
      <input type="email" id="email" required style="width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:6px; font-size:16px;">

      <label style="display:block; margin-top:12px; font-weight:bold;">Password</label>
      <input type="password" id="password" required style="width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:6px; font-size:16px;">

      <label style="display:block; margin-top:12px; font-weight:bold;">Role</label>
      <select id="role" required style="width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:6px; font-size:16px;">
        <option value="user">User</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit" style="margin-top:18px; padding:10px; width:100%; border:none; font-size:18px; border-radius:6px; cursor:pointer; background:#3498db; color:white;">Save</button>
      <button type="button" id="cancelForm" style="margin-top:10px; padding:10px; width:100%; border:none; font-size:18px; border-radius:6px; cursor:pointer; background:#bdc3c7; color:#2c3e50;">Cancel</button>
    </form>
  </div>

  <!-- Inline JS -->
  <script>
  document.addEventListener("DOMContentLoaded", () => {
    const userList = document.getElementById("userList");
    const searchInput = document.getElementById("search");
    const filterRole = document.getElementById("filterRole");
    const addUserBtn = document.getElementById("addUserBtn");
    const userForm = document.getElementById("userForm");
    const formTitle = document.getElementById("formTitle");
    const manageUserForm = document.getElementById("manageUserForm");
    const cancelForm = document.getElementById("cancelForm");
    const fullNameInput = document.getElementById("fullName");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const roleInput = document.getElementById("role");

    // Fetch users from PHP
    let users = <?php echo json_encode($usersFromDB); ?>;
    let editUserId = null;

    function renderUsers(list) {
      const searchText = searchInput.value.toLowerCase();
      const selectedRole = filterRole.value;
      const filtered = list.filter(u => {
        const matchesSearch = u.name.toLowerCase().includes(searchText) || u.email.toLowerCase().includes(searchText);
        const matchesRole = selectedRole === "all" || u.role.toLowerCase() === selectedRole;
        return matchesSearch && matchesRole;
      });

      userList.innerHTML = "";
      filtered.forEach(u => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${u.name}</td>
          <td>${u.email}</td>
          <td>${u.role}</td>
          <td>
            <button class="edit" data-id="${u.id}">Edit</button>
            <button class="delete" data-id="${u.id}">Delete</button>
          </td>
        `;
        userList.appendChild(row);
      });

      document.querySelectorAll(".edit").forEach(btn => btn.addEventListener("click", () => editUser(Number(btn.dataset.id))));
      document.querySelectorAll(".delete").forEach(btn => btn.addEventListener("click", () => deleteUser(Number(btn.dataset.id))));
    }

    addUserBtn.addEventListener("click", () => {
      formTitle.textContent = "Add User";
      manageUserForm.reset();
      editUserId = null;
      userForm.style.display = "block";
    });

    cancelForm.addEventListener("click", () => {
      userForm.style.display = "none";
      manageUserForm.reset();
      editUserId = null;
    });

    manageUserForm.addEventListener("submit", (e) => {
      e.preventDefault();
      const userData = {
        name: fullNameInput.value.trim(),
        email: emailInput.value.trim(),
        password: passwordInput.value.trim(),
        role: roleInput.value
      };
      if (!userData.name || !userData.email || !userData.password) { alert("Please fill all fields"); return; }

      if (editUserId) {
        const index = users.findIndex(u => u.id === editUserId);
        if (index !== -1) users[index] = { ...users[index], ...userData };
        editUserId = null;
      } else {
        const newId = users.length ? Math.max(...users.map(u => u.id)) + 1 : 1;
        users.push({ ...userData, id: newId });
      }

      userForm.style.display = "none";
      manageUserForm.reset();
      renderUsers(users);
    });

    function editUser(id) {
      const user = users.find(u => u.id === id);
      if (!user) return;
      fullNameInput.value = user.name;
      emailInput.value = user.email;
      passwordInput.value = "";
      roleInput.value = user.role;
      formTitle.textContent = "Edit User";
      userForm.style.display = "block";
      editUserId = id;
    }

    function deleteUser(id) {
      if (!confirm("Are you sure you want to delete this user?")) return;
      users = users.filter(u => u.id !== id);
      renderUsers(users);
    }

    searchInput.addEventListener("input", () => renderUsers(users));
    filterRole.addEventListener("change", () => renderUsers(users));

    renderUsers(users);

    const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', () => { window.location.href = '../frontend/login.html'; });
  });
  </script>

</body>
</html>
