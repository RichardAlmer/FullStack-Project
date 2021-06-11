<?php
$serverName = "127.0.0.1";
$userName = "root";
$password = "";
$databaseName = "wf-backend-5-ecommerce";

$conn = mysqli_connect($serverName, $userName, $password, $databaseName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "DB connected";
}
