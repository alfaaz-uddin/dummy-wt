<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/Connect.php';
require_once '../models/Close.php';
require_once '../models/Comment.php';

$task_id = $_POST['task_id'] ?? 0;
$body = htmlspecialchars($_POST['body'] ?? '');
$is_internal = $_POST['is_internal'] ?? false;

if (!$task_id || !$body) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$conn = connect();
$comment_id = createComment($conn, $task_id, $_SESSION['user_id'], $body, $is_internal);
close($conn);

if ($comment_id) {
    echo json_encode(['success' => true, 'comment_id' => $comment_id, 'message' => 'Comment added']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
}
?>