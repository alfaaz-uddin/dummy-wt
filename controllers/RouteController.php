<?php
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    // Auth
    case 'login':
        require_once 'views/auth/login.php';
        break;
    case 'register':
        require_once 'views/auth/register.php';
        break;

    // Dashboard
    case 'dashboard':
        require_once 'views/dashboard/index.php';
        break;
    case 'my-tasks':
        require_once 'views/dashboard/tasks.php';
        break;

    // Notifications
    case 'notifications':
        require_once 'views/notifications/list.php';
        break;

    // Workspaces
    case 'workspace':
        $workspace_id = $_GET['id'] ?? 0;
        require_once 'views/workspace/list.php';
        break;

    // Projects
    case 'project':
        $project_id = $_GET['id'] ?? 0;
        require_once 'views/project/board.php';
        break;

    // Tasks
    case 'task-detail':
        require_once 'views/project/task-detail.php';
        break;

    // Profile
    case 'profile':
        require_once 'views/profile/view.php';
        break;
    case 'edit-profile':
        require_once 'views/profile/edit.php';
        break;
    case 'change-password':
        require_once 'views/profile/change-password.php';
        break;

    default:
        require_once 'views/dashboard/index.php';
        break;
}
?>