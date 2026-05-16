<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?page=login');
    exit;
}

require_once '../models/db.php';
require_once '../models/Close.php';
require_once '../models/Project.php';
require_once '../models/Task.php';

$project_id = $_GET['id'] ?? 0;

if (!$project_id) {
    header('Location: ../index.php?page=dashboard');
    exit;
}

$conn = connect();

$project = getProjectById($conn, $project_id);

if (!$project) {
    $_SESSION['error'] = "Project not found";
    close($conn);
    header('Location: ../index.php?page=dashboard');
    exit;
}

$tasks_by_status = getTasksByStatus($conn, $project_id);

close($conn);

$_SESSION['project'] = $project;
$_SESSION['tasks_by_status'] = $tasks_by_status;

header('Location: ../views/project/board.php?id=' . $project_id);
?>