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

    $sql = "SELECT product.name AS name, product.category AS category, product.brand AS category,brand, purchase_item.quantity AS quantity
            FROM product 
            INNER JOIN purchase_item ON purchase_item.pk_purchase_item_id = product.pk_product_id
            ORDER BY purchase_item.quantity DESC";
    $result = mysqli_query($conn ,$sql);     

    $htmlResult="<table class='table table-striped'>
                    <thead class='table-success'>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>"; 
    if(mysqli_num_rows($result) > 0) {     
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ 
            $htmlResult .= "
            <tr>
                <td>" . $row['name'] . "</td>
                <td>" . $row['category'] . "</td>
                <td>" . $row['brand'] . "</td>
                <th>" . $row['quantity'] . "</th>
            </tr>";
        };
    } else  {
    $htmlResult =  "<center>No Data Available </center>";
    }
    $htmlResult .= "</tbody></table>";

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
        navbar("../../", "../");
    ?>

    <div id="container" class="container">
        <div id="content" class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Statistics</div>
            <table>
                <?= $htmlResult ?>
            </table>
            <a href='dashboard.php'>Back to dashboard</a>
        </div>
    </div>
</body>

</html>