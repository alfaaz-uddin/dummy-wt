<?php
session_start();
$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Task Management</title>
    <link rel="stylesheet" href="/WT-Project/views/css/styles.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <h2>Task Management System</h2>
        <h2>Create Account</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="../../controllers/AuthRegisterController.php" onsubmit="return validateRegister()">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" id="name" required>
                <span id="nameErr" class="error"></span>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" id="email" required>
                <span id="emailErr" class="error"></span>
            </div>

            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" id="phone">
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" id="password" required>
                <span id="passwordErr" class="error"></span>
            </div>

            <div class="form-group">
                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span id="confirmErr" class="error"></span>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</div>

<script src="/views/js/validation.js"></script>
</body>
</html>