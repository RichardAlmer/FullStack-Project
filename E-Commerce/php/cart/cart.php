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

    // Minus-Button
    $message = "";
    $class = "";
    if(isset($_POST['minus'])){
        $itemId = $_POST['itemId'];
        $sql = "DELETE FROM cart_item WHERE pk_cart_item_id = {$itemId}";
        if ($conn->query($sql) === true ) {
            $class = "success";
            // $message = "One Item Removed";
        } else {
            $class = "danger";
            $message = "Error. Try again: <br>" . $conn->error;
        }
    }

    // Plus Button
    if(isset($_POST['plus'])){
        $productId = $_POST['productId'];
        $userId = $_POST['userId'];
        $quantity = $_POST['quantity'];
        $sql = "INSERT INTO cart_item (quantity, fk_product_id, fk_user_id) VALUES ($quantity, $productId, $userId)";
        if ($conn->query($sql) === true ) {
            $class = "success";
            // $message = "One Item Added";
        } else {
            $class = "danger";
            $message = "Error. Try again: <br>" . $conn->error;
        }
    }

    // Delete Button
    if(isset($_POST['delete'])){
        $productId = $_POST['productId'];
        $sql = "DELETE FROM cart_item WHERE fk_product_id = {$productId}";
        if ($conn->query($sql) === true ) {
            $class = "success";
            $message = "All Items Removed";
        } else {
            $class = "danger";
            $message = "Error. Try again: <br>" . $conn->error;
        }
    }

    // Print Cart Items
    $itemId = "";
    $item = "";
    $quantity = "";
    $productId = "";
    $allPrice = array();
    $allDiscountPrice = array();
    $sql = "SELECT DISTINCT quantity, fk_product_id, name, image, price, discount_procent, status FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id WHERE fk_user_id = {$userId}";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $sqlA = "SELECT AVG(fk_product_id), COUNT(name) FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id WHERE fk_user_id = {$userId} AND name = '{$row['name']}'";
            $resultA = mysqli_query($conn, $sqlA);
            while($rowA = mysqli_fetch_array($resultA, MYSQLI_ASSOC)){
                $quantity .= $rowA['COUNT(name)'];
                $productId = $rowA['AVG(fk_product_id)'];
            }
            $sqlItemId = "SELECT pk_cart_item_id FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id WHERE fk_user_id = {$userId} AND name = '{$row['name']}' ORDER BY pk_cart_item_id DESC LIMIT 1";
            $resultItemId = mysqli_query($conn, $sqlItemId);
            while($rowItemId = mysqli_fetch_array($resultItemId, MYSQLI_ASSOC)){
                $itemId.= $rowItemId['pk_cart_item_id'];
            }
            $amount = "";
            $discountPrice = "";
            $price = ($row['price'] * $quantity);
            $discount = $row['discount_procent'];
            switch($discount){
                case 0:
                    $amount = 0;
                    $discountPrice = $price;
                    break;
                case 10:
                    $amount = ($price * 0.1);
                    $discountPrice = ($price - $amount);
                    break;
                case 15:
                    $amount = ($price * 0.15);
                    $discountPrice = ($price - $amount);
                    break;
                case 20:
                    $amount = ($price * 0.2);
                    $discountPrice = ($price - $amount);
                    break;
                case 25:
                    $amount = ($price * 0.25);
                    $discountPrice = ($price - $amount);
                    break;
                case 50:
                    $amount = ($price * 0.5);
                    $discountPrice = ($price - $amount);
                    break;
                case 75:
                    $amount = ($price * 0.75);
                    $discountPrice = ($price - $amount);
                    break;
            }
            array_push($allDiscountPrice, $discountPrice);
            array_push($allPrice, $price);
            if($productId == $row['fk_product_id']){
                $item .= "
                    <div class='col-12 col-lg-2 py-2'>
                        <a href='../product/product-details.php?id=".$productId."'><img class='img-thumbnail rounded-circle' src='../../img/product_images/".$row['image']."'></a>
                    </div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'>" .$row['name']."</div>
                    <div class='col-12 col-md-4 col-lg-1 py-2 py-md-3 py-lg-0'>" .$row['status']."</div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'>
                        <div class='row'>
                            <form class='d-none' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'>
                                <input type='hidden' name='itemId' value='$itemId'/>
                                <button class='col-2 del btn fw-bold fs-3 px-1' type='submit' name='minus'> - </button>
                            </form>

                            <div class='col-3 fw-bold fs-5 py-3 text-center px-1'><span class='my_text_maincolor border py-2 px-3 rounded-circle'>".$quantity."</span></div>

                            <form class='d-none' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'>
                                <input type='hidden' name='productId' value='$productId'/>
                                <input type='hidden' name='userId' value='$userId'/>
                                <input type='hidden' name='quantity' value='1'/>
                                <input class='quantityCart' type='hidden' name='quantityCart' value='$quantity'/>
                                <button class='col-2 del btn fw-bold fs-3 px-3' type='submit' name='plus'> + </button>
                            </form>

                            <form class='d-none' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'>
                                <input type='hidden' name='productId' value='$productId'/>
                                <button class='col-2 del btn fw-bold fs-5 ps-3' type='submit' name='delete'> X </button>
                            </form>
                        </div>
                    </div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'><span class='my_text_maincolor'>" .$row['discount_procent']."% | ".number_format($amount, 2, ',', ' ')."€</span></div>
                    <div class='col-12 col-md-4 col-lg-1 py-2 py-md-3 py-lg-0'>" .number_format($price, 2, ',', ' ')."€</div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'>" .number_format($discountPrice, 2, ',', ' ')."€</div>
                ";
                $quantity = "";
                $itemId = "";
            }
        }
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <?php require_once '../../php/components/boot.php'?>
    <link rel="stylesheet" href="../../style/main-style.css" />
