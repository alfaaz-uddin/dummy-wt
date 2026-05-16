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
    $name = htmlspecialchars($_POST['name'] ?? '');
    $phone = htmlspecialchars($_POST['phone'] ?? '');
    
    if ($name == "") {
        $_SESSION['error'] = "Name is required";
        header('Location: ../views/profile/edit.php');
        exit;
    }

    $profile_pic = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['size'] > 0) {
        $target_dir = "../uploads/profile_pics/";
        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $file_extension = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            $_SESSION['error'] = "Only image files are allowed";
            header('Location: ../views/profile/edit.php');
            exit;
        }

        $new_filename = "profile_" . $_SESSION['user_id'] . "_" . time() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            $profile_pic = $new_filename;
        } else {
            $_SESSION['error'] = "Failed to upload profile picture";
            header('Location: ../views/profile/edit.php');
            exit;
        }
    }

    $conn = connect();
    $result = updateProfile($conn, $_SESSION['user_id'], $name, $phone, $profile_pic);
    close($conn);

    if ($result) {
        $_SESSION['user_name'] = $name;
        if ($profile_pic) {
            $_SESSION['user_profile_pic'] = $profile_pic;
        }
        $_SESSION['msg'] = "Profile updated successfully";
        header('Location: ../views/profile/view.php');
    } else {
        $_SESSION['error'] = "Failed to update profile";
        header('Location: ../views/profile/edit.php');
    }
} else {
    header('Location: ../views/profile/edit.php');
}
?>