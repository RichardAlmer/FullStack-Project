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
                <tr>
                    <td><a href='../product/product-details.php?id=".$productId."'><img class='img-thumbnail' src='../../img/product_images/".$row['image']."'></a></td>
                    <td>" .$row['name']."</td>
                    <td>" .$row['status']."</td>
                    <td>
                        <form class='my-3' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'>
                            <input type='hidden' name='itemId' value='$itemId'/>
                            <button class='del btn btn-primary rounded-circle  my-auto ms-3 fw-bold' type='submit' name='minus'> - </button>".$quantity."
                        </form>
                        <form class='my-3' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'>
                            <input type='hidden' name='productId' value='$productId'/>
                            <input type='hidden' name='userId' value='$userId'/>
                            <input type='hidden' name='quantity' value='1'/>
                            <button class='del btn btn-primary rounded-circle  my-auto ms-3 fw-bold' type='submit' name='plus'> + </button>
                        </form>
                        <form class='my-3' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."' autocomplete='off'>
                            <input type='hidden' name='productId' value='$productId'/>
                            <button class='del btn btn-danger rounded-circle  my-auto ms-3 fw-bold' type='submit' name='delete'> X </button>
                        </form>
                    </td>
                    <td>" .$row['discount_procent']."% | ".$amount."€</td>
                    <td>" .$price."€</td>
                    <td>" .$discountPrice."€</td>
                </tr>
                ";
                $quantity = "";
                $itemId = "";
            }
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
        if(isset($_SESSION['admin'])){
            $id = $_SESSION['admin'];
        } else if(isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
        }
        navbar("../../", "../", $id);
    ?>
    <div class="container">
    <div class="my-2 text-<?=$class;?>"><?php echo ($message) ?? ""; ?></div>
        <h1>Cart</h1>
        <?php if($item !== ""){ ?>
        <div class="manageProduct w-75 mt-3">
            <table class='table table-striped'>
               <thead class='table-success'>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Quantity</th>
                        <th>Discount</th>
                        <th>Price</th>
                        <th>Discount Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $item;?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>-<?php echo (array_sum($allPrice) - array_sum($allDiscountPrice)) ?>€</td>
                        <td><?php echo array_sum($allPrice) ?>€</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>Total: </td>
                        <td><b><?php echo array_sum($allDiscountPrice) ?>€</b></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><a href="purchase.php"><button type="button" class="btn btn-primary">Purchase</button></a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php }else{ ?>
            <div>
                <h2>Your cart is empty!</h2>
            </div>
        <?php } ?>
    </div>
    <?php 
        require_once '../../php/components/footer.php';
        footer("../../");
        require_once '../../php/components/boot-javascript.php';
    ?>
</body>
</html>


