<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/db.php';
require_once '../models/Close.php';
require_once '../models/Task.php';

$task_id = $_POST['task_id'] ?? 0;
$is_blocked = $_POST['is_blocked'] ?? false;
$reason = htmlspecialchars($_POST['reason'] ?? '');

if (!$task_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$conn = connect();
$result = toggleTaskBlocked($conn, $task_id, $is_blocked, $reason);
close($conn);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Task blocked status updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update task']);
}
?>