<?php

function getProjectTasks($conn, $project_id) {
    $project_id = mysqli_real_escape_string($conn, $project_id);
    $sql = "SELECT t.*, u.name as assigned_name, u.profile_pic
            FROM tasks t
            LEFT JOIN users u ON t.assigned_to = u.id
            WHERE t.project_id = '$project_id'
            ORDER BY t.priority DESC, t.created_at ASC";
    
    $result = mysqli_query($conn, $sql);
    $tasks = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($tasks, $row);
        }
    }
    return $tasks;
}

function getTasksByStatus($conn, $project_id) {
    $tasks = getProjectTasks($conn, $project_id);
    
    $result = [
        'todo' => [],
        'in_progress' => [],
        'review' => [],
        'done' => []
    ];
    
    foreach ($tasks as $task) {
        $result[$task['status']][] = $task;
    }
    
    return $result;
}

function getTaskById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT t.*, u.name as assigned_name, c.name as created_name
            FROM tasks t
            LEFT JOIN users u ON t.assigned_to = u.id
            LEFT JOIN users c ON t.created_by = c.id
            WHERE t.id = '$id'";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getUserAssignedTasks($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "SELECT t.*, p.name as project_name, p.id as project_id, w.id as workspace_id
            FROM tasks t
            JOIN projects p ON t.project_id = p.id
            JOIN workspaces w ON p.workspace_id = w.id
            WHERE t.assigned_to = '$user_id'
            ORDER BY t.due_date ASC, t.priority DESC";
    
    $result = mysqli_query($conn, $sql);
    $tasks = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($tasks, $row);
        }
    }
    return $tasks;
}

function updateTaskStatus($conn, $task_id, $status) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $status = mysqli_real_escape_string($conn, $status);
    $sql = "UPDATE tasks SET status = '$status' WHERE id = '$task_id'";
    return mysqli_query($conn, $sql);
}

function toggleTaskBlocked($conn, $task_id, $is_blocked, $reason = null) {
    $task_id = mysqli_real_escape_string($conn, $task_id);
    $reason = $reason ? mysqli_real_escape_string($conn, $reason) : '';
    $is_blocked = $is_blocked ? 1 : 0;
    
    $sql = "UPDATE tasks SET is_blocked = $is_blocked, blocked_reason = '$reason' WHERE id = '$task_id'";
    return mysqli_query($conn, $sql);
}

?>