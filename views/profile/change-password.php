<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password - Task Management</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <div class="form-box">
        <h2>Change Password</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="../../controllers/ChangePasswordController.php">
            <div class="form-group">
                <label>Old Password:</label>
                <input type="password" name="old_password" required>
            </div>

            <div class="form-group">
                <label>New Password:</label>
                <input type="password" name="new_password" id="new_password" required>
                <small>Minimum 6 characters</small>
            </div>

            <div class="form-group">
                <label>Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Change Password</button>
                <a href="view.php"><button type="button" class="btn btn-secondary">Cancel</button></a>
            </div>
        </form>
    </div>
</div>

</body>
</html>