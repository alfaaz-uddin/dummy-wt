<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

require_once '../../models/db.php';
require_once '../../models/Close.php';
require_once '../../models/User.php';

$conn = connect();
$user = getUserById($conn, $_SESSION['user_id']);
close($conn);

$msg = $_SESSION['msg'] ?? "";
unset($_SESSION['msg']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile - Task Management</title>
    <link rel="stylesheet" href="/WT-Project/views/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <div class="profile-container">
        <h2>My Profile</h2>

        <?php if ($msg): ?>
            <div class="alert alert-success"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="profile-info">
            <div class="profile-pic">
                <img src="../../public/uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic'] ?? 'default.png'); ?>" alt="Profile">
            </div>

            <table class="info-table">
                <tr>
                    <td><strong>Name:</strong></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                </tr>
                <tr>
                    <td><strong>Phone:</strong></td>
                    <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td><strong>Role:</strong></td>
                    <td><span class="role-badge"><?php echo ucfirst($user['role']); ?></span></td>
                </tr>
                <tr>
                    <td><strong>Member Since:</strong></td>
                    <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                </tr>
            </table>
        </div>

        <div class="profile-actions">
            <a href="edit.php" class="btn btn-primary">Edit Profile</a>
            <a href="change-password.php" class="btn btn-secondary">Change Password</a>
            <a href="../../index.php?page=dashboard" class="btn btn-tertiary">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>