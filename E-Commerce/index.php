<?php
require_once "php/components/db_connect.php";
session_start();
function getAllProducts()
{
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM product WHERE discount_procent != 0 AND status = 'active' ORDER BY RAND()
    LIMIT 6");
    return $result;
}
$products = getAllProducts();

$userId = '';
if(isset($_SESSION['admin'])){
    $userId = $_SESSION['admin'];
} else if(isset($_SESSION['user'])){
    $userId = $_SESSION['user'];
}

$cartCount = "";
$image = "";
if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
    $sqlCart = "SELECT COUNT(quantity), profile_image FROM cart_item INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_user_id = {$userId}";
    $result = $conn->query($sqlCart);
        if ($result->num_rows == 1){
            $data = $result->fetch_assoc();
            $image = $data['profile_image'];
            if($data['COUNT(quantity)'] != 0){
                $cartCount = $data['COUNT(quantity)'];
            }
        }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <?php require_once 'php/components/boot.php' ?>
    <link rel="stylesheet" href="style/main-style.css" />
</head>

<body>
    <?php
        require_once 'php/components/header.php';
        $id = "";
        $session = "";
        if(isset($_SESSION['admin'])){
            $id = $_SESSION['admin'];
            $session = "admin";
        } else if(isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
            $session = "user";
        }
        navbar("", "php/", "../", $id, $session, $cartCount, $image);
    ?>
    <div class="container my-1 p-md-2">
        <div class="row px-md-5 my-5 pt-5">
            <div class="py-5 col-12 col-lg-6">
                <div class="text-uppercase banner_text fw-bold">Find your <br><span class="my_text_maincolor">perfect gift</span> <br>here</div>
                <div class="my_text_lightgray my-4">More than 10 000 products to buy from home now</div>
                
                <a href="php/product/product-catalog.php">
                    <div class="btn bg_gray rounded-pill my-4 col-12 col-md-6 py-3 text-white bg_hover">To all products</div>
                </a>
                
            </div>
            <div class="py-5 col-12 col-lg-6 ">
                <div class="row">
                    <img src="img/general_images/banner.png" alt="">
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row my-3">
            <div class="col-12 text-center fs_6 text-uppercase my-2">Our bestsellers. <span class="my_text_maincolor">Discover more</span></div>
            <div class="col-12 text-center my_text_lightgray">Find thing you'll love</div>
        </div>
    </div>

    <div class="row row_width py-3 mb-5">

        <?php foreach ($products as $product) : ?>
            
            <div class="col-12 col-md-4 col-lg-2 py-2 box_height">
            <a href='php/product/product-details.php?id=<?= $product['pk_product_id'] ?>'>
                <div class="square">
                    <img src="img/product_images/<?= $product['image'] ?>" class="content" alt="">
                </div>
                <div class="row py-3 text-center">
                    <div class="col-12 fs-5 my-2"><?= $product['name'] ?></div>
                    <div class="col-12 my-1 my_text_maincolor"><?= $product['category'] ?></div>
                    <div class="col-12"><?= $product['brand'] ?></div>
                    <div class="col-12 text-decoration-line-through mt-2">€<?= $product['price']?></div>

                    <div class="col-12 fw-bold">€<?= number_format(($product['price'] - $product['price'] * $product['discount_procent'] / 100), 2, ',', ' ') .' <span class="my_text_maincolor">(-'. $product['discount_procent'] ?>%)</div>
                </div>
                </a>
            </div>
            
        <?php endforeach; ?>
    </div>
    <?php
    require_once 'php/components/footer.php';
    footer("");
    require_once 'php/components/boot-javascript.php';
    ?>
</body>

</html>