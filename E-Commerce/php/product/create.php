<?php
// session_start(); 
// if (isset($_SESSION['user']) != "") {
//     header("Location: home.php"); 
// }
// if (isset($_SESSION['adm']) != "") {
//     header("Location: dashboard.php"); 
// }
require_once '../components/db_connect.php';
require_once '../components/file_upload.php';

$error = false;
$name = $description = $brand = $image = $price = $category = $status = $discountProcent = '';
$nameError = $descriptionError = $brandError = $imageError = $priceError = $categoryError = '';

function sanitizeUserInput ($fieldInput, $fieldName) {
    // --- sanitize user input to prevent sql injection --- //
    $fieldInput = trim($_POST[$fieldName]);     
    $fieldInput = strip_tags($fieldInput);       
    $fieldInput = htmlspecialchars($fieldInput);  // htmlspecialchars converts special characters to HTML entities
    return $fieldInput;
}

if (isset($_POST['submit'])) {

    $name = sanitizeUserInput($name, 'name');
    $description = sanitizeUserInput($description, 'description');
    $brand = sanitizeUserInput($brand, 'brand');
    $price = sanitizeUserInput($price, 'price');
    $category = sanitizeUserInput($category, 'category');
    //$status = $_POST[$status];                    //throws error
    //$discountProcent = $_POST[$discountProcent];  //throws error
 
    $uploadError = '';
    $image = file_upload($_FILES['image']);

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
    } 
    // else if (!preg_match("/^[0-9]+$/", $price)) {
    //     $error = true;
    //     $nameError = "Category must contain only letters and no spaces.";
    // }
    if (empty($category)) {
        $error = true;
        $categoryError = "Please enter a category for the product.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $category)) {
        $error = true;
        $nameError = "Category must contain only letters and no spaces.";
    }

    // if there's no error, continue to create product
    ///// //VALUES('$name', '$description', '$brand', '$image->fileName', '$price', '$category', '$status', '$discountProcent')";
    // error in line 32 file_upload - $image->fileName
    if (!$error) {

        $query = "INSERT INTO product(`name`, `description`, `brand`, `image`, `price`, `category`, `status`, `discount_procent`) 
        VALUES('$name', '$description', '$brand', 'default-product.jpg', '$price', '$category', 'active', 'none')";
        $res = mysqli_query($conn, $query);

        if ($res) {
            $errTyp = "success";
            $errMSG = "Successfully added product";
            $uploadError = ($image->error != 0) ? $image->ErrorMessage : '';

        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
            $uploadError = ($image->error != 0) ? $image->ErrorMessage : '';
        }
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
        navbar("../../", "../");
        ?>

        <div class="container">

            <?php
            if (isset($errMSG)) {
            ?>
                <div class="alert alert-<?php echo $errTyp ?>" >
                    <p><?php echo $errMSG; ?></p>
                    <p><?php echo $uploadError; ?></p>
                </div>
            <?php
            }
            ?>

            <h2>Add Product</h2>
           
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data">
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
                    <select class="form-select" aria-label="Default select example" id="status" name="status">
                            <option value="active" <?php echo ( $status == 'active') ? 'selected' : '' ?>>active</option>
                            <option value="deactive" <?php echo ( $status == 'deactive') ? 'selected' : '' ?>>deactive</option>    
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><a href="products.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                    <td><button name="submit" class="btn btn-success" type="submit">Add Product</button></td>
                </tr>
            </table>
        </form>
        </div>
    </body>
</html>