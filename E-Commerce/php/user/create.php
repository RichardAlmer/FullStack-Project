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
$firstName = $lastName = $email = $pass = $birthDate = $street = $zipCode = $city = $country = $role = $status = $bannedUntil = $picture = '';
$firstNameError = $lastNameError = $emailError =  $passwordError = $birthDateError = $streetError = $zipCodeError = $cityError = $countryError = $roleError = $statusError = $pictureError = '';

//role dropdown
$roleList = ["user", "admin"];
$roleOptions = "";
foreach ($roleList as $role) {
    $roleOptions .= "<option value='$role'>" . ucfirst($role) . "</option>";
}

//status dropdown
$statusList = ["active", "inactive"];
$statusOptions = "";
foreach ($statusList as $status) {
    $statusOptions .= "<option value='$status'>" . ucfirst($status) . "</option>";
}

//Create
$class = 'd-none';
if (isset($_POST["btnCreate"])) {

    $error = false;

    // sanitize user input to prevent sql injection
    $firstName = trim($_POST['firstName']);
    $firstName = strip_tags($firstName);
    $firstName = htmlspecialchars($firstName);

    $lastName = trim($_POST['lastName']);
    $lastName = strip_tags($lastName);
    $lastName = htmlspecialchars($lastName);

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);

    $birthDate = trim($_POST['birthDate']);
    $birthDate = strip_tags($birthDate);
    $birthDate = htmlspecialchars($birthDate);

    $street = trim($_POST['street']);
    $street = strip_tags($street);
    $street = htmlspecialchars($street);

    $zipCode = trim($_POST['zipCode']);
    $zipCode = strip_tags($zipCode);
    $zipCode = htmlspecialchars($zipCode);

    $city = trim($_POST['city']);
    $city = strip_tags($city);
    $city = htmlspecialchars($city);

    $country = trim($_POST['country']);
    $country = strip_tags($country);
    $country = htmlspecialchars($country);

    $role = $_POST['role'];
    $status = $_POST['status'];
    $bannedUntil = $_POST['bannedUntil'];

    // basic name validation
    if (empty($firstName) || empty($lastName)) {
        $error = true;
        $firstNameError = "Please enter your full name and surname";
    } else if (strlen($firstName) < 3 || strlen($lastName) < 3) {
        $error = true;
        $fistNameError = "Name and surname must have at least 3 characters.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $firstName) || !preg_match("/^[a-zA-Z]+$/", $lastName)) {
        $error = true;
        $firstNameError = "Name and surname must contain only letters and no spaces.";
    }

    //basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } else {
        // checks whether the email exists or not
        $query = "SELECT email FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $emailError = "Provided email is already in use.";
        }
    }

    // password validation
    if (empty($password)) {
        $error = true;
        $passwordError = "Please enter password.";
    } else if (strlen($password) < 6) {
        $error = true;
        $passwordError = "Password must have at least 6 characters.";
    }

    //checks if the date input was left empty
    if (empty($birthDate)) {
        $error = true;
        $birthDateError = "Please enter a date of birth.";
    }

    //checks if the street input was left empty
    if (empty($street)) {
        $error = true;
        $streetError = "Please enter a street.";
    }

    //checks if the ZIP-Code input was left empty
    if (empty($zipCode)) {
        $error = true;
        $zipCodeError = "Please enter a postcode.";
    }

    //checks if the city input was left empty
    if (empty($city)) {
        $error = true;
        $cityError = "Please enter a city.";
    }

    //checks if the country input was left empty
    if (empty($country)) {
        $error = true;
        $countryError = "Please enter a country.";
    }

    //you cannot ban other admins
    if ($role == 'admin') {
        $bannedUntil = "";
    }

    // password hashing for security
    $password = hash('sha256', $password);

    // if there's no error, continue to signup
    if (!$error) {
        //variable for upload pictures errors is initialized
        $uploadError = '';
        $picture = file_upload($_FILES['picture']); //file_upload() called

        //if banned until is empty then insert NULL into db
        if ($bannedUntil == "") {
            $sql = "INSERT INTO user (first_name, last_name, email, password, birthdate, address, postcode, city, country, role, status, banned_until, profile_image) VALUES ('$firstName', '$lastName', '$email', '$password', '$birthDate', '$street', '$zipCode', '$city', '$country', '$role', '$status', NULL, '$picture->fileName')";
        } else {
            $sql = "INSERT INTO user (first_name, last_name, email, password, birthdate, address, postcode, city, country, role, status, banned_until, profile_image) VALUES ('$firstName', '$lastName', '$email', '$password', '$birthDate', '$street', '$zipCode', '$city', '$country', '$role', '$status', '$bannedUntil', '$picture->fileName')";
        }

        $result = $conn->query($sql);

        if ($result) {
            $class = "alert alert-success";
            $message = "The record was successfully created";
            $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';
            header("refresh:3;url=users.php");
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <?php require_once '../components/boot.php' ?>
    <link rel="stylesheet" href="../../style/main-style.css" />
    <link rel="stylesheet" href="../../style/updateUser.css">
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
        <div class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Create New User</div>
            <div class="<?php echo $class; ?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>
            </div>

            <form class="my-3" method="post" enctype="multipart/form-data">
                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">First name</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="firstName" placeholder="First name" value="<?php echo $firstName ?>" maxlength="100" />
                    </div>
                    <span class="text-danger"> <?php echo $firstNameError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Last name</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="lastName" placeholder="Last name" value="<?php echo $lastName ?>" maxlength="100" />
                    </div>
                    <span class="text-danger"> <?php echo $lastNameError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Email</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo $email ?>" maxlength="100" />
                    </div>
                    <span class="text-danger"> <?php echo $emailError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Password</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="password" name="password" placeholder="Password" maxlength="255" />
                        <span class="text-danger"> <?php echo $passwordError; ?> </span>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Date of birth</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="date" name="birthDate" placeholder="Date of birth" value="<?php echo $birthDate ?>" />
                    </div>
                    <span class="text-danger"> <?php echo $birthDateError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Picture</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="file" name="picture" />
                    </div>
                    <span class="text-danger"> <?php echo $pictureError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Street</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="street" placeholder="Street" value="<?php echo $street ?>" maxlength="255" />
                    </div>
                    <span class="text-danger"> <?php echo $streetError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Postcode</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="zipCode" placeholder="ZIP-Code" value="<?php echo $zipCode ?>" maxlength="12" />
                    </div>
                    <span class="text-danger"> <?php echo $zipCodeError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">City</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="city" placeholder="City" value="<?php echo $city ?>" maxlength="120" />
                    </div>
                    <span class="text-danger"> <?php echo $cityError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Country</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="country" placeholder="Country" value="<?php echo $country ?>" maxlength="50" />
                    </div>
                    <span class="text-danger"> <?php echo $countryError; ?> </span>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Role</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <select class="form-select" name="role" aria-label="Default select example">
                            <?php echo $roleOptions; ?>
                        </select>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Status</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <select class="form-select" name="status" aria-label="Default select example">
                            <?php echo $statusOptions; ?>
                        </select>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Banned until:</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="datetime-local" name="bannedUntil" placeholder="Banned until" />
                    </div>
                </div>

                <div class="row py-4">
                    <div class="">
                        <a href="users.php">
                            <button class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="button">Back</button>
                        </a>
                        <button name="btnCreate" class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="submit">Create User</button>
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