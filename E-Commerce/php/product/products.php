<?php

require_once '../components/db_connect.php';

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
                        <button class='btn btn-light btn-sm' type='button'>No Reviews</button></a> ";
        } 
        else if ($data['count'] == 1) {
            $tbody .= "<a href='../admin/reviews.php?id=" . $row['pk_product_id'] . "'>
                        <button class='btn btn-warning btn-sm' type='button'>View ".$data['count']." Review</button></a> ";
        } else {
            $tbody .= "<a href='../admin/reviews.php?id=" . $row['pk_product_id'] . "'>
                        <button class='btn btn-warning btn-sm' type='button'>View ".$data['count']." Review(s)</button></a> ";
        }
        $tbody .= "<a href='update.php?id=" . $row['pk_product_id'] . "'><button class='btn btn-primary btn-sm' type='button'>Update</button></a>
                <a href='delete.php?id=" . $row['pk_product_id'] . "'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a>
            </td>
        </tr>";
    };
} else  {
   $tbody =  "<div><center>No Data Available </center></div>";
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
    navbar("../../", "../");
    ?>

    <div id="container">
        <div id="content">
            <h1>Manage Products</h1>

            <a href="create.php"><button class='btn btn-primary' type="button">Add Product</button></a>

            <table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $tbody ?>
                </tbody>
            </table>

            <a href='../admin/dashboard.php'>Back to dashboard</a>
        </div>
    </div>

    <?php
        require_once '../components/footer.php';

    ?>

    <?php require_once '../components/boot-javascript.php' ?>

</body>

</html>