<?php
$conn = mysqli_connect('localhost','root','','maverick_db');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}


?>