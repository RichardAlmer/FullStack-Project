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
    $sql = "SELECT pk_review_id, rating, title, create_datetime, name, pk_product_id FROM review INNER JOIN product ON fk_product_id = pk_product_id ORDER BY create_datetime DESC";
    $result = mysqli_query($conn ,$sql);
    $tbody='';
    if(mysqli_num_rows($result)  > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $stars = getStars(round($row['rating']));
            $tbody .= "<tr>
                    <td>".$row['create_datetime']."</td>
                    <td>".$row['name']."</td>
                    <td>".$row['title']."</td>
                    <td>".$stars."</td>
                    <td><a href='../product/product-details.php?id=".$row['pk_product_id']."'><button class='btn btn-warning btn-sm' type='button'>View</button></a><a href='delete-review.php?id=".$row['pk_review_id']."'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a></td>
                </tr>";
        };
    } else {
        $tbody =  "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
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
        .manageProduct {          
            margin: auto;
        }
        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }
        td {          
            text-align: left;
            vertical-align: middle;

        }
        tr {
            text-align: left;
        }
    </style>
</head>
<body>
    <div id="container">
        <?php require_once '../components/header.php'; 
        navbar("../../", "../");?>
        <div id="content">
            <h1>Manage Reviews</h1>
            <table class='table table-striped'>
            <thead class='table-success'>
                    <tr>
                        <th>Created on</th>
                        <th>Product Name</th>
                        <th>Review Title</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $tbody;?>
                </tbody>
            </table>
            <a href='../product/products.php'>Back to Manage Products</a>
        </div>
    </div>
</body>
</html>
