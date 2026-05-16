<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/Connect.php';
require_once '../models/Close.php';
require_once '../models/Comment.php';

$comment_id = $_POST['comment_id'] ?? 0;

if (!$comment_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$conn = connect();
$result = deleteComment($conn, $comment_id, $_SESSION['user_id']);
close($conn);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Comment deleted']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
}
?>