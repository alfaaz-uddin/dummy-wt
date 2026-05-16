<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require_once '../models/db.php';
require_once '../models/Close.php';
require_once '../models/TaskAttachment.php';

$task_id = $_POST['task_id'] ?? 0;

if (!$task_id || !isset($_FILES['attachment'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$target_dir = "../uploads/attachments/";

if (!is_dir($target_dir)) {
    mkdir($target_dir, 0755, true);
}

$file_extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
$allowed_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'jpg', 'jpeg', 'png', 'gif', 'zip'];

if (!in_array(strtolower($file_extension), $allowed_extensions)) {
    echo json_encode(['success' => false, 'message' => 'File type not allowed']);
    exit;
}

if ($_FILES['attachment']['size'] > 50 * 1024 * 1024) { // 50MB limit
    echo json_encode(['success' => false, 'message' => 'File size exceeds 50MB limit']);
    exit;
}

$new_filename = "attachment_" . time() . "_" . basename($_FILES['attachment']['name']);
$target_file = $target_dir . $new_filename;

if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
    $conn = connect();
    $result = uploadAttachment($conn, $task_id, $_SESSION['user_id'], $target_file, $_FILES['attachment']['name']);
    close($conn);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save attachment']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload file']);
}
?>