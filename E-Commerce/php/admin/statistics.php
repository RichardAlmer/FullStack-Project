<?php
    require_once '../components/db_connect.php';

    session_start();
    if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
        header("Location: ../../index.php");
        exit;
    }
    if (isset($_SESSION["user"])) {
        header("Location: ../product/product-catalog.php");
        exit;
    }

    $sql = "SELECT product.name, product.category, product.brand, product.status, purchase_item.fk_product_id, SUM(purchase_item.sold) AS sold, SUM(purchase_item.quantity) AS quantity 
    FROM purchase_item 
    INNER JOIN product ON purchase_item.fk_product_id = product.pk_product_id 
    GROUP BY purchase_item.fk_product_id
    ORDER BY quantity DESC";
    $result = mysqli_query($conn, $sql);

    $htmlResult="<table class='table table-striped'>
                    <thead class='bg_maincolor'>
                        <tr>
                            <th class='border-0'>Product Name</th>
                            <th class='border-0'>Category</th>
                            <th class='border-0'>Brand</th>
                            <th class='border-0'>Product Status</th>
                            <th class='border-0'>Price</th>
                            <th class='border-0'>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>"; 
    if ($result->num_rows > 0) {  
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ 
            $htmlResult .= "
            <tr>
                <td>" . $row['name'] . "</td>
                <td>" . $row['category'] . "</td>
                <td>" . $row['brand'] . "</td>
                <td>" . $row['status'] . "</td>
                <td>" . number_format($row['sold'], 2, ',', ' ') . "€</td>
                <th>" . $row['quantity'] . "</th>
            </tr>";
        };
    } else  {
    $htmlResult =  "<center>No Data Available </center>";
    }
    $htmlResult .= "</tbody></table>";

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
    <title>Statistics</title>
    <?php require_once '../components/boot.php' ?>
    <link rel="stylesheet" href="../../style/main-style.css" />
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
        <div id="content" class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Statistics</div>
            <a href="dashboard.php"><button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-4' type="button">Back to dashboard</button></a>
            <table>
                <?= $htmlResult ?>
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