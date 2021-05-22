<?php
    require_once '../components/db_connect.php';

    session_start();
    if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
        header("Location: ../../index.php");
        exit;
    }
    // if (isset($_SESSION["admin"])) {
    //     header("Location: ../product/product-catalog.php");
    //     exit;
    // }

    $userId = '';
    if(isset($_SESSION['admin'])){
        $userId = $_SESSION['admin'];
    } else if(isset($_SESSION['user'])){
        $userId = $_SESSION['user'];
    }

    $item = "";
    $sql = "SELECT quantity, name, image, price, discount_procent, status FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id WHERE fk_user_id = {$userId}";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $item .= "
            <tr>
                <td><img class='img-thumbnail' src='".$row['image']."'></td>
                <td>" .$row['name']."</td>
                <td>" .$row['status']."</td>
                <td>" .$row['price']."€</td>
                <td>" .$row['discount_procent']."%</td>
                <td>" .$row['quantity']."x</td>
            </tr>
            ";
        }
    }

    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <?php require_once '../../php/components/boot.php'?>
    <link rel="stylesheet" href="../../style/main-style.css" />
</head>
<body>
    <?php 
        require_once '../../php/components/header.php';
        if(isset($_SESSION['admin'])){
            $id = $_SESSION['admin'];
        } else if(isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
        }
        navbar("../../", "../", $id);
    ?>
    <div class="container">
        <h1>Cart</h1>
        <div class="manageProduct w-75 mt-3">
            <table class='table table-striped'>
               <thead class='table-success'>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $item;?>
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