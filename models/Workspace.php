<?php

function createWorkspace($conn, $name, $description, $owner_id) {
    $name = mysqli_real_escape_string($conn, $name);
    $description = mysqli_real_escape_string($conn, $description);
    $owner_id = mysqli_real_escape_string($conn, $owner_id);
    $invite_code = strtoupper(bin2hex(random_bytes(4)));
    
    $sql = "INSERT INTO workspaces (name, description, owner_id, invite_code) 
            VALUES ('$name', '$description', '$owner_id', '$invite_code')";
    
    if (mysqli_query($conn, $sql)) {
        $workspace_id = mysqli_insert_id($conn);
        // Add owner as member
        $sql_member = "INSERT INTO workspace_members (workspace_id, user_id, workspace_role) 
                       VALUES ('$workspace_id', '$owner_id', 'admin')";
        mysqli_query($conn, $sql_member);
        return $workspace_id;
    }
    return false;
}

function getWorkspaceByInviteCode($conn, $code) {
    $code = mysqli_real_escape_string($conn, $code);
    $sql = "SELECT * FROM workspaces WHERE invite_code = '$code' AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getWorkspaceById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM workspaces WHERE id = '$id' AND is_active = 1";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return null;
}

function getUserWorkspaces($conn, $user_id) {
    $user_id = mysqli_real_escape_string($conn, $user_id);
    $sql = "SELECT w.* FROM workspaces w
            JOIN workspace_members wm ON w.id = wm.workspace_id
            WHERE wm.user_id = '$user_id' AND w.is_active = 1
            ORDER BY w.created_at DESC";
    
    $result = mysqli_query($conn, $sql);
    $workspaces = array();
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($workspaces, $row);
        }
    }
    return $workspaces;
}

function joinWorkspace($conn, $workspace_id, $user_id) {
    $workspace_id = mysqli_real_escape_string($conn, $workspace_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $sql = "INSERT INTO workspace_members (workspace_id, user_id, workspace_role) 
            VALUES ('$workspace_id', '$user_id', 'member')
            ON DUPLICATE KEY UPDATE workspace_role = 'member'";
    
    return mysqli_query($conn, $sql);
}

function isMember($conn, $workspace_id, $user_id) {
    $workspace_id = mysqli_real_escape_string($conn, $workspace_id);
    $user_id = mysqli_real_escape_string($conn, $user_id);
    
    $sql = "SELECT id FROM workspace_members 
            WHERE workspace_id = '$workspace_id' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $sql);
    return mysqli_num_rows($result) > 0;
}

?>