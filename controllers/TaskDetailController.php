<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

require_once '../models/Connect.php';
require_once '../models/Close.php';
require_once '../models/Task.php';
require_once '../models/Comment.php';
require_once '../models/TimeLog.php';
require_once '../models/TaskAttachment.php';

$task_id = $_GET['id'] ?? 0;

if (!$task_id) {
    header('Location: ../index.php?page=dashboard');
    exit;
}

$conn = connect();

$task = getTaskById($conn, $task_id);

if (!$task) {
    $_SESSION['error'] = "Task not found";
    close($conn);
    header('Location: ../index.php?page=dashboard');
    exit;
}

$comments = getTaskComments($conn, $task_id, true);
$time_logs = getTaskTimeLogs($conn, $task_id);
$attachments = getTaskAttachments($conn, $task_id);

close($conn);

$_SESSION['task'] = $task;
$_SESSION['comments'] = $comments;
$_SESSION['time_logs'] = $time_logs;
$_SESSION['attachments'] = $attachments;

header('Location: ../views/project/task-detail.php?id=' . $task_id);
?>