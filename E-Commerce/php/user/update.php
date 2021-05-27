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

//fetch and populate form
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $sql = "SELECT * FROM user WHERE pk_user_id = {$userId}";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();

        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $email = $data['email'];
        $password = $data['password'];

        $birthDate = $data['birthdate'];
        $picture = $data['profile_image'];

        $street = $data['address'];
        $zipCode = $data['postcode'];
        $city = $data['city'];
        $country = $data['country'];

        //role dropdown
        $roleList = ["user", "admin"];
        $selectedRole = $data['role'];
        $roleOptions = "";
        foreach ($roleList as $role) {
            if ($role == $selectedRole) {
                $roleOptions .= "<option selected value='$selectedRole'>" . ucfirst($selectedRole) . "</option>";
            } else {
                $roleOptions .= "<option value='$role'>" . ucfirst($role) . "</option>";
            }
        }

        //status dropdown
        $statusList = ["active", "inactive"];
        $selectedStatus = $data['status'];
        $statusOptions = "";
        foreach ($statusList as $status) {
            if ($status == $selectedStatus) {
                $statusOptions .= "<option selected value='$selectedStatus'>" . ucfirst($selectedStatus) . "</option>";
            } else {
                $statusOptions .= "<option value='$status'>" . ucfirst($status) . "</option>";
            }
        }

        //banned until
        $bannedUntil = $data['banned_until'];
        $time = strtotime($bannedUntil);
        $timeResult = date('Y.m.d H:i:s', $time);

        //you cannot ban other admins
        if ($role == 'admin') {
            $bannedUntil = NULL;
        }
    }
}

$firstNameError = $lastNameError = $emailError =  $passwordError = $birthDateError = $streetError = $zipCodeError = $cityError = $countryError = $roleError = $statusError = $pictureError = '';
//update
$class = 'd-none';
if (isset($_POST["btnSave"])) {

    // var_dump($_POST);

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
    $bannedUntilNew = $_POST['bannedUntilNew'];
    $intoDB = "";

    //IF there is a new bannedUntil value, the old one is overwritten!
    if (!empty($bannedUntilNew)) {
        $intoDB = $bannedUntilNew;
    } else {
        $intoDB = $bannedUntil;
    }

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
        $query = "SELECT pk_user_id, email FROM user WHERE email='$email'";
        $result = mysqli_query($conn, $query);
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
        if ($count != 0 && $userId != $rows[0]['pk_user_id']) {
            $error = true;
            $emailError = "Provided Email is already in use.";
        }
    }

    // use old password if no new input
    if (empty($password)) {
        $sql = "SELECT * FROM user WHERE pk_user_id = {$userId}";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        $newPassword = $data['password'];
    } else if (strlen($password) < 6) { //if new input check passw length
        $error = true;
        $passwordError = "Password must have at least 6 characters.";
    } else {
        // password hashing for security
        $newPassword = hash('sha256', $password);
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
        $zipCodeError = "Please enter a ZIP-Code.";
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
        $intoDB = NULL;
    }

    // // password hashing for security
    // $password = hash('sha256', $password);

    // if there's no error, continue to signup
    if (!$error) {
        //variable for upload pictures errors is initialized
        $uploadError = '';
        $pictureArray = file_upload($_FILES['picture']); //file_upload() called
        $picture = $pictureArray->fileName;

        //if banned until is empty (like when never used or unbanned) then insert NULL into db
        if (empty($intoDB)) {
            if ($pictureArray->error === 0) {
                ($_POST["picture"] == "default-user.jpg") ?: unlink("../../img/user_images/{$_POST["picture"]}");
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$newPassword', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = NULL, profile_image = '$pictureArray->fileName' WHERE pk_user_id = '$userId'";
            } else {
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$newPassword', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = NULL WHERE pk_user_id = '$userId'";
            }
        } else {
            if ($pictureArray->error === 0) {
                ($_POST["picture"] == "default-user.jpg") ?: unlink("../../img/user_images/{$_POST["picture"]}");
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$newPassword', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = '$intoDB', profile_image = '$pictureArray->fileName' WHERE pk_user_id = '$userId'";
            } else {
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$newPassword', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = '$intoDB' WHERE pk_user_id = '$userId'";
            }
        }

        $result = $conn->query($sql);

        if ($result) {
            $class = "alert alert-success";
            $message = "The record was successfully updated";
            $uploadError = ($pictureArray->error != 0) ? $pictureArray->ErrorMessage : '';
            header("refresh:3;url=update.php?id={$userId}");
        } else {
            $class = "alert alert-danger";
            $message = "Error while updating record : <br>" . $conn->error;
            $uploadError = ($pictureArray->error != 0) ? $pictureArray->ErrorMessage : '';
            header("refresh:3;url=update.php?id={$userId}");
        }
    }
}

