<?php
$localhost = "173.212.235.205";
$username = "obermaye_userTeam5";
$password = "7hNNxNwdby9CCmy";
$dbname = "obermaye_WF-BackEnd-5";
// create connection
$connect = new mysqli($localhost, $username, $password, $dbname);
// check connection
if ($connect->connect_error) {
    die("Connection failed: " . $connect->connect_error);
} else {
    echo "Successfully Connected";
}
?>