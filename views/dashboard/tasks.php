<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

require_once '../../models/Connect.php';
require_once '../../models/Close.php';
require_once '../../models/Task.php';

$conn = connect();
$all_tasks = getUserAssignedTasks($conn, $_SESSION['user_id']);

$tasks_by_status = [
    'todo' => [],
    'in_progress' => [],
    'review' => [],
    'done' => []
];

foreach ($all_tasks as $task) {
    $tasks_by_status[$task['status']][] = $task;
}

close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Tasks - Task Management</title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <h2>All My Tasks</h2>

    <div class="tasks-view">
        <div class="view-tabs">
            <button class="tab-btn active" onclick="switchView('grid')">Grid View</button>
            <button class="tab-btn" onclick="switchView('list')">List View</button>
        </div>

        <div id="gridView" class="tasks-grid">
            <?php foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'done' => 'Done'] as $status => $label): ?>
                <div class="tasks-column">
                    <h3><?php echo $label; ?> (<?php echo count($tasks_by_status[$status]); ?>)</h3>
                    <div class="tasks-list">
                        <?php foreach ($tasks_by_status[$status] as $task): ?>
                            <div class="task-card" onclick="window.location.href='../../index.php?page=task-detail&id=<?php echo $task['id']; ?>'">
                                <?php if ($task['is_blocked']): ?>
                                    <span class="blocked-badge">🚫</span>
                                <?php endif; ?>
                                <h4><?php echo htmlspecialchars($task['title']); ?></h4>
                                <p><?php echo htmlspecialchars(substr($task['description'], 0, 80)); ?>...</p>
                                <div class="task-meta">
                                    <span class="priority priority-<?php echo $task['priority']; ?>"><?php echo ucfirst($task['priority']); ?></span>
                                    <?php if ($task['due_date']): ?>
                                        <span class="due-date"><?php echo date('M d', strtotime($task['due_date'])); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="listView" class="tasks-table" style="display: none;">
            <table>
                <thead>
                <tr>
                    <th>Task</th>
                    <th>Project</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($all_tasks as $task): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                        <td><?php echo htmlspecialchars($task['project_name']); ?></td>
                        <td><span class="priority priority-<?php echo $task['priority']; ?>"><?php echo ucfirst($task['priority']); ?></span></td>
                        <td><span class="status status-<?php echo str_replace('_', '-', $task['status']); ?>"><?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?></span></td>
                        <td><?php echo $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'N/A'; ?></td>
                        <td><a href="../../index.php?page=task-detail&id=<?php echo $task['id']; ?>" class="btn btn-sm">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function switchView(view) {
    document.getElementById('gridView').style.display = view === 'grid' ? 'grid' : 'none';
    document.getElementById('listView').style.display = view === 'list' ? 'block' : 'none';
    
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
}
</script>

</body>
</html>