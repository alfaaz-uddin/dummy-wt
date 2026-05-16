<?php

function getTaskComments($conn, $task_id, $show_internal = false) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    
    if ($show_internal) {
        $sql = "SELECT c.*, u.name, u.profile_pic
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.task_id = '$task_id'
                ORDER BY c.created_at DESC";
    } else {
        $sql = "SELECT c.*, u.name, u.profile_pic
                FROM comments c
                JOIN users u ON c.user_id = u.id
                WHERE c.task_id = '$task_id' AND c.is_internal = 0
                ORDER BY c.created_at DESC";
    }
    
    $result = mysqli_query($conn, $sql);
    $comments = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($comments, $row);
        }
    }
    return $comments;
}

function createComment($conn, $task_id, $user_id, $body, $is_internal = false) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $body = mysqli_real_escape_string($conn, $body);
    $is_internal = $is_internal ? 1 : 0;
    
    $sql = "INSERT INTO comments (task_id, user_id, body, is_internal) 
            VALUES ('$task_id', '$user_id', '$body', $is_internal)";
    
    return mysqli_query($conn, $sql) ? mysqli_insert_id($conn) : false;
}

function deleteComment($conn, $comment_id, $user_id) {
    $comment_id = mysqli_real_escape_string($conn, $comment_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $sql = "DELETE FROM comments WHERE id = '$comment_id' AND user_id = '$user_id'";
    return mysqli_query($conn, $sql);
}

function updateComment($conn, $comment_id, $body, $user_id) {
    $comment_id = mysqli_real_escape_string($conn, $comment_id);
    $body = mysqli_real_escape_string($conn, $body);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $sql = "UPDATE comments SET body = '$body' WHERE id = '$comment_id' AND user_id = '$user_id'";
    return mysqli_query($conn, $sql);
}

?>