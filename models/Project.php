<?php

function getProjectById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT p.*, w.name as workspace_name, u.name as client_name
            FROM projects p
            LEFT JOIN workspaces w ON p.workspace_id = w.id
            LEFT JOIN users u ON p.client_id = u.id
            WHERE p.id = '$id'";
    
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getUserProjects($conn, $user_id, $workspace_id = null) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    if ($workspace_id) {
        $workspace_id = mysqli_real_escape_string($conn, $workspace_id);
        $sql = "SELECT DISTINCT p.* FROM projects p
                JOIN project_members pm ON p.id = pm.project_id
                WHERE pm.user_id = '$user_id' AND p.workspace_id = '$workspace_id'
                ORDER BY p.created_at DESC";
    } else {
        $sql = "SELECT DISTINCT p.* FROM projects p
                JOIN project_members pm ON p.id = pm.project_id
                WHERE pm.user_id = '$user_id'
                ORDER BY p.created_at DESC";
    }
    
    $result = mysqli_query($conn, $sql);
    $projects = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($projects, $row);
        }
    }
    return $projects;
}

function getWorkspaceProjects($conn, $workspace_id) {
    $workspace_id = mysqli_real_escape_string($conn, $workspace_id);
    $sql = "SELECT * FROM projects WHERE workspace_id = '$workspace_id' ORDER BY created_at DESC";
    $result = mysqli_query($conn, $sql);
    $projects = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($projects, $row);
        }
    }
    return $projects;
}

?>