<?php

require_once "../../DB-sql/db.php";


$error = null;
$valid = true;

function sanitizeUserInput ($fieldInput, $fieldName) {
    // --- sanitize user input to prevent sql injection --- //
    $fieldInput = trim($_POST[$fieldName]);     
    $fieldInput = strip_tags($fieldInput);       
    $fieldInput = htmlspecialchars($fieldInput);  // htmlspecialchars converts special characters to HTML entities
    return $fieldInput;
}

if (isset($_POST['submit-button'])) {
    

    $first_name = sanitizeUserInput($first_name, 'first_name');
    $last_name = sanitizeUserInput($last_name, 'last_name');
    
    $email = sanitizeUserInput($email, 'email');
    $address = sanitizeUserInput($address, 'address');
    $city = sanitizeUserInput($city, 'city');
    $postcode = sanitizeUserInput($postcode, 'postcode');
    $country = sanitizeUserInput($country, 'country');
    $birthdate = sanitizeUserInput($birthdate, 'birthdate');
    $profile_image = sanitizeUserInput($profile_image, 'profile_image');
    



    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "This is not a valid email address";
        $valid = false;
    }

    if (empty($password)) {
        $error = "Password is required";
        $valid = false;
    } else if (strlen($password) < 6) {
        $valid = false;
        $error = "Password must have at least 6 characters.";
    }

    if ($valid) {

        
        
           

        $password = trim($password);
        $password = strip_tags($password);
        $password = htmlspecialchars($password);
        $password = hash('sha256', $password);

        mysqli_real_escape_string($connect, $email);
        mysqli_real_escape_string($connect, $password);
        

        $query = "SELECT pk_user_id FROM user WHERE email='$email' LIMIT 1";
        $result = mysqli_query($connect, $query);

        if ($result->num_rows < 1) {
            $query = "INSERT INTO user (email, password, first_name,last_name,adress,city,postcode,country,birthdate) VALUES ('$email', '$password', '$first_name','$last_name','$address','$city','$postcode','$country','$birthdate')";
            var_dump($query);
            mysqli_query($connect, $query);

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['pk_user_id'] = mysqli_insert_id($connect);
            $_SESSION['type'] = $type;

            if ($type == "user") {
                header("Location: home.php");
            } else {
                header("Location: admin.php");
            }
        } else {
            $error = "User with this email already exists";
        }
    }
}

?>

<!DOCTYPE HTML>

<html>

<head>
    <?php require_once "../../DB-sql/head.php" ?>
</head>

<body>
<div class="d-flex justify-content-center align-items-center bg-muted flex-column">
    

    <form method="POST" action="register.php" class="col-11 col-lg-3 mt-5">

        <?php if ($error !== null) : ?>
            <p class="text-danger"><?= $error ?></p>
        <?php endif; ?>

     

        <div class="mb-3">

        <h1>Register</h1>
        <p>Please fill in this form to create an account.</p>
        <hr>
        </div>
        <div class="mb-3">
        <label class="form-label" for="email"><b>Email:</b></label>
        <input class="form-control" type="text" placeholder="Enter Email" name="email" id="email" required>
        </div>
        <div class="mb-3">
        <label label class="form-label" for="password"><b>Password:</b></label>
        <input class="form-control" type="password" placeholder="Enter Password" name="password" id="password" required>
        </div>
        <div class="mb-3">
        <label label class="form-label" for="psw-repeat"><b>Repeat Password:</b></label>
        <input class="form-control" type="password" placeholder="Repeat Password" name="psw-repeat" id="psw-repeat" required>
        
        </div>
        <div class="mb-3">
        <label label class="form-label" for="first_name"><b>First Name:</b></label>
        <input class="form-control" type="text" placeholder="Enter First Name" name="first_name" id="first_name" required>
        </div>
        <div class="mb-3">
        <label label class="form-label" for="last_name"><b>Last Name:</b></label>
        <input class="form-control" type="text" placeholder="Enter Last Name" name="last_name" id="last_name" required>
        </div>
        <div class="mb-3">
        <label label class="form-label" for="address"><b>Address:</b></label>
        <input class="form-control" type="text" placeholder="Enter Adress" name="address" id="address" required>
        </div>
        <div class="mb-3">
        <label label class="form-label" for="city"><b>City:</b></label>
        <input class="form-control" type="text" placeholder="Enter City" name="city" id="city" required>
        </div>
        <div>
        <label for="postcode"><b>Postcode:</b></label>
        <input class="form-control" type="text" placeholder="Enter post code" name="postcode" id="postcode" required>
        </div>
        <div>
        <label label class="form-label" for="country"><b>Country:</b></label>
        <input class="form-control" type="text" placeholder="Enter Country" name="country" id="country" required>
        </div>
        <div>
        <label label class="form-label" for="birthdate"><b>Birthdate:</b></label>
        <input class="form-control" type="text" placeholder="Enter your Birthdate" name="birthdate" id="birthdate" required>
        </div>
        <div>
        <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
        <input value="Register new account" type="submit" name="submit-button" class="btn btn-primary">
        </div>

        <div class="container signin">
            <p>Already have an account? <a href="#">Sign in</a>.</p>
        </div>
    </form>
</div>
</body>
<?php

?>

</html>
<?php $connect->close(); ?>