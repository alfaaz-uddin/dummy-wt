<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/db.php';
require_once '../models/Close.php';
require_once '../models/Notification.php';

$conn = connect();
$result = markAllNotificationsAsRead($conn, $_SESSION['user_id']);
close($conn);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'All notifications marked as read']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark as read']);
}
?>