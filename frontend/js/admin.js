document.addEventListener("DOMContentLoaded", () => {
  // Check if we are on the admin page
  const adminPage = document.getElementById("admin-page");
  if (!adminPage) return;

  // DOM elements
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

  // Mock data
  let users = [
    { id: 1, fullName: "Ekram Jemal", email: "ekram@email.com", role: "admin" },
    { id: 2, fullName: "Alice", email: "alice@email.com", role: "user" }
  ];

  let editUserId = null;

  // Render users in the table
  function renderUsers(list) {
    const searchText = searchInput.value.toLowerCase();
    const selectedRole = filterRole.value;

    const filtered = list.filter(u => {
      const matchesSearch = u.fullName.toLowerCase().includes(searchText) || u.email.toLowerCase().includes(searchText);
      const matchesRole = selectedRole === "all" || u.role.toLowerCase() === selectedRole;
      return matchesSearch && matchesRole;
    });

    userList.innerHTML = "";

    filtered.forEach(u => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${u.fullName}</td>
        <td>${u.email}</td>
        <td>${u.role}</td>
        <td>
          <button class="edit" data-id="${u.id}">Edit</button>
          <button class="delete" data-id="${u.id}">Delete</button>
        </td>
      `;
      userList.appendChild(row);
    });

    // Add listeners to new buttons
    document.querySelectorAll(".edit").forEach(btn => btn.addEventListener("click", () => editUser(Number(btn.dataset.id))));
    document.querySelectorAll(".delete").forEach(btn => btn.addEventListener("click", () => deleteUser(Number(btn.dataset.id))));
  }

  // Show Add User Form
  addUserBtn.addEventListener("click", () => {
    formTitle.textContent = "Add User";
    manageUserForm.reset();
    editUserId = null;
    userForm.style.display = "block";
  });

  // Cancel Form
  cancelForm.addEventListener("click", () => {
    userForm.style.display = "none";
    manageUserForm.reset();
    editUserId = null;
  });

  // Submit Add/Edit Form
  manageUserForm.addEventListener("submit", (e) => {
    e.preventDefault();
    const userData = {
      fullName: fullNameInput.value.trim(),
      email: emailInput.value.trim(),
      password: passwordInput.value.trim(),
      role: roleInput.value
    };

    if (!userData.fullName || !userData.email || !userData.password) {
      alert("Please fill all fields");
      return;
    }

    if (editUserId) {
      // Edit user
      const index = users.findIndex(u => u.id === editUserId);
      if (index !== -1) users[index] = { ...users[index], ...userData };
      editUserId = null;
    } else {
      // Add new user
      const newId = users.length ? Math.max(...users.map(u => u.id)) + 1 : 1;
      users.push({ ...userData, id: newId });
    }

    userForm.style.display = "none";
    manageUserForm.reset();
    renderUsers(users);
  });

  // Edit user
  function editUser(id) {
    const user = users.find(u => u.id === id);
    if (!user) return;

    fullNameInput.value = user.fullName;
    emailInput.value = user.email;
    passwordInput.value = "";
    roleInput.value = user.role;

    formTitle.textContent = "Edit User";
    userForm.style.display = "block";
    editUserId = id;
  }

  // Delete user
  function deleteUser(id) {
    if (!confirm("Are you sure you want to delete this user?")) return;
    users = users.filter(u => u.id !== id);
    renderUsers(users);
  }

  // Search and filter
  searchInput.addEventListener("input", () => renderUsers(users));
  filterRole.addEventListener("change", () => renderUsers(users));

  // Initial render
  renderUsers(users);
});
const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', () => {
        window.location.href = 'login.html';
    });