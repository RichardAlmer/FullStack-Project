<?php
// session_start();
// if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
//     header("Location: ../../index.php");
//     exit;
// }
// $backBtn = '';
// if (isset($_SESSION["user"])) {
//     $backBtn = "../product/produrct-catalog.php";
// }
// if (isset($_SESSION["admin"])) {
//     $backBtn = "../admin/dashBoard.php";
// }

require_once '../components/db_connect.php';
require_once '../components/file_upload.php';

$error = false;
$name = $description = $brand = $image = $price = $category = $status = $discountProcent = '';
$nameError = $descriptionError = $brandError = $imageError = $priceError = $categoryError = '';

//fetch and populate form
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $sql = "SELECT * FROM product WHERE pk_product_id = {$productId}";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $name = $data['name'];
        $description = $data['description'];
        $brand = $data['brand'];
        $image = $data['image'];
        $price = $data['price'];
        $category = $data['category'];
        $status = $data['status'];
        $discountProcent = $data['discount_procent'];
    }
}

//update on submit
$class = 'd-none';
if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $brand = $_POST['brand'];
    $image = $_POST['image'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $discountProcent = $_POST['discountProcent'];
    $id = $_POST['id'];
    
    $uploadError = '';  
    
    $image = file_upload($_FILES['image']);

    $sql = "UPDATE product SET name = '$name', description = '$description', brand = '$brand', image = '$image->fileName', price = '$price', category = '$category', discount_procent = '$discountProcent', status = '$status' 
    WHERE pk_product_id = '$id'";
    
    if ($conn->query($sql) === true) {     
        $class = "alert alert-success";
        $message = "The record was successfully updated.";
        header("refresh:3;url=products.php");
    } else {
        $class = "alert alert-danger";
        $message = "Error while updating record: <br>" . $conn->error;
        header("refresh:3;url=products.php");
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <?php require_once '../components/boot.php' ?>
    <link rel='stylesheet' type='text/css' href='../../style/main-style.css'>
</head>

<body>

    <?php
    require_once '../components/header.php';
    navbar("../../", "../");
    ?>

    <div class="container">
        <div class="<?php echo $class; ?>" role="alert">
            <p><?php echo ($message) ?? ''; ?></p>
            <p><?php echo ($uploadError) ?? ''; ?></p>
        </div>

        <h2>Update Product</h2>

        <form method="post" enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td>
                        <input class="form-control" type="text" name="name" placeholder="Name" value="<?php echo $name ?>" />
                        <span class="text-danger"> <?php echo $nameError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <th>Image</th>
                    <td><input class="form-control" type="file" name="image" /></td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <textarea rows="3" name="description" class="form-control" placeholder=""><?php echo $description ?></textarea>    
                        <span class="text-danger"> <?php echo $descriptionError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <th>Brand</th>
                    <td>
                        <input class="form-control" type="text" name="brand" placeholder="Brand of product" value="<?php echo $brand ?>" />
                        <span class="text-danger"> <?php echo $brandError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <th>Price</th>
                    <td>
                        <input class="form-control" type="text" name="price" placeholder="Price of product" value="<?php echo $price ?>" />
                        <span class="text-danger"> <?php echo $priceError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>
                        <input class="form-control" type="text" name="category" placeholder="Category of product" value="<?php echo $category ?>" />
                        <span class="text-danger"> <?php echo $categoryError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <th>Discount</th>
                    <td>
                        <select class="form-select" aria-label="Default select example" name="discountProcent">
                            <option value="0" <?php echo ( $discountProcent == '0') ? 'selected' : '' ?>>none</option>
                            <option value="10" <?php echo ( $discountProcent == '10') ? 'selected' : '' ?>>10% off</option>
                            <option value="15" <?php echo ( $discountProcent == '15') ? 'selected' : '' ?>>15% off</option>
                            <option value="20" <?php echo ( $discountProcent == '20') ? 'selected' : '' ?>>20% off</option>
                            <option value="25" <?php echo ( $discountProcent == '25') ? 'selected' : '' ?>>25% off</option>
                            <option value="50" <?php echo ( $discountProcent == '50') ? 'selected' : '' ?>>50% off</option>
                            <option value="75" <?php echo ( $discountProcent == '75') ? 'selected' : '' ?>>75% off</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                    <select class="form-select" aria-label="Default select example" name="status">
                        <option value="active" <?php echo ( $status == 'active') ? 'selected' : '' ?>>active</option>
                        <option value="deactive" <?php echo ( $status == 'deactive') ? 'selected' : '' ?>>deactive</option>    
                    </select>
                    </td>
                </tr>
                <tr>
                    <input type="hidden" name="id" value="<?php echo $data['pk_product_id'] ?>" />
                    <input type="hidden" name="image" value="<?php echo $image ?>" />
                    <td><a href="products.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                    <td><button name="submit" class="btn btn-success" type="submit">Save Changes</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>