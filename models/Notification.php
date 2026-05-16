<?php

function createNotification($conn, $user_id, $type, $message, $link = null) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $type = mysqli_real_escape_string($conn, $type);
    $message = mysqli_real_escape_string($conn, $message);
    $link = $link ? mysqli_real_escape_string($conn, $link) : '';
    
    $sql = "INSERT INTO notifications (user_id, type, message, link) 
            VALUES ('$user_id', '$type', '$message', '$link')";
    
    return mysqli_query($conn, $sql);
}

function getUserNotifications($conn, $user_id, $limit = 20) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $limit = mysqli_real_escape_string($conn, $limit);
    
    $sql = "SELECT * FROM notifications 
            WHERE user_id = '$user_id'
            ORDER BY created_at DESC
            LIMIT $limit";
    
    $result = mysqli_query($conn, $sql);
    $notifications = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($notifications, $row);
        }
    }
    return $notifications;
}

function getUnreadNotifications($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "SELECT COUNT(*) as count FROM notifications 
            WHERE user_id = '$user_id' AND is_read = 0";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['count'];
}

function markNotificationAsRead($conn, $notification_id) {
    $notification_id = mysqli_real_escape_string($conn, $notification_id);
    $sql = "UPDATE notifications SET is_read = 1 WHERE id = '$notification_id'";
    return mysqli_query($conn, $sql);
}

function markAllNotificationsAsRead($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "UPDATE notifications SET is_read = 1 WHERE user_id = '$user_id'";
    return mysqli_query($conn, $sql);
}

?>