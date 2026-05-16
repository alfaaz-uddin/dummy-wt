<?php
session_start();

require_once '../models/Connect.php';
require_once '../models/Close.php';
require_once '../models/User.php';

$_SESSION['error'] = "";
$_SESSION['msg'] = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email == "" || $password == "") {
        $_SESSION['error'] = "Email and password are required";
        header('Location: ../views/auth/login.php');
        exit;
    }

    $conn = connect();
    $user = getUserByEmail($conn, $email);
    close($conn);

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_profile_pic'] = $user['profile_pic'];
        
        header('Location: ../index.php?page=dashboard');
    } else {
        $_SESSION['error'] = "Invalid email or password";
        header('Location: ../views/auth/login.php');
    }
} else {
    header('Location: ../views/auth/login.php');
}
?>