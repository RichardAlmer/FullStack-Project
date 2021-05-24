<?php
    session_start();
    if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
        header("Location: ../../index.php");
        exit;
    }
    if (isset($_SESSION["user"])) {
        header("Location: ../product/product-catalog.php");
        exit;
    }
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
        $id = "";
        $session = "";
        if(isset($_SESSION['admin'])){
            $id = $_SESSION['admin'];
            $session = "admin";
        } else if(isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
            $session = "user";
        }
        navbar("../../", "../", "../", $id, $session);
    ?>

    <div id="container" class="container">
        <div id="content" class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Admin Dashboard</div>
            <div class="py-5 my-4">
                <div class="col-12">
                    <a class="fs-5 btn btn bg_gray bg_hover rounded-pill col-12 col-md-3 py-2 px-4 text-white my-2" href='../user/users.php'>Manage Users</a>
                </div>
                <div class="col-12">
                    <a class="fs-5 btn btn bg_gray bg_hover rounded-pill col-12 col-md-3 py-2 px-4 text-white my-2" href='../product/products.php'>Manage Products</a>
                </div>
                <div class="col-12">
                    <a class="fs-5 btn btn bg_gray bg_hover rounded-pill col-12 col-md-3 py-2 px-4 text-white my-2" href='reviews.php'>Manage Reviews</a>
                </div>
                <div class="col-12">
                    <a class="fs-5 btn btn bg_lightgray bg_hover rounded-pill col-12 col-md-3 py-2 px-4 text-white my-2" href='statistics.php'>View Statistics</a>
                </div>
            </div>
        </div>
    </div>
    <?php 
        require_once '../components/footer.php';
        footer("../../");
        require_once '../components/boot-javascript.php';
    ?>
</body>

</html>