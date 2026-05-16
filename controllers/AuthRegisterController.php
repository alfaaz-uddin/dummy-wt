<?php
session_start();

require_once '../models/db.php';
require_once '../models/Close.php';
require_once '../models/User.php';

$_SESSION['error'] = "";
$_SESSION['msg'] = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($name == "" || $email == "" || $password == "") {
        $_SESSION['error'] = "Name, email and password are required";
        header('Location: ../views/auth/register.php');
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header('Location: ../views/auth/register.php');
        exit;
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters";
        header('Location: ../views/auth/register.php');
        exit;
    }

    $conn = connect();

    if (emailExists($conn, $email)) {
        $_SESSION['error'] = "Email already registered";
        close($conn);
        header('Location: ../views/auth/register.php');
        exit;
    }

    if (registerUser($conn, $name, $email, $phone, $password)) {
        $_SESSION['msg'] = "Registration successful! Please login.";
        close($conn);
        header('Location: ../views/auth/login.php');
    } else {
        $_SESSION['error'] = "Registration failed. Try again.";
        close($conn);
        header('Location: ../views/auth/register.php');
    }
} else {
    header('Location: ../views/auth/register.php');
}
?>