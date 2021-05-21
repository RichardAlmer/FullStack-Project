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
        $timeResult = date('d-m-y H:i:s', $time);

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

    // var_dump(!empty($bannedUntil)); //if no date = false
    // var_dump(!empty($bannedUntilNew)); //if date = true

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

    // password validation
    if (empty($pass)) {
        $passwordNew = $password;
    } else if (strlen($pass) < 6) {
        $error = true;
        $passError = "Password must have at least 6 characters.";
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

    // password hashing for security
    $password = hash('sha256', $password);

    // if there's no error, continue to signup
    if (!$error) {
        //variable for upload pictures errors is initialized
        $uploadError = '';
        $pictureArray = file_upload($_FILES['picture']); //file_upload() called
        $picture = $pictureArray->fileName;

        //if banned until is empty then insert NULL into db
        if (empty($intoDB)) {
            if ($pictureArray->error === 0) {
                ($_POST["picture"] == "default-user.jpg") ?: unlink("../../img/user_images/{$_POST["picture"]}");
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$password', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = NULL, profile_image = '$pictureArray->fileName' WHERE pk_user_id = '$userId'";
            } else {
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$password', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = NULL WHERE pk_user_id = '$userId'";
            }
        } else {
            if ($pictureArray->error === 0) {
                ($_POST["picture"] == "default-user.jpg") ?: unlink("../../img/user_images/{$_POST["picture"]}");
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$password', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = '$intoDB', profile_image = '$pictureArray->fileName' WHERE pk_user_id = '$userId'";
            } else {
                $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', email = '$email', password = '$password', birthdate = '$birthDate', address = '$street', postcode = '$zipCode', city = '$city', country = '$country', role = '$role', status = '$status', banned_until = '$intoDB' WHERE pk_user_id = '$userId'";
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
    echo "unban says hi!";
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <?php require_once '../components/boot.php' ?>
    <link rel="stylesheet" href="../../style/updateUser.css">
    <style type="text/css">
        fieldset {
            margin: auto;
            margin-top: 100px;
            width: 60%;
        }

        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
        }
    </style>
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

        <h2>Update</h2>

        <img class='img-thumbnail rounded-circle' src='../../img/user_images/<?php echo $picture ?>' alt="<?php echo $firstName ?>">

        <form method="post" enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <th>First Name</th>
                    <td><input class="form-control" type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName ?>" maxlength="100" /></td>
                    <span class="text-danger"> <?php echo $firstNameError; ?> </span>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><input class="form-control" type="text" name="lastName" placeholder="Last Name" value="<?php echo $lastName ?>" maxlength="100" /></td>
                    <span class="text-danger"> <?php echo $lastNameError; ?> </span>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo $email ?>" maxlength="100" /></td>
                    <span class="text-danger"> <?php echo $emailError; ?> </span>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>
                        <input class="form-control" type="password" name="password" placeholder="New password" maxlength="255" />
                        <span class="text-danger"> <?php echo $passwordError; ?> </span>
                    </td>
                </tr>
                <tr>
                    <th>Date of birth</th>
                    <td><input class="form-control" type="date" name="birthDate" placeholder="Date of birth" value="<?php echo $birthDate ?>" /></td>
                    <span class="text-danger"> <?php echo $birthDateError; ?> </span>
                </tr>
                <tr>
                    <th>Picture</th>
                    <td><input class="form-control" type="file" name="picture" /></td>
                    <span class="text-danger"> <?php echo $pictureError; ?> </span>
                </tr>
                <tr>
                    <th>Street</th>
                    <td><input class="form-control" type="text" name="street" placeholder="Street" value="<?php echo $street ?>" maxlength="255" /></td>
                    <span class="text-danger"> <?php echo $streetError; ?> </span>
                </tr>
                <tr>
                    <th>ZIP-Code</th>
                    <td><input class="form-control" type="text" name="zipCode" placeholder="ZIP-Code" value="<?php echo $zipCode ?>" maxlength="12" /></td>
                    <span class="text-danger"> <?php echo $zipCodeError; ?> </span>
                </tr>
                <tr>
                    <th>City</th>
                    <td><input class="form-control" type="text" name="city" placeholder="City" value="<?php echo $city ?>" maxlength="120" /></td>
                    <span class="text-danger"> <?php echo $cityError; ?> </span>
                </tr>
                <tr>
                    <th>Country</th>
                    <td><input class="form-control" type="text" name="country" placeholder="Country" value="<?php echo $country ?>" maxlength="50" /></td>
                    <span class="text-danger"> <?php echo $countryError; ?> </span>
                </tr>

                <tr>
                    <th>Role</th>
                    <td>
                        <select class="form-select" name="role" aria-label="Default select example">
                            <?php echo $roleOptions; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <select class="form-select" name="status" aria-label="Default select example">
                            <?php echo $statusOptions; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Banned Until:</th>

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

                    <td><input class="form-control" type="datetime-local" name="bannedUntilNew" placeholder="Banned until" /></td>
                </tr>

                <tr>
                    <input type="hidden" name="id" value="<?php echo $data['pk_user_id'] ?>" />
                    <input type="hidden" name="bannedUntil" value="<?php echo $bannedUntil ?>" />
                    <input type="hidden" name="picture" value="<?php echo $picture ?>" />
                    <td><a href="users.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                    <td><button name="btnSave" class="btn btn-success" type="submit">Save Changes</button></td>
                </tr>
            </table>
        </form>
    </div>



</body>

</html>