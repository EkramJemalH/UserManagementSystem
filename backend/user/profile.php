<?php
session_start();

// ✅ Redirect if not logged in or not a regular user
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../frontend/login.html"); // redirect to login if not a user
    exit();
}

// Get user info from session
$userName = $_SESSION['user_name'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? 'user@example.com';
$userRole = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Profile</title>
</head>
<body style="margin:0; font-family:Arial, sans-serif; background: radial-gradient(circle, rgba(131,54,54,0.8) 0%, rgba(131,54,54,0.5) 50%, rgba(131,54,54,0.2) 100%); color:#dadad6;">

<div style="max-width:800px; margin:50px auto; padding:20px; background:rgba(131,54,49,0.7); border-radius:20px; box-shadow:0 8px 16px rgba(0,0,0,0.3);">

  <!-- Header -->
  <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
    <h2 style="font-size:36px;">Welcome, <?php echo htmlspecialchars($userName); ?></h2>
    <button id="logoutBtn" style="width:100px; height:42px; border-radius:20px; margin:10px; border:2px double rgb(163,102,102); background-color:rgb(233,34,34); color:beige; font-weight:700; cursor:pointer;">Logout</button>
  </header>

  <!-- Profile Info -->
  <section style="display:flex; align-items:center; gap:20px; font-size:20px; flex-wrap:wrap;">
    <div style="text-align:center;">
      <img id="profilePic" src="../frontend/images/e033bf7b93236764dfe8c2ee78bbc40e.jpg" alt="Profile Picture" style="width:150px; height:150px; border-radius:50%; object-fit:cover; border:2px solid #d88b8b;">
      <input type="file" id="uploadPic" style="display:none;">
      <button id="changePicBtn" style="width:200px; height:38px; margin-top:10px; border:2px double rgb(163,102,102); background-color:rgb(145,17,17); color:beige; font-weight:700; cursor:pointer;">Change Picture</button>
    </div>
    <div>
      <p><strong>Full Name:</strong> <span id="displayName"><?php echo htmlspecialchars($userName); ?></span></p>
      <p><strong>Email:</strong> <span id="displayEmail"><?php echo htmlspecialchars($userEmail); ?></span></p>
      <p><strong>Role:</strong> <span><?php echo htmlspecialchars($userRole); ?></span></p>
      <button id="editProfileBtn" style="width:200px; height:42px; margin-top:10px; border:2px double rgb(163,102,102); background-color:brown; color:beige; font-weight:700; cursor:pointer;">Edit Profile</button>
    </div>
  </section>

  <!-- Edit Profile Section -->
  <section class="edit-profile" style="display:none; margin-top:30px; padding:15px; border:1px solid #ccc; border-radius:10px;">
    <h3>Edit Profile</h3>
    <form id="editProfileForm" style="display:flex; flex-direction:column; gap:10px;">
      <label>Full Name</label>
      <input type="text" id="editName" value="<?php echo htmlspecialchars($userName); ?>" style="width:50%; height:30px;">

      <label>Email</label>
      <input type="email" id="editEmail" value="<?php echo htmlspecialchars($userEmail); ?>" style="width:50%; height:30px;">

      <label>Password</label>
      <input type="password" id="editPassword" style="width:50%; height:30px;">

      <label>Confirm Password</label>
      <input type="password" id="editConfirmPassword" style="width:50%; height:30px;">

      <button type="submit" style="width:200px; height:38px; margin-top:10px; border:2px double rgb(163,102,102); background-color:rgb(145,17,17); color:beige; font-weight:700; cursor:pointer;">Save Changes</button>
    </form>
  </section>

</div>

<script>
  // Logout
  const logoutBtn = document.getElementById('logoutBtn');
  logoutBtn.addEventListener('click', () => {
      window.location.href = '../frontend/login.html';
  });

  // Toggle Edit Profile Section
  const editBtn = document.getElementById('editProfileBtn');
  const editSection = document.querySelector('.edit-profile');
  editBtn.addEventListener('click', () => {
    editSection.style.display = editSection.style.display === 'none' ? 'block' : 'none';
  });

  // Change Profile Picture
  const changePicBtn = document.getElementById('changePicBtn');
  const uploadPic = document.getElementById('uploadPic');
  const profilePic = document.getElementById('profilePic');

  changePicBtn.addEventListener('click', () => uploadPic.click());
  uploadPic.addEventListener('change', function () {
    const file = this.files[0];
    if (file) profilePic.src = URL.createObjectURL(file);
  });

  // Save Edited Profile Info
  const editForm = document.getElementById('editProfileForm');
  const displayName = document.getElementById('displayName');
  const displayEmail = document.getElementById('displayEmail');

  editForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const newName = document.getElementById('editName').value.trim();
    const newEmail = document.getElementById('editEmail').value.trim();
    const newPassword = document.getElementById('editPassword').value;
    const confirmPassword = document.getElementById('editConfirmPassword').value;

    if (newPassword && newPassword !== confirmPassword) {
      alert("Passwords don't match!");
      return;
    }

    displayName.textContent = newName;
    displayEmail.textContent = newEmail;
    editSection.style.display = 'none';

    alert("Profile updated successfully!");
  });
</script>

</body>
</html>
