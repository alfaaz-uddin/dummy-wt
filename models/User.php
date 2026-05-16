<?php

function registerUser($conn, $name, $email, $phone, $password) {
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    $phone = mysqli_real_escape_string($conn, $phone);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    
    $sql = "INSERT INTO users (name, email, phone, password_hash, role) 
            VALUES ('$name', '$email', '$phone', '$password_hash', 'member')";
    
    return mysqli_query($conn, $sql);
}

function getUserByEmail($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT * FROM users WHERE email = '$email' AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getUserById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM users WHERE id = '$id' AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function emailExists($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

function updateProfile($conn, $id, $name, $phone, $profile_pic = null) {
    $id = mysqli_real_escape_string($conn, $id);
    $name = mysqli_real_escape_string($conn, $name);
    $phone = mysqli_real_escape_string($conn, $phone);
    
    if ($profile_pic) {
        $profile_pic = mysqli_real_escape_string($conn, $profile_pic);
        $sql = "UPDATE users SET name = '$name', phone = '$phone', profile_pic = '$profile_pic' WHERE id = '$id'";
    } else {
        $sql = "UPDATE users SET name = '$name', phone = '$phone' WHERE id = '$id'";
    }
    
    return mysqli_query($conn, $sql);
}

function changePassword($conn, $id, $newPassword) {
    $id = mysqli_real_escape_string($conn, $id);
    $password_hash = password_hash($newPassword, PASSWORD_BCRYPT);
    $sql = "UPDATE users SET password_hash = '$password_hash' WHERE id = '$id'";
    return mysqli_query($conn, $sql);
}

?>