</head>
<body>
    <?php 
        require_once '../../php/components/header.php';
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
    <div class="container">
        <div class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Cart</div>
            <div class="py-2 text-<?=$class;?>"><?php echo ($message) ?? ""; ?></div>

            <?php if($item !== ""){ ?>
            <div class="manageProduct">
                <table class='table table-striped'>
                    <div class='fw-bold d-none d-lg-flex row my-3'>
                        <div class="col-12 col-lg-2">Picture</div>
                        <div class="col-12 col-lg-2">Name</div>
                        <div class="col-12 col-lg-1">Status</div>
                        <div class="col-12 col-lg-2">Quantity</div>
                        <div class="col-12 col-lg-2">Discount</div>
                        <div class="col-12 col-lg-1">Price</div>
                        <div class="col-12 col-lg-2">Discount Price</div>
                    </div>
                    <div class='row my-3 align-items-center text-center text-md-start'>
                        <?= $item;?>
                    </div>

                    <hr>

                    <div class='fw-bold row my-3 text-center text-md-end'>
                        <div class="col-12 py-1">
                            <div>Subtotal: <?php echo number_format(array_sum($allPrice), 2, ',', ' ') ?>€</div>
                        </div>
                        <div class="col-12 py-1">
                            <div class='my_text_maincolor'>Discount: -<?php echo number_format((array_sum($allPrice) - array_sum($allDiscountPrice)), 2, ',', ' ') ?>€</div>
                        </div>
                        <div class="col-12 py-1">
                            <div class="text-uppercase fs-5">Total: <?php echo number_format(array_sum($allDiscountPrice), 2, ',', ' ') ?>€</b></div>
                        </div>
                        <div>
                            <a href="purchase.php"><button type="button" class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-4 text-white my-4">Checkout</button></a>
                        </div>
                    </div>
                </table>
            </div>

            <?php }else{ ?>
                <div class="my-5">
                    <div class="col-12 fs_6 text-uppercase my-2">Your cart is empty!</div>
                    <div class="col-12 col-md-2 my-5 py-2">
                        <img src="../../img/general_images/cart.png" alt="">
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
    <?php 
        require_once '../../php/components/footer.php';
        footer("../../");
        require_once '../../php/components/boot-javascript.php';
    ?>
</body>
</html>


