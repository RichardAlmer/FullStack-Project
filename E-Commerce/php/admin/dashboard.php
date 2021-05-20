<?php
    // session_start();
    // if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
    //     header("Location: ../../index.php");
    //     exit;
    // }
    // if (isset($_SESSION["user"])) {
    //     header("Location: ../product/product-catalog.php");
    //     exit;
    // }
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php require_once '../components/boot.php' ?>
    <link rel='stylesheet' type='text/css' href='../../style/main-style.css'>
</head>

<body>
    <?php
    require_once '../components/header.php';
    navbar("../../", "../");
    ?>

    <div id="container">
        <div id="content">
            <h1>Admin Dashboard</h1>
            <a href='../user/users.php'>
                <div>Manage Users</div>
            </a>
            <a href='../product/products.php'>
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
    <?php require_once '../../php/components/boot-javascript.php'?>
</body>

</html>