<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

require_once '../../models/Connect.php';
require_once '../../models/Close.php';
require_once '../../models/Workspace.php';
require_once '../../models/Task.php';
require_once '../../models/TimeLog.php';
require_once '../../models/Notification.php';

$conn = connect();

// Get user workspaces
$workspaces = getUserWorkspaces($conn, $_SESSION['user_id']);
$_SESSION['current_workspace_id'] = $_SESSION['current_workspace_id'] ?? ($workspaces[0]['id'] ?? null);

// Get assigned tasks
$all_tasks = getUserAssignedTasks($conn, $_SESSION['user_id']);

// Separate tasks
$today_tasks = [];
$overdue_tasks = [];
$active_tasks = [];
$completed_this_week = 0;

$today = date('Y-m-d');

foreach ($all_tasks as $task) {
    if ($task['status'] === 'done') {
        if (strtotime($task['created_at']) >= strtotime('-7 days')) {
            $completed_this_week++;
        }
    } else {
        if ($task['due_date'] === $today) {
            $today_tasks[] = $task;
        } elseif (strtotime($task['due_date']) < time()) {
            $overdue_tasks[] = $task;
        } else {
            $active_tasks[] = $task;
        }
    }
}

// Get weekly hours and productivity
$weekly_hours = getUserWeeklyHours($conn, $_SESSION['user_id']);
$total_hours = getUserTotalHours($conn, $_SESSION['user_id']);
$unread_notifications = getUnreadNotifications($conn, $_SESSION['user_id']);

close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Task Management</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>

    <div class="stats-grid">
        <div class="stat-card">
            <h3><?php echo count($today_tasks); ?></h3>
            <p>Tasks Due Today</p>
        </div>
        <div class="stat-card">
            <h3><?php echo count($overdue_tasks); ?></h3>
            <p>Overdue Tasks</p>
        </div>
        <div class="stat-card">
            <h3><?php echo $completed_this_week; ?></h3>
            <p>Completed This Week</p>
        </div>
        <div class="stat-card">
            <h3><?php echo number_format($weekly_hours, 1); ?> hrs</h3>
            <p>Weekly Hours Logged</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3>Today's Tasks</h3>
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Project</th>
                    <th>Priority</th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($today_tasks) > 0): ?>
                    <?php foreach ($today_tasks as $task): ?>
                        <tr>
                            <td><a href="../../index.php?page=task-detail&id=<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['title']); ?></a></td>
                            <td><?php echo htmlspecialchars($task['project_name']); ?></td>
                            <td><span class="priority priority-<?php echo $task['priority']; ?>"><?php echo ucfirst($task['priority']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">No tasks due today</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-6">
            <h3>Overdue Tasks</h3>
            <table>
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Project</th>
                    <th>Due Date</th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($overdue_tasks) > 0): ?>
                    <?php foreach ($overdue_tasks as $task): ?>
                        <tr class="overdue">
                            <td><a href="../../index.php?page=task-detail&id=<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['title']); ?></a></td>
                            <td><?php echo htmlspecialchars($task['project_name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($task['due_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">No overdue tasks</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>