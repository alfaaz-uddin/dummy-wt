<?php

function logTime($conn, $task_id, $user_id, $hours, $note = null) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $hours = mysqli_real_escape_string($conn, $hours);
    $note = $note ? mysqli_real_escape_string($conn, $note) : '';
    
    $sql = "INSERT INTO time_logs (task_id, user_id, hours_logged, note) 
            VALUES ('$task_id', '$user_id', '$hours', '$note')";
    
    return mysqli_query($conn, $sql);
}

function getTaskTimeLogs($conn, $task_id) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "SELECT tl.*, u.name FROM time_logs tl
            JOIN users u ON tl.user_id = u.id
            WHERE tl.task_id = '$task_id'
            ORDER BY tl.logged_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $logs = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($logs, $row);
        }
    }
    return $logs;
}

function getUserTaskTimeLogs($conn, $task_id, $user_id) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $sql = "SELECT * FROM time_logs 
            WHERE task_id = '$task_id' AND user_id = '$user_id'
            ORDER BY logged_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $logs = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($logs, $row);
        }
    }
    return $logs;
}

function getUserWeeklyHours($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "SELECT SUM(hours_logged) as total_hours FROM time_logs 
            WHERE user_id = '$user_id' 
            AND logged_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
    
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_hours'] ?? 0;
}

function getUserTotalHours($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "SELECT SUM(hours_logged) as total_hours FROM time_logs WHERE user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total_hours'] ?? 0;
}

?>