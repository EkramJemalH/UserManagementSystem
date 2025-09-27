const loginBtn=document.getElementById("loginBtn");

loginBtn.addEventListener('click',()=>{
  window.location.href="login.html";
});

document.getElementById("register-form").addEventListener("submit", async (e) => {
    e.preventDefault();
    const form = e.target;
    const password = form.password.value;
    const confirmPassword = form.confirm_password.value;

    const role = document.querySelector('input[name="role"]:checked')?.value;
    if (!role) return document.getElementById("errorMsg").textContent = "Please select a role.";
    if (password !== confirmPassword) return document.getElementById("errorMsg").textContent = "Passwords do not match!";

    const formData = new FormData(form);

    try {
        const response = await fetch("../../backend/api/register.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = data.redirect;
        } else {
            document.getElementById("errorMsg").textContent = data.error || "Registration failed.";
        }
    } catch (err) {
        console.error(err);
        document.getElementById("errorMsg").textContent = "Server error. Try again later.";
    }
});
