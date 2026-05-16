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

$error = $_SESSION['error'] ?? "";
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile - Task Management</title>
    <link rel="stylesheet" href="/WT-Project/views/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <div class="form-box">
        <h2>Edit Profile</h2>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="../../controllers/ProfileUpdateController.php" enctype="multipart/form-data">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email:</label>
                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                <small>Email cannot be changed</small>
            </div>

            <div class="form-group">
                <label>Phone:</label>
                <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label>Profile Picture:</label>
                <input type="file" name="profile_pic" accept="image/*">
                <small>JPG, PNG, GIF only. Max 5MB</small>
                <?php if ($user['profile_pic']): ?>
                    <p>Current: <img src="../../public/uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic']); ?>" style="max-width: 100px;"></p>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="view.php"><button type="button" class="btn btn-secondary">Cancel</button></a>
            </div>
        </form>
    </div>
</div>

</body>
</html>