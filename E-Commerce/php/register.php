<?php
session_start();
if (isset($_SESSION['user']) != "") {
    header("Location: product/product-catalog.php");
}
if (isset($_SESSION['admin']) != "") {
    header("Location: admin/dashboard.php");
}

require_once 'components/db_connect.php';
require_once 'components/file_upload.php';

$error = false;
$first_name = $last_name = $email = $address = $city = $postcode = $country = $birthdate = $password = $profile_image = '';
$fnameError = $lnameError = $emailError = $addressError = $cityError = $postcodeError = $countryError = $birthdateError = $passError = $picError = '';

if (isset($_POST['btnRegister'])) {

    $first_name = trim($_POST['first_name']);
    $first_name = strip_tags($first_name);
    $first_name = htmlspecialchars($first_name);

    $last_name = trim($_POST['last_name']);
    $last_name = strip_tags($last_name);
    $last_name = htmlspecialchars($last_name);

    $address = trim($_POST['address']);
    $address = strip_tags($address);
    $address = htmlspecialchars($address);

    $city = trim($_POST['city']);
    $city = strip_tags($city);
    $city = htmlspecialchars($city);

    $postcode = trim($_POST['postcode']);
    $postcode = strip_tags($postcode);
    $postcode = htmlspecialchars($postcode);

    $country = trim($_POST['country']);
    $country = strip_tags($country);
    $country = htmlspecialchars($country);

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $birthdate = trim($_POST['birthdate']);
    $birthdate = strip_tags($birthdate);
    $birthdate = htmlspecialchars($birthdate);

    $pass = trim($_POST['pass']);
    $pass = strip_tags($pass);
    $pass = htmlspecialchars($pass);

    // basic name validation
    if (empty($first_name) || empty($last_name)) {
        $error = true;
        $fnameError = "Please enter your full name and surname.";
    } else if (strlen($first_name) < 3 || strlen($last_name) < 3) {
        $error = true;
        $fnameError = "Name and surname must have at least 3 characters.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $first_name) || !preg_match("/^[a-zA-Z]+$/", $last_name)) {
        $error = true;
        $fnameError = "Name and surname must contain only letters and no spaces.";
    }

    //basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter a valid email address.";
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
    //checks if the date input was left empty
    if (empty($birthdate)) {
        $error = true;
        $birthdateError = "Please enter your date of birth.";
    }
    if (empty($address)) {
        $error = true;
        $addressError = "Please enter the address.";
    }
    if (empty($city)) {
        $error = true;
        $cityError = "Please enter the city.";
    }
    if (empty($postcode)) {
        $error = true;
        $postcodeError = "Please enter a postcode.";
    }
    if (empty($country)) {
        $error = true;
        $countryError = "Please enter the country.";
    }
    // password validation
    if (empty($pass)) {
        $error = true;
        $passError = "Please enter a password.";
    } else if (strlen($pass) < 6) {
        $error = true;
        $passError = "Password must have at least 6 characters.";
    }

    // password hashing for security
    $password = hash('sha256', $pass);

    if (!$error) {
        $uploadError = '';
        $profile_image = file_upload($_FILES['profile_image'], 'register');
        
        $query = "INSERT INTO user(first_name, last_name, address, city, postcode, country, password, birthdate, email, profile_image) VALUES('$first_name', '$last_name','$address','$city', '$postcode', '$country', '$password', '$birthdate', '$email', '$profile_image->fileName')";

        $res = mysqli_query($conn, $query);

        if ($res) {
            $errTyp = "success";
            $errMSG = "Successfully registered, you may <a href='login.php'>login</a> now.";
            $uploadError = ($profile_image->error != 0) ? $profile_image->ErrorMessage : '';
            header("refresh:3;url=login.php");
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
            $uploadError = ($profile_image->error != 0) ? $profile_image->ErrorMessage : '';
        }
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
    <title>Registration System</title>
    <?php require_once 'components/boot.php' ?>
    <link rel="stylesheet" href="../style/main-style.css" />
</head>

<body>
    <?php
    require_once 'components/header.php';
    $id = "";
    $session = "";
    if (isset($_SESSION['admin'])) {
        $id = $_SESSION['admin'];
        $session = "admin";
    } else if (isset($_SESSION['user'])) {
        $id = $_SESSION['user'];
        $session = "user";
    }
    navbar("../", "", "", $id, $session);
    ?>
    <div class="container">
        <div class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2 text-center">Registration</div>

            <div class="col-12 text-center my_text_maincolor">
                <?php if (isset($errMSG)) { ?>
                    <div class="alert alert-<?php echo $errTyp ?>">
                        <p><?php echo $errMSG; ?></p>
                        <p><?php echo $uploadError; ?></p>
                    </div>
                <?php } ?>
            </div>

            <div class="justify-content-center d-flex">
                <form class="col-12 col-md-6 flex-column" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" enctype="multipart/form-data">
                    <div class="col-12 my-3">
                        <label for="first_name" class="form-label">First name</label>
                        <input id="first_name" type="text" name="first_name" class="form-control" placeholder="Enter your first name" maxlength="100" value="<?php echo $first_name ?>" />
                        <span class="text-danger"> <?php echo $fnameError; ?> </span>
                    </div>

                    <div class="col-12 my-3">
                        <label for="last_name" class="form-label">Last name</label>
                        <input id="last_name" type="text" name="last_name" class="form-control" placeholder="Enter your last name" maxlength="100" value="<?php echo $last_name ?>" />
                        <span class="text-danger"> <?php echo $lnameError; ?> </span>
                    </div>

                    <div class="col-12 my-3">
                        <label for="email" class="form-label">E-Mail</label>
                        <input id="email" type="email" name="email" class="form-control" placeholder="Enter your e-mail" maxlength="100" value="<?php echo $email ?>" />
                        <span class="text-danger"> <?php echo $emailError; ?> </span>
                    </div>

                    <div class="col-12 my-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="pass" class="form-control" placeholder="Enter password" maxlength="255" />
                        <span class="text-danger"> <?php echo $passError; ?> </span>
                    </div>

                    <div class="d-flex row">
                        <div class="col-12 col-md-6 pe-md-3 mb-3 mb-md-0">
                            <label for="birthdate" class="form-label">Your date of birth</label>
                            <input id="birthdate" class='form-control' type="date" name="birthdate" value="<?php echo $birthdate ?>" />
                            <span class="text-danger"> <?php echo $birthdateError; ?> </span>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="avatar" class="form-label">Your image</label>
                            <input id="avatar" class='form-control' type="file" name="profile_image">
                            <span class="text-danger"> <?php echo $picError; ?> </span>
                        </div>
                    </div>

                    <div class="col-12 my-3">
                        <label for="address" class="form-label">Your address</label>
                        <input id="address" type="text" name="address" class="form-control" placeholder="Enter your address" maxlength="255" value="<?php echo $address ?>" />
                        <span class="text-danger"> <?php echo $addressError; ?> </span>
                    </div>

                    <div class="col-12 my-3">
                        <label for="city" class="form-label">Your city</label>
                        <input id="city" type="text" name="city" class="form-control" placeholder="Enter your city" maxlength="120" value="<?php echo $city ?>" />
                        <span class="text-danger"> <?php echo $cityError; ?> </span>
                    </div>

                    <div class="col-12 my-3">
                        <label for="postcode" class="form-label">Your postcode</label>
                        <input id="postcode" type="text" name="postcode" class="form-control" placeholder="Enter your postcode" maxlength="12" value="<?php echo $postcode ?>" />
                        <span class="text-danger"> <?php echo $postcodeError; ?> </span>
                    </div>

                    <div class="col-12 my-3">
                        <label for="country" class="form-label">Your country</label>
                        <input id="country" type="text" name="country" class="form-control" placeholder="Enter your country" maxlength="50" value="<?php echo $country ?>" />
                        <span class="text-danger"> <?php echo $countryError; ?> </span>
                    </div>

                    <button type="submit" class="btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-md-3 mb-2" name="btnRegister">Register</button>

                    <div class="btn btn bg_lightgray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-md-3">
                        <a href="login.php">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
        require_once 'components/footer.php';
        footer("../");
        require_once 'components/boot-javascript.php';
    ?>
</body>
</html>