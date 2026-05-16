<?php
session_start();
$error = $_SESSION['error'] ?? "";
$msg = $_SESSION['msg'] ?? "";
unset($_SESSION['error']);
unset($_SESSION['msg']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Task Management</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<div class="auth-container">
    <div class="auth-box">
        <h1>Task Management System</h1>
        <h2>Login</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST" action="../../controllers/AuthLoginController.php">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
</body>
</html>