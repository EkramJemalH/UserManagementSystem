const loginBtn=document.getElementById('loginBtn');

loginBtn.addEventListener('click',()=>{
    window.location.href='login.html';
})

const registerForm=document.getElementById("register-form");
registerForm.addEventListener('submit',function(e){
    e.preventDefault();

    const name=document.getElementById('name').value.trim();
    const email=document.getElementById('email').value.trim();
    const password=document.getElementById('password').value;
    const confirmPassword=document.getElementById('confirm_password').value;
    const errorMsg=document.getElementById("errorMsg");

    errorMsg.textContent="";

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if(!emailRegex.test(email)){
        errorMsg.textContent="Please enter a valid email address.";
        return;
    }

    if(password.length<6){
        errorMsg.textContent="Password must be at least 6 characters long";
        return;
    }
    if(password !== confirmPassword){
        errorMsg.textContent="Passwords don't match.";
        return;
    }

    this.submit();
})