<?php
$host = "127.0.0.1:3306";
$username = "root";      
$password = "";         
$database = "admin_db";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
