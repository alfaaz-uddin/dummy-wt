<?php

// Router for the application


// Check if user is logged in
$public_pages = ['login', 'register'];
$is_public = in_array($page, $public_pages);

if (!isset($_SESSION['user_id']) && !$is_public) {
    header('Location: index.php?page=login');
    exit;
}

?>