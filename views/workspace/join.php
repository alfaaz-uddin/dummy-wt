<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

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
    <title>Join Workspace - Task Management</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <div class="form-box">
        <h2>Join a Workspace</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>

        <form method="POST" action="../../controllers/WorkspaceJoinController.php">
            <div class="form-group">
                <label>Workspace Invite Code:</label>
                <input type="text" name="invite_code" placeholder="Enter the invite code" required>
                <small>Ask your workspace owner or team lead for the invite code</small>
            </div>

            <button type="submit" class="btn btn-primary">Join Workspace</button>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            <a href="../../index.php?page=dashboard">Back to Dashboard</a>
        </p>
    </div>
</div>

</body>
</html>