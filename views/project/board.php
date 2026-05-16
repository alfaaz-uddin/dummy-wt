<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

require_once '../../models/Connect.php';
require_once '../../models/Close.php';
require_once '../../models/Project.php';
require_once '../../models/Task.php';

$project_id = $_GET['id'] ?? 0;

if (!$project_id) {
    header('Location: ../../index.php?page=dashboard');
    exit;
}

$conn = connect();
$project = getProjectById($conn, $project_id);
$tasks_by_status = getTasksByStatus($conn, $project_id);
close($conn);

if (!$project) {
    header('Location: ../../index.php?page=dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kanban - <?php echo htmlspecialchars($project['name']); ?></title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <h2><?php echo htmlspecialchars($project['name']); ?></h2>
    <p><?php echo htmlspecialchars($project['description']); ?></p>

    <div class="kanban-board">
        <?php foreach (['todo' => 'To Do', 'in_progress' => 'In Progress', 'review' => 'Review', 'done' => 'Done'] as $status => $label): ?>
            <div class="kanban-column">
                <h3><?php echo $label; ?> (<?php echo count($tasks_by_status[$status]); ?>)</h3>
                <div class="tasks-list" data-status="<?php echo $status; ?>">
                    <?php foreach ($tasks_by_status[$status] as $task): ?>
                        <div class="task-card" data-task-id="<?php echo $task['id']; ?>" onclick="viewTask(<?php echo $task['id']; ?>)">
                            <?php if ($task['is_blocked']): ?>
                                <div class="blocked-badge">🚫 BLOCKED</div>
                            <?php endif; ?>
                            <h4><?php echo htmlspecialchars($task['title']); ?></h4>
                            <p><?php echo htmlspecialchars(substr($task['description'], 0, 100)); ?>...</p>
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
</div>

<script src="../../public/js/kanban.js"></script>
</body>
</html>