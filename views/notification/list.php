<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

require_once '../../models/db.php';
require_once '../../models/Close.php';
require_once '../../models/Notification.php';

$conn = connect();
$notifications = getUserNotifications($conn, $_SESSION['user_id'], 50);
close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifications - Task Management</title>
    <link rel="stylesheet" href="/WT-Project/views/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <h2>Notifications</h2>

    <div class="notification-controls">
        <button onclick="markAllAsRead()" class="btn btn-sm">Mark All as Read</button>
    </div>

    <div class="notifications-list">
        <?php if (count($notifications) > 0): ?>
            <?php foreach ($notifications as $notif): ?>
                <div class="notification-item <?php echo $notif['is_read'] ? 'read' : 'unread'; ?>" onclick="handleNotification(<?php echo $notif['id']; ?>, '<?php echo htmlspecialchars($notif['link']); ?>')">
                    <div class="notification-content">
                        <p><?php echo htmlspecialchars($notif['message']); ?></p>
                        <small><?php echo date('M d, Y H:i', strtotime($notif['created_at'])); ?></small>
                    </div>
                    <span class="notification-type"><?php echo ucfirst($notif['type']); ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; padding: 40px;">No notifications</p>
        <?php endif; ?>
    </div>
</div>

<script>
function markAllAsRead() {
    fetch('../../controllers/MarkAllNotificationsReadController.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function handleNotification(notifId, link) {
    fetch('../../controllers/MarkNotificationReadController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'notification_id=' + notifId
    })
    .then(() => {
        if (link) {
            window.location.href = link;
        } else {
            location.reload();
        }
    });
}
</script>

</body>
</html>