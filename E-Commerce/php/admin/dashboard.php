<?php
    // To Do: Session stuff ---------------------------------------
    // session_start();
    // if ($_SESSION['user_role'] != 'admin') {
    //     header("Location: home.php");
    //     exit;
    // }
    
    // To Do: DB stuff  ---------------------------------------
    // require_once 'components/db_connect.php'; //-----------------------------------
    // $connect->close();
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php require_once '../components/boot.php'?>
    <!-- <link rel='stylesheet' type='text/css' href='../../style/main.css'> -->
</head>

<body>
    <div id="container">
        <?php include '../components/navbar.php' ?>
        <div id="content">
            <h1>Admin Dashboard</h1>
            <a href='users.php'>
                <div>Manage Users</div>
            </a>
            <a href='products.php'>
                <div>Manage Products</div>
            </a>
            <a href='reviews.php'>
                <div>Manage Reviews</div>
            </a>
            <a href='statistics.php'>
                <div>View Statistics</div>
            </a>
        </div>
    </div>
</body>

</html>