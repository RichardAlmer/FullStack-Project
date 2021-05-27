<?php
    require_once '../components/db_connect.php';
    require_once '../product/actions/helper-functions.php';

    session_start();
    if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
        header("Location: ../../index.php");
        exit;
    }
    if (isset($_SESSION["user"])) {
        header("Location: ../product/product-catalog.php");
        require_once 'actions/helper-functions.php';
        exit;
    }

    //$id = $_GET['id'];
    // $sql = "SELECT pk_review_id, rating, title, create_datetime, name, pk_product_id FROM review INNER JOIN product ON fk_product_id = pk_product_id WHERE review.fk_product_id = {$id} ORDER BY create_datetime DESC";
    $sql = "SELECT pk_review_id, fk_user_id, rating, title, create_datetime, name, pk_product_id FROM review INNER JOIN product ON fk_product_id = pk_product_id ORDER BY create_datetime DESC";
    $result = mysqli_query($conn ,$sql);
    $tbody='';
    if(mysqli_num_rows($result)  > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $stars = getStars(round($row['rating']));
            $tbody .= "<tr>
                    <td>".$row['create_datetime']."</td>
                    <td>".$row['fk_user_id']."</td>
                    <td>".$row['name']."</td>
                    <td>".$row['title']."</td>
                    <td>".$stars."</td>
                    <td><a href='../product/product-details.php?id=".$row['pk_product_id']."'><button class='col-12 col-md-auto btn bg-dark bg_hover rounded-pill py-2 px-md-4 text-white me-1' type='button'>View</button></a><a href='delete-review.php?id=".$row['pk_review_id']."'><button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-4 text-white' type='button'>Delete</button></a></td>
                </tr>";
        };
    } else {
        $tbody =  "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
    }
    $userId = '';
    if(isset($_SESSION['admin'])){
        $userId = $_SESSION['admin'];
    } else if(isset($_SESSION['user'])){
        $userId = $_SESSION['user'];
    }
    $cartCount = "";
    $image = "";
    $sqlCart = "SELECT COUNT(quantity), profile_image FROM cart_item INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_user_id = {$userId}";
    $result = $conn->query($sqlCart);
        if ($result->num_rows == 1){
            $data = $result->fetch_assoc();
            $image = $data['profile_image'];
            if($data['COUNT(quantity)'] != 0){
                $cartCount = $data['COUNT(quantity)'];
            }
        }

    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reviews</title>
    <?php require_once '../../php/components/boot.php'?>
    <link rel="stylesheet" href="../../style/main-style.css" />
    <style type= "text/css">
        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }
    </style>
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
        navbar("../../", "../", "../", $id, $session, $cartCount, $image);
    ?>

    <div id="container"  class="container">
        <div id="content" class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Manage Reviews</div>
            <a href="dashboard.php"><button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-4' type="button">Back to dashboard</button></a>
            <a href='../product/products.php' class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-4">Back to Manage Products</a>

            <table class='table table-striped'>
                <thead class='bg_maincolor'>
                    <tr>
                        <th class="border-0">Created on</th>
                        <th class="border-0">By</th>
                        <th class="border-0">Product Name</th>
                        <th class="border-0">Review Title</th>
                        <th class="border-0">Rating</th>
                        <th class="border-0">Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?= $tbody;?>
                </tbody>
            </table>
        </div>
    </div>

    <?php 
        require_once '../components/footer.php';
        footer("../../");
        require_once '../components/boot-javascript.php';
    ?>
</body>
</html>
