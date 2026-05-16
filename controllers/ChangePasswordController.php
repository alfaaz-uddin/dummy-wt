<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please login first";
    header('Location: ../index.php?page=login');
    exit;
}

require_once '../models/db.php';
require_once '../models/Close.php';
require_once '../models/User.php';

$_SESSION['error'] = "";
$_SESSION['msg'] = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($old_password == "" || $new_password == "" || $confirm_password == "") {
        $_SESSION['error'] = "All fields are required";
        header('Location: ../views/profile/change-password.php');
        exit;
    }

    if (strlen($new_password) < 6) {
        $_SESSION['error'] = "New password must be at least 6 characters";
        header('Location: ../views/profile/change-password.php');
        exit;
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header('Location: ../views/profile/change-password.php');
        exit;
    }

    $conn = connect();
    $user = getUserById($conn, $_SESSION['user_id']);

    if (!$user || !password_verify($old_password, $user['password_hash'])) {
        $_SESSION['error'] = "Old password is incorrect";
        close($conn);
        header('Location: ../views/profile/change-password.php');
        exit;
    }

    if (changePassword($conn, $_SESSION['user_id'], $new_password)) {
        $_SESSION['msg'] = "Password changed successfully";
        close($conn);
        header('Location: ../views/profile/view.php');
    } else {
        $_SESSION['error'] = "Failed to change password";
        close($conn);
        header('Location: ../views/profile/change-password.php');
    }
} else {
    header('Location: ../views/profile/change-password.php');
}
?>