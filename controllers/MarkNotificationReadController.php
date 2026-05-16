<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/Connect.php';
require_once '../models/Close.php';
require_once '../models/Notification.php';

$notification_id = $_POST['notification_id'] ?? 0;

if (!$notification_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$conn = connect();
$result = markNotificationAsRead($conn, $notification_id);
close($conn);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Notification marked as read']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to mark as read']);
}
?>