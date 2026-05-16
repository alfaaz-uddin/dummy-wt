<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/Connect.php';
require_once '../models/Close.php';
require_once '../models/Task.php';

$task_id = $_POST['task_id'] ?? 0;
$status = $_POST['status'] ?? '';

if (!$task_id || !$status) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$valid_statuses = ['todo', 'in_progress', 'review', 'done'];
if (!in_array($status, $valid_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit;
}

$conn = connect();
$result = updateTaskStatus($conn, $task_id, $status);
close($conn);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Task status updated']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update task']);
}
?>