// LOGIN BUTTON (only if it exists)
const loginBtn = document.getElementById('loginBtn');
if (loginBtn) {
    loginBtn.addEventListener('click', () => {
        window.location.href = 'login.html';
    });
}

// REGISTRATION FORM VALIDATION (only if it exists)
const registerForm = document.getElementById("register-form");
if (registerForm) {
    registerForm.addEventListener('submit', function(e){
        e.preventDefault();

        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password') ? document.getElementById('confirm_password').value : '';
        const role=document.getElementById("role").value;
        const errorMsg = document.getElementById("errorMsg");

        if (errorMsg) errorMsg.textContent = "";

        // EMAIL VALIDATION
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            if (errorMsg) errorMsg.textContent = "Please enter a valid email address.";
            return;
        }

        // PASSWORD LENGTH CHECK
        if (password.length < 6) {
            if (errorMsg) errorMsg.textContent = "Password must be at least 6 characters long";
            return;
        }

        // CONFIRM PASSWORD MATCH
        if (confirmPassword && password !== confirmPassword) {
            if (errorMsg) errorMsg.textContent = "Passwords don't match.";
            return;
        }

        if(role==="admin"){
            window.location.href="admin-dashboard.html";

        }
        else{
            window.location.href="profile.html";
        }
        // All validations passed → submit
        this.submit();
    });
}

// ✅ Toggle Edit Profile section visibility
const editBtn = document.getElementById('editProfileBtn');
const editSection = document.querySelector('.edit-profile');

editBtn.addEventListener('click', () => {
  editSection.style.display = editSection.style.display === 'none' ? 'block' : 'none';
});

// ✅ Handle profile picture change
const changePicBtn = document.getElementById('changePicBtn');
const uploadPic = document.getElementById('uploadPic');
const profilePic = document.getElementById('profilePic');

changePicBtn.addEventListener('click', () => uploadPic.click());

uploadPic.addEventListener('change', function () {
  const file = this.files[0];
  if (file) {
    profilePic.src = URL.createObjectURL(file); // Show the chosen image immediately
  }
});

// ✅ Save edited profile info
const editForm = document.getElementById('editProfileForm');
const displayName = document.getElementById('displayName');
const displayEmail = document.getElementById('displayEmail');

editForm.addEventListener('submit', function (e) {
  e.preventDefault();

  const newName = document.getElementById('editName').value.trim();
  const newEmail = document.getElementById('editEmail').value.trim();
  const newPassword = document.getElementById('editPassword').value;
  const confirmPassword = document.getElementById('editConfirmPassword').value;

  // ✅ Simple validation
  if (newPassword && newPassword !== confirmPassword) {
    alert("Passwords don't match!");
    return;
  }

  // ✅ Update UI with new info
  displayName.textContent = newName;
  displayEmail.textContent = newEmail;

  // ✅ Hide the edit section again
  editSection.style.display = 'none';

  alert("Profile updated successfully!");
});
