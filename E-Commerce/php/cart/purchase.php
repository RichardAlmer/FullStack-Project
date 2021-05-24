<?php
    require_once '../components/db_connect.php';
    require_once '../components/emailService.php';

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

    $purchaseId = "";
    $class1 = "";
    $class2 = "";
    $class3 = "";
    $massage1 = "";
    $massage2 = "";
    $massage3 = "";
    $firstName = "";
    $lastName = "";
    $address = "";
    $postcode = "";
    $city = "";
    $country = "";
    $itemId = "";
    $item = "";
    $itemBill = "";
    $quantity = "";
    $productId = "";
    $allPrice = array();
    $allDiscountPrice = array();
    $allQuantitys = array();
    $allProductIds = array();

    $sql = "SELECT DISTINCT quantity, fk_product_id, name, image, price, discount_procent, first_name, last_name, address, postcode, city, country FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_user_id = {$userId}";
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
            $firstName = $row['first_name'];
            $lastName = $row['last_name'];
            $address = $row['address'];
            $postcode = $row['postcode'];
            $city = $row['city'];
            $country = $row['country'];
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
            array_push($allQuantitys, $quantity);
            array_push($allProductIds, $productId);
            if($productId == $row['fk_product_id']){
                $item .= "
                <tr>
                    <td>".$row['name']."</td>
                    <td>".$quantity."</td>
                    <td>".$row['discount_procent']."%</td>
                    <td>".$price."€</td>
                </tr>
                ";
                $itemBill .= "
                <tr>
                    <td><img class='img-thumbnail' src='../../img/product_images/".$row['image']."'></td>
                    <td>".$row['name']."</td>
                    <td>".$quantity."</td>
                    <td>".$row['discount_procent']."%</td>
                    <td>".$price."€</td>
                    <td>".$discountPrice."€</td>
                </tr>
                ";
                $quantity = "";
                $itemId = "";
            }
        }
    }

    if (isset($_POST['buyBtn'])){
        $userId = $_POST['userId'];
        $date = date('d-m-y h:i:s');
        $sql1 = "INSERT INTO purchase (create_datetime, fk_user_id) VALUES ('$date', $userId)";
        $sql3 = "DELETE FROM cart_item WHERE fk_user_id = {$userId}";
        if ($conn->query($sql1) === true ) {
            $class1 = "success";
            // $message1 = "Succsess Purchase";
        } else {
            $class1 = "danger";
            $message1 = "Error Purchase. Try again: <br>" . $conn->error;
        }
        if ($conn->query($sql3) === true ) {
            $class3 = "success";
            $message3 = "Your Order was Successfull";
        } else {
            $class3 = "danger";
            $message3 = "Error delete. Try again: <br>" . $conn->error;
        }

        $sqlId = "SELECT pk_purchase_id FROM purchase WHERE create_datetime = '{$date}' AND fk_user_id = {$userId}";
        $result = $conn->query($sqlId);

        if ($result->num_rows == 1){
            $data = $result->fetch_assoc();
            $purchaseId = $data['pk_purchase_id'];
        }
        for($i = 0; $i < count($allProductIds); $i++){
            $sql2 = "INSERT INTO purchase_item (quantity, fk_product_id, fk_purchase_id) VALUES ($allQuantitys[$i], $allProductIds[$i], $purchaseId)";
        }
        if ($conn->query($sql2) === true ) {
            $class2 = "success";
            // $message2 = "Succsess Item";
        } else {
            $class2 = "danger";
            $message2 = "Error Item. Try again: <br>" . $conn->error;
        }

        //send email notification
        purchaseNotification($purchaseId);

        Header('Location: '.$_SERVER['PHP_SELF']);
    }
    $conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase</title>
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
        navbar("../../", "../", "../", $id, $session);
    ?>
    <div class="container">
        <h1>Purchase</h1>
        <div class="my-2 text-<?=$class1;?>"><?php echo ($message1) ?? ""; ?></div>
        <div class="my-2 text-<?=$class2;?>"><?php echo ($message2) ?? ""; ?></div>
        <div class="my-2 text-<?=$class3;?>"><?php echo ($message3) ?? ""; ?></div>
        <?php if($item !== ""){ ?>
        <div id="address">
            <h3>Delivery | Billing Address</h3>
            <p><?php echo $firstName." ".$lastName ?></p>
            <p><?php echo $address."," ?></p>
            <p><?php echo $city." - ".$postcode."," ?></p>
            <p><?php echo $country ?></p>
            <!-- <button id='addressBtn' type="button" class="btn btn-primary">Change Delivery Address</button> -->
        </div>
        <div id="payment">
            <select class="form-select  w-25" aria-label="Default select example">
                <option selected>Payment Methods</option>
                <option value="dd">Direct Debit</option>
                <option value="cc">Credit Card</option>
                <option value="pp">PayPal</option>
            </select>
        </div>
        <div id="priceList">
            <h3>List of Items</h3>
            <div class="manageProduct w-50 mt-3">
                <table class='table table-striped'>
                    <thead class='table-success'>
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Discount</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $item;?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>-<?php echo (array_sum($allPrice) - array_sum($allDiscountPrice)) ?>€</td>
                            <td><?php echo array_sum($allPrice) ?>€</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>Total: </td>
                            <td><b><?php echo array_sum($allDiscountPrice) ?>€</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <form class='my-3' method='post' action='<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>' autocomplete='off'>
                <input type='hidden' name='productId' value='<?php echo $productId ?>'/>
                <input type='hidden' name='userId' value='<?php echo $userId ?>'/>
                <button id='buyBtn' type="sumbit" name="buyBtn" class="btn btn-primary">Purchase</button>
            </form>
        </div>
        <div id="products">
            <h3>Bill</h3>
            <div class="manageProduct w-75 mt-3">
                <table class='table table-striped'>
                    <thead class='table-success'>
                        <tr>
                            <th></th>
                            <th></th>
                            <th colspan="2">Bill</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Name: </td>
                            <td colspan="4"><?php echo $firstName." ".$lastName ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Address: </td>
                            <td colspan="4"><?php echo $address."," ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="4"><?php echo $city." - ".$postcode."," ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="4"><?php echo $country ?></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Picture</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Discount</th>
                            <th>Price</th>
                            <th>Discount Price</th>
                        </tr>
                        <?= $itemBill;?>
                        <tr>
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
                            <td>Total: </td>
                            <td><b><?php echo array_sum($allDiscountPrice) ?>€</b></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php }else{ ?>
        <div>
            <h2>Thank you for shopping with us!</h2>
            <h1>Your Order was Successfull</h1>
            <p>An notification has been send to your email address.</p>
            <a href="../product/product-catalog.php" class="btn btn-primary">Continue Shopping</a>
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