const loginBtn=document.getElementById("loginBtn");

loginBtn.addEventListener('click',()=>{
  window.location.href="login.html";
});

const logoutBtn = document.getElementById('logoutBtn');
    logoutBtn.addEventListener('click', () => {
        window.location.href = 'login.html';
    });

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

        if(role=="admin"){
            window.location.href="admin-dashboard.html";

        }
        else{
            window.location.href="profile.html";
        }
        // All validations passed → submit
        this.submit();
    });
}



  


