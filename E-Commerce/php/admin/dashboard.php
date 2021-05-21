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

    <div id="container" class="container">
        <div id="content" class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Admin Dashboard</div>
            <div class="col-12 py-2">
                <a href='../user/users.php'>Manage Users</a>
            </div>
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
    <?php 
        require_once '../components/footer.php';
        footer("../../");
        require_once '../components/boot-javascript.php';
    ?>
</body>

</html>