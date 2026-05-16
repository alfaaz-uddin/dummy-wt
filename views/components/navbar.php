<?php

require_once dirname(__DIR__, 2) . '/models/db.php';
require_once dirname(__DIR__, 2) . '/models/Close.php';
require_once dirname(__DIR__, 2) . '/models/Notification.php';
require_once dirname(__DIR__, 2) . '/models/Workspace.php';

$conn = connect();
$unread_notifications = getUnreadNotifications($conn, $_SESSION['user_id']);
$workspaces = getUserWorkspaces($conn, $_SESSION['user_id']);
close($conn);
?>
<!DOCTYPE html>
<meta charset="utf-8">
<nav class="navbar">
    <div class="navbar-left">
        <h1><a href="../../index.php?page=dashboard">Task Management</a></h1>
    </div>

    <div class="navbar-center">
        <a href="../../index.php?page=dashboard">Dashboard</a>
        <a href="../../index.php?page=my-tasks">My Tasks</a>
        
        <?php if (count($workspaces) > 0): ?>
            <div class="dropdown">
                <button class="dropdown-btn">Workspaces ▼</button>
                <div class="dropdown-content">
                    <?php foreach ($workspaces as $ws): ?>
                        <a href="../../index.php?page=workspace&id=<?php echo $ws['id']; ?>"><?php echo htmlspecialchars(substr($ws['name'], 0, 20)); ?></a>
                    <?php endforeach; ?>
                    <hr>
                    <a href="/WT-Project/views/workspace/join.php">+ Join Workspace</a>
                </div>
            </div>
        <?php else: ?>
            <a href="/WT-Project/views/workspace/join.php">Join Workspace</a>
        <?php endif; ?>

        <a href="../../index.php?page=notifications">
            Notifications 
            <?php if ($unread_notifications > 0): ?>
                <span class="notification-badge"><?php echo $unread_notifications; ?></span>
            <?php endif; ?>
        </a>
    </div>

    <div class="navbar-right">
        <div class="user-menu">
            <img src="../../public/uploads/profile_pics/<?php echo htmlspecialchars($_SESSION['user_profile_pic'] ?? 'default.png'); ?>" alt="Profile">
            <div class="dropdown">
                <button class="dropdown-btn"><?php echo htmlspecialchars($_SESSION['user_name']); ?> ▼</button>
                <div class="dropdown-content">
                    <a href="../../index.php?page=profile">View Profile</a>
                    <a href="../../index.php?page=edit-profile">Edit Profile</a>
                    <a href="../../index.php?page=change-password">Change Password</a>
                    <hr>
                    <a href="../../controllers/AuthLogoutController.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>