<?php

require_once '../components/db_connect.php';
session_start();
$sqlCategories = ("SELECT DISTINCT category FROM product");
$resultCategories = mysqli_query($conn ,$sqlCategories);
$categories=''; 
if(mysqli_num_rows($resultCategories) > 0) {     
    while($row = mysqli_fetch_array($resultCategories, MYSQLI_ASSOC)){ 
        $categories .= "<option value=".$row['category'].">".$row['category']."</option>";
    }
}

$sql = ("SELECT * FROM product ORDER BY pk_product_id DESC");
$result = mysqli_query($conn ,$sql);
$tbody=''; 
if(mysqli_num_rows($result) > 0) {     
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ 
        $sqlR = "SELECT COUNT(rating) AS count FROM review WHERE fk_product_id = $row[pk_product_id]";
        $resultR = mysqli_query($conn ,$sqlR);
        $data = $resultR->fetch_assoc();
        $tbody .= "
        <tr>
            <td>" . $row['name'] . "</td>
            <td>" . $row['category'] . "</td>
            <td>" . $row['brand'] . "</td>
            <th>" . $row['discount_procent'] . "%</th>
            <td>" . $row['status'] . "</td>
            <td>";
        
        if ($data['count'] == 0) {
            $tbody .= "<a href='../admin/reviews.php?id=" . $row['pk_product_id'] . "'>
                        <button class='col-12 col-md-5 btn bg-white bg_hover rounded-pill py-2 px-md-4' type='button'>No Reviews</button></a> ";
        } 
        else if ($data['count'] == 1) {
            $tbody .= "<a href='../admin/reviews.php?id=" . $row['pk_product_id'] . "'>
                        <button class='col-12 col-md-5 btn bg-dark bg_hover rounded-pill py-2 px-md-4 text-white' type='button'>View ".$data['count']." Review</button></a> ";
        } else {
            $tbody .= "<a href='../admin/reviews.php?id=" . $row['pk_product_id'] . "'>
                        <button class='col-12 col-md-5 btn bg-dark bg_hover rounded-pill py-2 px-md-4 text-white' type='button'>View ".$data['count']." Review(s)</button></a> ";
        }
        $tbody .= "<a href='update.php?id=" . $row['pk_product_id'] . "'><button class='col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-4 text-white' type='button'>Update</button></a>
                <a href='delete.php?id=" . $row['pk_product_id'] . "'><button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-4 text-white' type='button'>Delete</button></a>
            </td>
        </tr>";
    };
} else  {
   $tbody =  "<div><center>No Data Available </center></div>";
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
    <title>Manage Products</title>
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
        navbar("../../", "../", "../", $id, $session, $cartCount, $image);
    ?>

    <div id="container" class="container">
        <div id="content" class="my-5 pt-5">
            <div class="col-12 col-md-4 fs_6 text-uppercase my-2">Manage Products</div>

            <a href='../admin/dashboard.php' class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-4">Back to dashboard</a>

            <a href="create.php"><button class='col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-4' type="button">Add Product</button></a>

            <table class='table table-striped'>
                <thead class='bg_maincolor'>
                    <tr>
                        <th class="border-0">Name</th>
                        <th class="border-0">Category</th>
                        <th class="border-0">Brand</th>
                        <th class="border-0">Discount</th>
                        <th class="border-0">Status</th>
                        <th class="border-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $tbody ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php 
        require_once '../../php/components/footer.php';
        footer("../../");
        require_once '../../php/components/boot-javascript.php';
    ?>

</body>

</html>