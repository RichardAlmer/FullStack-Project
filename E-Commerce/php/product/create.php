<?php
session_start();
if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}
if (isset($_SESSION["user"])) {
    header("Location: ../product/product-catalog.php");
    exit;
}

require_once '../components/db_connect.php';
require_once '../components/file_upload.php';

$passError = '';
$name = $description = $brand = $picture = $price = $category = $status = $discountProcent = '';
$nameError = $descriptionError = $brandError = $pictureError = $priceError = $categoryError = '';

function sanitizeUserInput ($fieldInput, $fieldName) {
    // --- sanitize user input to prevent sql injection --- //
    $fieldInput = trim($_POST[$fieldName]);     
    $fieldInput = strip_tags($fieldInput);       
    $fieldInput = htmlspecialchars($fieldInput);  // htmlspecialchars converts special characters to HTML entities
    return $fieldInput;
}

//Create
$class = 'd-none';
if (isset($_POST['btnCreate'])) {

    $error = false;

    $name = sanitizeUserInput($name, 'name');
    $description = sanitizeUserInput($description, 'description');
    $brand = sanitizeUserInput($brand, 'brand');
    $price = sanitizeUserInput($price, 'price');
    $category = sanitizeUserInput($category, 'category');
    $discountProcent = $_POST['discountProcent'];
    $status = $_POST['status'];                  
   
    $uploadError = '';
  
    // validation of required fields and input type where needed
    if (empty($name)) {
        $error = true;
        $nameError = "Please enter a name for the product.";
    } 
    if (empty($description)) {
        $error = true;
        $descriptionError = "Please enter a description for the product.";
    } 
    if (empty($brand)) {
        $error = true;
        $brandError = "Please enter a brand for the product.";
    } 
    if (empty($price)) {
        $error = true;
        $priceError = "Please enter a price for the product.";
    } else if (!preg_match("/^[0-9]+(?:\.[0-9]{0,2})?$/", $price)) {
        $error = true;
        $priceError = "Price must be currency value.";
    }
    if (empty($category)) {
        $error = true;
        $categoryError = "Please enter a category for the product.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $category)) {
        $error = true;
        $categoryError = "Category must contain only letters and no spaces.";
    }

    // if there's no error, continue to create product
    if (!$error) {
        $uploadError = '';
        $picture = file_upload($_FILES['picture'], 'product');

        $sql = "INSERT INTO product(`name`, `description`, `brand`, `image`, `price`, `category`, `status`, `discount_procent`) 
        VALUES('$name', '$description', '$brand', '$picture->fileName', '$price', '$category', '$status', '$discountProcent')";
        
        $result = $conn->query($sql);

        if ($result) {
            $class = "alert alert-success";
            $message = "The record was successfully created";
            $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';
            header("refresh:3;url=products.php");
        } else {
            $class = "alert alert-danger";
            $message = "Error while creating record : <br>" . $conn->error;
            $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';
            header("refresh:3;url=create.php");
        }
    }
}
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
        <title>Create Product</title>
        <?php require_once '../components/boot.php' ?>
        <link rel='stylesheet' type='text/css' href='../../style/main-style.css'>
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

        <div class="container">
            <div class="my-5 py-5">
                <div class="col-12 fs_6 text-uppercase my-2">Add Product</div>

                <div class="<?php echo $class; ?>" role="alert">
                    <p><?php echo ($message) ?? ''; ?></p>
                    <p><?php echo ($uploadError) ?? ''; ?></p>
                </div>

                <form class="my-3" method="post" enctype="multipart/form-data">
                    <table class="table">
                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Name</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <input class="form-control" type="text" name="name" placeholder="Name" value="<?php echo $name ?>" maxlength="100" />
                                <span class="text-danger"> <?php echo $nameError; ?> </span>
                            </div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Picture</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <input class="form-control" type="file" name="picture" />
                                <span class="text-danger"> <?php echo $pictureError; ?> </span>
                            </div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Description</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <textarea rows="3" name="description" class="form-control" placeholder=""><?php echo $description ?></textarea>    
                                <span class="text-danger"> <?php echo $descriptionError; ?> </span>
                            </div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Brand</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <input class="form-control" type="text" name="brand" placeholder="Brand of product" value="<?php echo $brand ?>" maxlength="100" />
                                <span class="text-danger"> <?php echo $brandError; ?> </span>
                            </div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Price</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <input class="form-control" type="text" name="price" placeholder="Price of product" value="<?php echo $price ?>" />
                                <span class="text-danger"> <?php echo $priceError; ?> </span>
                            </div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Category</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <input class="form-control" type="text" name="category" placeholder="Category of product" value="<?php echo $category ?>" maxlength="100" />
                                <span class="text-danger"> <?php echo $categoryError; ?> </span>
                            </div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Discount</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <select class="form-select" aria-label="Default select example" name="discountProcent">
                                    <option value="0" <?php echo ( $discountProcent == '0') ? 'selected' : '' ?>>none</option>
                                    <option value="10" <?php echo ( $discountProcent == '10') ? 'selected' : '' ?>>10% off</option>
                                    <option value="15" <?php echo ( $discountProcent == '15') ? 'selected' : '' ?>>15% off</option>
                                    <option value="20" <?php echo ( $discountProcent == '20') ? 'selected' : '' ?>>20% off</option>
                                    <option value="25" <?php echo ( $discountProcent == '25') ? 'selected' : '' ?>>25% off</option>
                                    <option value="50" <?php echo ( $discountProcent == '50') ? 'selected' : '' ?>>50% off</option>
                                    <option value="75" <?php echo ( $discountProcent == '75') ? 'selected' : '' ?>>75% off</option>
                                </select>
                            </div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Status</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2">
                                <select class="form-select" aria-label="Default select example" id="status" name="status">
                                    <option value="active" <?php echo ( $status == 'active') ? 'selected' : '' ?>>active</option>
                                    <option value="deactive" <?php echo ( $status == 'deactive') ? 'selected' : '' ?>>deactive</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <a href="products.php"><button class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="button">Back</button></a>
                            <button name="btnCreate" class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="submit">Add Product</button>
                        </div>
                    </table>
                </form>
            </div>
        </div>

        <?php 
            require_once '../../php/components/footer.php';
            footer("../../");
            require_once '../../php/components/boot-javascript.php';
        ?>
    </body>
</html>