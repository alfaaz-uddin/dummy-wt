<?php

function connect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "task_management_db";
    
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    return $conn;
}

?>