<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/db.php';
require_once '../models/Close.php';
require_once '../models/TimeLog.php';

$task_id = $_POST['task_id'] ?? 0;
$hours = $_POST['hours'] ?? 0;
$note = htmlspecialchars($_POST['note'] ?? '');

if (!$task_id || !$hours || $hours <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$conn = connect();
$result = logTime($conn, $task_id, $_SESSION['user_id'], $hours, $note);
close($conn);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Time logged successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to log time']);
}
?>