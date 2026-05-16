function validateRegister() {
    let name = document.getElementById('name').value.trim();
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value;
    let confirm = document.getElementById('confirm_password').value;

    document.getElementById('nameErr').innerText = '';
    document.getElementById('emailErr').innerText = '';
    document.getElementById('passwordErr').innerText = '';
    document.getElementById('confirmErr').innerText = '';

    let isValid = true;

    if (name.length < 3) {
        document.getElementById('nameErr').innerText = 'Name must be at least 3 characters';
        isValid = false;
    }

    if (!isValidEmail(email)) {
        document.getElementById('emailErr').innerText = 'Please enter a valid email';
        isValid = false;
    }

    if (password.length < 6) {
        document.getElementById('passwordErr').innerText = 'Password must be at least 6 characters';
        isValid = false;
    }

    if (password !== confirm) {
        document.getElementById('confirmErr').innerText = 'Passwords do not match';
        isValid = false;
    }

    return isValid;
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}