//Unban
if (isset($_POST["btnUnban"])) {
    $userId = $_POST["id"];

    $sql = "UPDATE user SET banned_until = NULL WHERE pk_user_id = '$userId'";
    $result = $conn->query($sql);

    if ($result) {
        $class = "alert alert-success";
        $message = "The ban was successfully lifted";
        header("refresh:3;url=update.php?id={$userId}");
    } else {
        $class = "alert alert-danger";
        $message = "Error while updating ban entry : <br>" . $conn->error;
        header("refresh:3;url=update.php?id={$userId}");
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
        $dataC = $result->fetch_assoc();
        $image = $dataC['profile_image'];
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
    <title>Update User</title>
    <?php require_once '../components/boot.php' ?>
    <link rel="stylesheet" href="../../style/main-style.css" />
    <link rel="stylesheet" href="../../style/updateUser.css">
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
            <div class="col-12 fs_6 text-uppercase my-2">Update</div>

            <div class="<?php echo $class; ?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>
            </div>

            <div class="col-12">
                <img class='img-thumbnail rounded-circle' src='../../img/user_images/<?php echo $picture ?>' alt="<?php echo $firstName ?>">
            </div>

            <form class="my-3" method="post" enctype="multipart/form-data">
                <table class="table">
                    <div class="row py-2 align-items-center">
                        <div class="col-12 col-md-3 fw-bold py-2">First name</div>
                        <div class="col-12 col-md-9 pb-3 py-md-2">
                            <input class="form-control" type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName ?>" maxlength="100" />
                        </div>
                        <span class="text-danger"> <?php echo $firstNameError; ?> </span>
                    </div>

                    <div class="row py-2 align-items-center">
                        <div class="col-12 col-md-3 fw-bold py-2">Last name</div>
                        <div class="col-12 col-md-9 pb-3 py-md-2">
                            <input class="form-control" type="text" name="lastName" placeholder="Last Name" value="<?php echo $lastName ?>" maxlength="100" />
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
                            <input class="form-control" type="password" name="password" placeholder="New password" maxlength="255" />
                        </div>
                        <span class="text-danger"> <?php echo $passwordError; ?> </span>
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

                        <?php if ($time) { ?>
                            <td>
                                <p id="banned"><?php echo $timeResult ?></p>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="submit" name="btnUnban" class="btn btn-secondary" value="Unban">
                                    <input type="hidden" name="id" value="<?php echo $userId ?>">
                                </form>
                            </td>
                        <?php } ?>

                        <div class="col-12 col-md-9 pb-3 py-md-2">
                            <input class="form-control" type="datetime-local" name="bannedUntilNew" placeholder="Banned until YYYY-MM-DD HH:MM:SS" />

                        </div>
                    </div>

                    <div>
                        <input type="hidden" name="id" value="<?php echo $data['pk_user_id'] ?>" />
                        <input type="hidden" name="bannedUntil" value="<?php echo $data['banned_until'] ?>" />
                        <input type="hidden" name="picture" value="<?php echo $picture ?>" />
                        <a href="users.php"><button class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="button">Back</button></a>
                        <button name="btnSave" class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="submit">Save Changes</button>
                    </div>
                </table>
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