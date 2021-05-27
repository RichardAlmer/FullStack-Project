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
if (isset($_SESSION['admin'])) {
    $userId = $_SESSION['admin'];
} else if (isset($_SESSION['user'])) {
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
$emailResponse = array();

$sql = "SELECT DISTINCT quantity, fk_product_id, name, image, price, discount_procent, first_name, last_name, address, postcode, city, country FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_user_id = {$userId}";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $sqlA = "SELECT AVG(fk_product_id), COUNT(name) FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id WHERE fk_user_id = {$userId} AND name = '{$row['name']}'";
        $resultA = mysqli_query($conn, $sqlA);
        while ($rowA = mysqli_fetch_array($resultA, MYSQLI_ASSOC)) {
            $quantity .= $rowA['COUNT(name)'];
            $productId = $rowA['AVG(fk_product_id)'];
        }
        $sqlItemId = "SELECT pk_cart_item_id FROM cart_item INNER JOIN product ON fk_product_id = pk_product_id WHERE fk_user_id = {$userId} AND name = '{$row['name']}' ORDER BY pk_cart_item_id DESC LIMIT 1";
        $resultItemId = mysqli_query($conn, $sqlItemId);
        while ($rowItemId = mysqli_fetch_array($resultItemId, MYSQLI_ASSOC)) {
            $itemId .= $rowItemId['pk_cart_item_id'];
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
        switch ($discount) {
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
        if ($productId == $row['fk_product_id']) {
            $item .= "
                    <div class='col-12 col-md-4 py-2 py-md-3 py-lg-0'>" . $row['name'] . "</div>
                    <div class='col-12 col-md-2 py-2 py-md-3 py-lg-0'>" . $quantity . "</div>
                    <div class='col-12 col-md-3 py-2 py-md-3 py-lg-0'><span class='my_text_maincolor'>" . $row['discount_procent'] . "%</span></div>
                    <div class='col-12 col-md-3 py-2 py-md-3 py-lg-0'>" . number_format($price, 2, ',', ' ') . "€</div>
                ";
            $itemBill .= "
                    <div class='col-12 col-md-4 col-lg-2 py-2'><img class='img-thumbnail rounded-circle' src='../../img/product_images/" . $row['image'] . "'></div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'>" . $row['name'] . "</div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'><span class='my_text_maincolor fw-bold'>" . $quantity . "</span></div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'><span class='my_text_maincolor'>" . $row['discount_procent'] . "%</span></div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'>" . number_format($price, 2, ',', ' ') . "€</div>
                    <div class='col-12 col-md-4 col-lg-2 py-2 py-md-3 py-lg-0'>" . number_format($discountPrice, 2, ',', ' ') . "€</div>
                ";
            $quantity = "";
            $itemId = "";
        }
    }
}

if (isset($_POST['buyBtn'])) {
    $userId = $_POST['userId'];
    $date = date('d-m-y h:i:s');
    $sql1 = "INSERT INTO purchase (create_datetime, fk_user_id) VALUES ('$date', $userId)";
    $sql3 = "DELETE FROM cart_item WHERE fk_user_id = {$userId}";
    if ($conn->query($sql1) === true) {
        $class1 = "success";
        // $message1 = "Succsess Purchase";
    } else {
        $class1 = "danger";
        $message1 = "Error Purchase. Try again: <br>" . $conn->error;
    }
    if ($conn->query($sql3) === true) {
        $class3 = "success";
        $message3 = "Your Order was Successfull";
    } else {
        $class3 = "danger";
        $message3 = "Error delete. Try again: <br>" . $conn->error;
    }

    $sqlId = "SELECT pk_purchase_id FROM purchase WHERE create_datetime = '{$date}' AND fk_user_id = {$userId}";
    $result = $conn->query($sqlId);

    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $purchaseId = $data['pk_purchase_id'];
    }
    for ($i = 0; $i < count($allProductIds); $i++) {
        $sql2 = "INSERT INTO purchase_item (quantity, fk_product_id, fk_purchase_id, sold) VALUES ($allQuantitys[$i], $allProductIds[$i], $purchaseId, $allDiscountPrice[$i])";
        if ($conn->query($sql2) === true) {
            $class2 = "success";
            // $message2 = "Succsess Item";
        } else {
            $class2 = "danger";
            $message2 = "Error Item. Try again: <br>" . $conn->error;
        }
    }
    

    //send email notification & 
    //save result into session otherwise variable does not survive the $_SERVER['PHP_SELF']
    $_SESSION['emailResponse'] = purchaseNotification($purchaseId);

    Header('Location: ' . $_SERVER['PHP_SELF']);
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
    <title>Purchase</title>
    <?php require_once '../../php/components/boot.php' ?>
    <link rel="stylesheet" href="../../style/main-style.css" />
</head>

<body>
    <?php
    require_once '../../php/components/header.php';
    $id = "";
    $session = "";
    if (isset($_SESSION['admin'])) {
        $id = $_SESSION['admin'];
        $session = "admin";
    } else if (isset($_SESSION['user'])) {
        $id = $_SESSION['user'];
        $session = "user";
    }
    navbar("../../", "../", "../", $id, $session, $cartCount, $image);
    ?>
    <div class="container">
        <div class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Purchase</div>
            <div class="my-2 text-<?= $class1; ?>"><?php echo ($message1) ?? ""; ?></div>
            <div class="my-2 text-<?= $class2; ?>"><?php echo ($message2) ?? ""; ?></div>
            <div class="my-2 text-<?= $class3; ?>"><?php echo ($message3) ?? ""; ?></div>
            <?php if ($item !== "") { ?>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div id="address" class="my-3">
                            <div class="col-12 fw-bold my-4">Delivery | Billing address</div>
                            <div class="col-12 my-3"><span class="my_text_maincolor"><?php echo $firstName . " " . $lastName ?> </span><?php echo $address .", ". $city . " - " . $postcode . ", ". $country?></div>
                            <!-- <button id='addressBtn' type="button" class="btn btn-primary">Change Delivery Address</button> -->
                        </div>
                        <div id="payment" class="col-12 col-md-6">
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Payment Methods</option>
                                <option value="dd">Direct Debit</option>
                                <option value="cc">Credit Card</option>
                                <option value="pp">PayPal</option>
                            </select>
                        </div>
                    </div>
                    <div id="priceList" class="col-12 col-lg-6">
                        <div class="col-12 fw-bold my-4">List of items</div>
                        <div class="manageProduct mt-3">
                            <table class='table table-striped'>
                                <div class='fw-bold d-none d-lg-flex row my-3'>
                                    <div class="col-12 col-lg-4">Name</div>
                                    <div class="col-12 col-lg-2">Quantity</div>
                                    <div class="col-12 col-lg-3">Discount</div>
                                    <div class="col-12 col-lg-3">Price</div>
                                </div>
                                <div class='row my-3 align-items-center text-center text-md-start'>
                                    <?= $item; ?>
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
                                </div>
                            </table>
                        </div>
                        <form class="col-12 col-md-auto text-center text-md-end" method='post' action='<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>' autocomplete='off'>
                            <input type='hidden' name='productId' value='<?php echo $productId ?>' />
                            <input type='hidden' name='userId' value='<?php echo $userId ?>' />
                            <button id='buyBtn' type="sumbit" name="buyBtn" class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-4 text-white">Purchase</button>
                        </form>
                    </div>
                </div>

                <div id="products" class="my-5">
                    <div class="col-12 fs_6 text-uppercase my-2">Bill</div>
                    <div class="manageProduct mt-3">
                        <table class='table table-striped'>
                            <div class="col-12 fw-bold my-4">Bill</div>
                            <div class='row my-3'>
                                <div class='col-12 col-md-4 py-2'>Name:</div>
                                <div class='col-12 col-md-8 py-2'><?php echo $firstName . " " . $lastName ?></div>
                                <div class='col-12 col-md-4 py-2'>Address:</div>
                                <div class='col-12 col-md-8 py-2'><?php echo $address . ", ". $city . " - " . $postcode . ", ". $country ?></div>
                            </div>

                            <div class="col-12 fw-bold my-4">Items</div>
                            <div class='fw-bold d-none d-lg-flex row my-3 my_text_lightgray'>
                                <div class="col-lg-2">Picture</div>
                                <div class="col-lg-2">Name</div>
                                <div class="col-lg-2">Quantity</div>
                                <div class="col-lg-2">Discount</div>
                                <div class="col-lg-2">Price</div>
                                <div class="col-lg-2">Discount price</div>
                            </div>
                            <div class='row my-3 align-items-center text-center text-lg-start'>
                                <?= $itemBill; ?>
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
                            </div>
                        </table>
                    </div>
                </div>
            <?php } else { ?>
                <div class="my-5">
                    <div class="fs-5 my-3"><span class="my_text_maincolor">Thank you</span> for shopping with us!</div>
                    <div class="my_text_gray fs-4">Your order was successfull.</div>
                    <div class="fs_7 my-2 my_text_lightgray"><?php echo $_SESSION['emailResponse']['statusMessage'] ?></div>
                    <a href="../product/product-catalog.php" class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-4">Continue Shopping</a>
                </div>
            <?php
                //destroy session emailResponse variable
                unset($_SESSION['emailResponse']);
                }
            ?>
        </div>
    </div>
    <?php
    require_once '../../php/components/footer.php';
    footer("../../");
    require_once '../../php/components/boot-javascript.php';
    ?>
</body>

</html>