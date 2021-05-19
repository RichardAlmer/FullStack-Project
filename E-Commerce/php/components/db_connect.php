<?php
$serverName = "173.212.235.205";
$userName = "obermaye_userT5";
$password = "394hgid45nHJ8";
$databaseName = "obermaye_wf-backend-5-ecommerce";

$conn = mysqli_connect($serverName, $userName, $password, $databaseName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // echo "DB connected";
}
