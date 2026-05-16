<?php

function uploadAttachment($conn, $task_id, $user_id, $file_path, $file_name) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $file_path = mysqli_real_escape_string($conn, $file_path);
    $file_name = mysqli_real_escape_string($conn, $file_name);
    
    $sql = "INSERT INTO task_attachments (task_id, uploaded_by, file_path, file_name) 
            VALUES ('$task_id', '$user_id', '$file_path', '$file_name')";
    
    return mysqli_query($conn, $sql);
}

function getTaskAttachments($conn, $task_id) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $sql = "SELECT ta.*, u.name FROM task_attachments ta
            JOIN users u ON ta.uploaded_by = u.id
            WHERE ta.task_id = '$task_id'
            ORDER BY ta.uploaded_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $attachments = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($attachments, $row);
        }
    }
    return $attachments;
}

?>