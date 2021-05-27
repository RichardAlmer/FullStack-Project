<?php
session_start();
$userId = '';
if (isset($_SESSION['admin'])) {
    $userId = $_SESSION['admin'];
} else if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user'];
}

if ($_GET['id'] != $userId) {
    header("Location: ../error.php");
    exit;
}
if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}

require_once '../components/db_connect.php';
require_once '../components/file_upload.php';

//fetch and populate form
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM user WHERE pk_user_id = {$id}";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $address = $data['address'];
        $city = $data['city'];
        $postcode = $data['postcode'];
        $country = $data['country'];
        $birthdate = $data['birthdate'];
        $picture = $data['profile_image'];
    }
}

function sanitizeUserInput($fieldInput, $fieldName)
{
    $fieldInput = trim($_POST[$fieldName]);
    $fieldInput = strip_tags($fieldInput);
    $fieldInput = htmlspecialchars($fieldInput);
    return $fieldInput;
}

//update on submit
$class = 'd-none';
if (isset($_POST["submit"])) {
    $firstName = sanitizeUserInput($firstName, 'firstName');
    $lastName = sanitizeUserInput($lastName, 'lastName');
    $address = sanitizeUserInput($address, 'address');
    $city = sanitizeUserInput($city, 'city');
    $postcode = sanitizeUserInput($postcode, 'postcode');
    $country = sanitizeUserInput($country, 'country');
    $birthdate = $_POST['birthdate'];
    $id = $_POST['ids'];
    $error = false;

    if (empty($firstName)) {
        $error = true;
        $nameError = "Please enter a first name.";
    }
    if (empty($lastName)) {
        $error = true;
        $lastNameError = "Please enter a last name.";
    }
    if (empty($address)) {
        $error = true;
        $addressError = "Please enter a address.";
    }
    if (empty($city)) {
        $error = true;
        $cityError = "Please enter a city.";
    }
    if (empty($postcode)) {
        $error = true;
        $postcodeError = "Please enter a postcode.";
    }
    if (empty($country)) {
        $error = true;
        $countryError = "Please enter a country.";
    }
    if (empty($birthdate)) {
        $error = true;
        $birthdateError = "Please enter a birthdate.";
    }
    if (!$error) {
        $uploadError = '';
        $pictureArray = file_upload($_FILES['picture']); //file_upload() called
        if ($pictureArray->error === 0) {
            ($_POST["picture"] == "default-user.jpg") ?: unlink("../../img/user_images/{$_POST["picture"]}");
            $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', address = '$address', city = '$city', postcode = '$postcode', country = '$country', birthdate = '$birthdate', profile_image = '$pictureArray->fileName' WHERE pk_user_id = '$id'";
        } else {
            $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', address = '$address', city = '$city', postcode = '$postcode', country = '$country', birthdate = '$birthdate' WHERE pk_user_id = '$id'";
        }

        if ($conn->query($sql) === true) {
            $class = "alert alert-success";
            $message = "The record was successfully updated.";
            header("refresh:3;url=profile.php?id={$id}");
        } else {
            $class = "alert alert-danger";
            $message = "Error while updating record: <br>" . $conn->error;
            header("refresh:3;url=profile.php?id={$id}");
        }
    }
}


$cartCount = "";
$image = "";
$sqlCart = "SELECT COUNT(quantity), profile_image FROM cart_item INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_user_id = {$userId}";
$result = $conn->query($sqlCart);
    if ($result->num_rows == 1){
        $dataC = $result->fetch_assoc();
        $image = $data['profile_image'];
        if($dataC['COUNT(quantity)'] != 0){
            $cartCount = $dataC['COUNT(quantity)'];
        }
    }

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <?php require_once '../components/boot.php' ?>
    <link rel="stylesheet" href="../../style/main-style.css" />
    <style type="text/css">
        .img-thumbnail {
            width: 10rem;
            height: 10rem;
        }
    </style>
</head>

<body>
    <?php
    require_once '../components/header.php';
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
    <div id="container" class="container">
        <div id="content" class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Update Profile</div>

            <div class="<?php echo $class; ?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>
            </div>

            <div class="col-12">
                <img class='img-thumbnail rounded-circle' src='../../img/user_images/<?php echo $picture ?>' alt="<?php echo $firstName ?>">
            </div>

            <form class="my-3" method="post" enctype="multipart/form-data">
                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">First name</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName ?>" />
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Last name</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="lastName" placeholder="Last Name" value="<?php echo $lastName ?>" />
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Picture</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="file" name="picture" />
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Address</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="address" placeholder="Adress" value="<?php echo $address ?>" />
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">City</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="city" placeholder="City" value="<?php echo $city ?>" />
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Postcode</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="postcode" placeholder="Postcode" value="<?php echo $postcode ?>" />
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Country</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="country" placeholder="Country" value="<?php echo $country ?>" />
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Birthdate</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="date" name="birthdate" placeholder="Birthdate" value="<?php echo $birthdate ?>" />
                    </div>
                </div>

                <div class="row py-4">
                    <input type="hidden" name="ids" value="<?php echo $data['pk_user_id'] ?>" />
                    <input type="hidden" name="picture" value="<?php echo $picture ?>" />

                    <div class="">
                        <a href="javascript:history.back()">
                            <button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1' type="button">Back</button>
                        </a>
                        <button name="submit" class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="submit">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    require_once '../components/footer.php';
    footer("../../");
    require_once '../components/boot-javascript.php';
    ?>
</body>

</html>