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
            $emailError = "Provided Email is already in use.";
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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
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

        <h2>Create New User</h2>

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
                        <input class="form-control" type="password" name="password" placeholder="Password" maxlength="255" />
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
                    <td><input class="form-control" type="datetime-local" name="bannedUntil" placeholder="Banned until" /></td>
                </tr>

                <tr>
                    <td><a href="users.php"><button class="btn btn-warning" type="button">Back</button></a></td>
                    <td><button name="btnCreate" class="btn btn-success" type="submit">Create User</button></td>
                </tr>
            </table>
        </form>
    </div>

</body>

</html>