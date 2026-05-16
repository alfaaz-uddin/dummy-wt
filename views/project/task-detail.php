<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php?page=login');
    exit;
}

require_once '../../models/Connect.php';
require_once '../../models/Close.php';
require_once '../../models/Task.php';
require_once '../../models/Comment.php';
require_once '../../models/TimeLog.php';
require_once '../../models/TaskAttachment.php';

$task_id = $_GET['id'] ?? 0;

if (!$task_id) {
    header('Location: ../../index.php?page=dashboard');
    exit;
}

$conn = connect();

$task = getTaskById($conn, $task_id);

if (!$task) {
    close($conn);
    header('Location: ../../index.php?page=dashboard');
    exit;
}

$comments = getTaskComments($conn, $task_id, true);
$time_logs = getTaskTimeLogs($conn, $task_id);
$attachments = getTaskAttachments($conn, $task_id);

close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($task['title']); ?></title>
    <link rel="stylesheet" href="../../public/css/styles.css">
</head>
<body>
<?php require_once '../components/navbar.php'; ?>

<div class="container">
    <div class="task-detail">
        <div class="task-header">
            <h2><?php echo htmlspecialchars($task['title']); ?></h2>
            <div class="task-actions">
                <span class="priority priority-<?php echo $task['priority']; ?>"><?php echo ucfirst($task['priority']); ?></span>
                <span class="status status-<?php echo str_replace('_', '-', $task['status']); ?>"><?php echo ucfirst(str_replace('_', ' ', $task['status'])); ?></span>
                <?php if ($task['is_blocked']): ?>
                    <span class="blocked">🚫 BLOCKED</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="task-body">
            <div class="left-panel">
                <h3>Description</h3>
                <p><?php echo nl2br(htmlspecialchars($task['description'])); ?></p>

                <h3>Details</h3>
                <table class="details-table">
                    <tr>
                        <td>Assigned To:</td>
                        <td><?php echo $task['assigned_name'] ?? 'Unassigned'; ?></td>
                    </tr>
                    <tr>
                        <td>Due Date:</td>
                        <td><?php echo $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <td>Estimated Hours:</td>
                        <td><?php echo $task['estimated_hours'] ?? 'N/A'; ?></td>
                    </tr>
                    <tr>
                        <td>Created By:</td>
                        <td><?php echo htmlspecialchars($task['created_name']); ?></td>
                    </tr>
                </table>

                <h3>Status Update</h3>
                <select onchange="updateTaskStatus(<?php echo $task['id']; ?>, this.value)">
                    <option value="todo" <?php echo $task['status'] === 'todo' ? 'selected' : ''; ?>>To Do</option>
                    <option value="in_progress" <?php echo $task['status'] === 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                    <option value="review" <?php echo $task['status'] === 'review' ? 'selected' : ''; ?>>Review</option>
                    <option value="done" <?php echo $task['status'] === 'done' ? 'selected' : ''; ?>>Done</option>
                </select>

                <h3>Block Task</h3>
                <div class="block-section">
                    <label>
                        <input type="checkbox" onchange="toggleBlocked(<?php echo $task['id']; ?>, this.checked)" <?php echo $task['is_blocked'] ? 'checked' : ''; ?>>
                        Mark as Blocked
                    </label>
                    <?php if ($task['is_blocked']): ?>
                        <p><strong>Reason:</strong> <?php echo htmlspecialchars($task['blocked_reason']); ?></p>
                    <?php endif; ?>
                    <textarea id="blocked_reason" placeholder="Why is this task blocked?" style="display: <?php echo $task['is_blocked'] ? 'block' : 'none'; ?>"></textarea>
                </div>
            </div>

            <div class="right-panel">
                <h3>Attachments</h3>
                <div class="attachments-list">
                    <?php foreach ($attachments as $attachment): ?>
                        <div class="attachment">
                            <a href="<?php echo htmlspecialchars($attachment['file_path']); ?>" download>📎 <?php echo htmlspecialchars($attachment['file_name']); ?></a>
                            <small>by <?php echo htmlspecialchars($attachment['name']); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="upload-area">
                    <form id="uploadForm">
                        <input type="file" id="attachment" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.zip">
                        <button type="submit" class="btn btn-sm">Upload</button>
                    </form>
                </div>

                <h3>Time Logs</h3>
                <div class="time-logs">
                    <?php
                    $total = 0;
                    foreach ($time_logs as $log):
                        $total += $log['hours_logged'];
                    ?>
                        <div class="time-log">
                            <strong><?php echo htmlspecialchars($log['name']); ?>:</strong> <?php echo $log['hours_logged']; ?> hrs
                            <?php if ($log['note']): ?>
                                <p><small><?php echo htmlspecialchars($log['note']); ?></small></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <p><strong>Total: <?php echo $total; ?> hours</strong></p>
                </div>

                <div class="log-time-form">
                    <h4>Log Time</h4>
                    <form id="logTimeForm">
                        <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                        <input type="number" name="hours" placeholder="Hours" step="0.5" required>
                        <textarea name="note" placeholder="Note (optional)"></textarea>
                        <button type="submit" class="btn btn-sm">Log Time</button>
                    </form>
                </div>

                <h3>Comments</h3>
                <div class="comments-section">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <strong><?php echo htmlspecialchars($comment['name']); ?></strong>
                            <?php if ($comment['is_internal']): ?>
                                <span class="internal-badge">Internal</span>
                            <?php endif; ?>
                            <p><?php echo nl2br(htmlspecialchars($comment['body'])); ?></p>
                            <small><?php echo date('M d, Y H:i', strtotime($comment['created_at'])); ?></small>
                            <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                                <a href="#" onclick="deleteComment(<?php echo $comment['id']; ?>)">Delete</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="add-comment-form">
                    <textarea id="commentBody" placeholder="Add a comment..."></textarea>
                    <label>
                        <input type="checkbox" id="isInternal">
                        Internal (not visible to clients)
                    </label>
                    <button onclick="addComment(<?php echo $task_id; ?>)" class="btn btn-sm">Comment</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../public/js/task-detail.js"></script>
</body>
</html>