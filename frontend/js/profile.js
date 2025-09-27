const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', () => {
        window.location.href = 'login.html';
    });

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


