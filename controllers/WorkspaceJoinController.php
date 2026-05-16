<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first";
    header('Location: ../index.php?page=login');
    exit;
}

require_once '../models/Connect.php';
require_once '../models/Close.php';
require_once '../models/Workspace.php';

$_SESSION['error'] = "";
$_SESSION['msg'] = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invite_code = htmlspecialchars($_POST['invite_code'] ?? '');

    if ($invite_code == "") {
        $_SESSION['error'] = "Invite code is required";
        header('Location: ../views/workspace/join.php');
        exit;
    }

    $conn = connect();
    $workspace = getWorkspaceByInviteCode($conn, $invite_code);

    if (!$workspace) {
        $_SESSION['error'] = "Invalid invite code";
        close($conn);
        header('Location: ../views/workspace/join.php');
        exit;
    }

    if (joinWorkspace($conn, $workspace['id'], $_SESSION['user_id'])) {
        $_SESSION['msg'] = "Successfully joined workspace: " . $workspace['name'];
        close($conn);
        $_SESSION['current_workspace_id'] = $workspace['id'];
        header('Location: ../index.php?page=dashboard');
    } else {
        $_SESSION['error'] = "Failed to join workspace";
        close($conn);
        header('Location: ../views/workspace/join.php');
    }
} else {
    header('Location: ../views/workspace/join.php');
}
?>