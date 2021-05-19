<?php
session_start(); // start a new session or continues the previous
if (isset($_SESSION['user']) != "") {
    header("Location: home.php"); // redirects to home.php
}
if (isset($_SESSION['admin']) != "") {
    header("Location: dashboard.php"); // redirects to home.php
}
require_once 'components/db_connect.php';
require_once 'components/file_upload.php';         
$error = false;
$first_name = $last_name = $email = $address =$city= $postcode =$country = $birthdate = $password = $profile_image = '';
$fnameError = $lnameError = $emailError = $addressError = $cityError = $postcodeError = $countryError = $birthdateError = $passError = $picError = '';
if (isset($_POST['btn-signup'])) {

    // sanitize user input to prevent sql injection
    $first_name = trim($_POST['first_name']);

    //trim - strips whitespace (or other characters) from the beginning and end of a string
    $first_name = strip_tags($first_name);

    // strip_tags -- strips HTML and PHP tags from a string

    $first_name = htmlspecialchars($first_name);
    // htmlspecialchars converts special characters to HTML entities
    
    $last_name = trim($_POST['last_name']);
    $last_name = strip_tags($last_name);
    $last_name = htmlspecialchars($last_name);  
    
    $address = trim($_POST['address']);
    $address = strip_tags($address);
    $address = htmlspecialchars($address); 

    $address = trim($_POST['city']);
    $address = strip_tags($city);
    $address = htmlspecialchars($country); 

    $address = trim($_POST['postcode']);
    $address = strip_tags($postcode);
    $address = htmlspecialchars($postcode); 

    $address = trim($_POST['country']);
    $address = strip_tags($country);
    $address = htmlspecialchars($country); 

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $birthdate = trim($_POST['birthdate']);
    $birthdate = strip_tags($birthdate);
    $birthdate = htmlspecialchars($birthdate);

    $pass = trim($_POST['pass']);
    $pass = strip_tags($pass);
    $pass = htmlspecialchars($pass);

    $uploadError = '';
    $profile_image = file_upload($_FILES['profile_image']);

    // basic name validation
    if (empty($first_name) || empty($last_name)) {
        $error = true;
        $fnameError = "Please enter your full name and surname";
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
    //checks if the date input was left empty
    if (empty($birthdate)) {
        $error = true;
        $birthdateError = "Please enter your date of birth.";
    }
    if (empty($address)) {
        $error = true;
        $addressError = "Please enter the Adress.";
    }
    if (empty($city)) {
        $error = true;
        $cityError = "Please enter the City.";
    }
    if (empty($postcode)) {
        $error = true;
        $postcodeError = "Please enter the Postcode.";
    }
    if (empty($country)) {
        $error = true;
        $countryError = "Please enter the Country.";
    }
    // password validation
    if (empty($pass)) {
        $error = true;
        $passError = "Please enter password.";
    } else if (strlen($pass) < 6) {
        $error = true;
        $passError = "Password must have at least 6 characters.";
    }

    // password hashing for security
    $password = hash('sha256', $pass);
    // if there's no error, continue to signup
    if (!$error) {

         $query = "INSERT INTO user(first_name, last_name, address, city, postcode, country, password, birthdate, email, profile_image) VALUES('$first_name', '$last_name','$address','$city', '$postcode', '$country', '$password', '$birthdate', '$email', '$profile_image->fileName')";
        
        $res = mysqli_query($conn, $query);
        // var_dump($user);
        // var_dump($res);

        if ($res) {
            $errTyp = "success";
            $errMSG = "Successfully registered, you may login now";
            $uploadError = ($profile_image->error != 0) ? $profile_image->ErrorMessage : '';

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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registration System</title>
        <?php require_once 'components/boot.php'?>
    </head>
    <body>
        <div class="container">
            <form class="w-75" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  autocomplete="off" enctype="multipart/form-data">
                <h2>Registration</h2>
                <hr/>
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
              
                <input type ="text"  name="first_name"  class="form-control"  placeholder="First name" maxlength="100" value="<?php echo $first_name ?>"  />
                <span class="text-danger"> <?php echo $fnameError; ?> </span>

                <input type ="text"  name="last_name"  class="form-control"  placeholder="Surname" maxlength="100" value="<?php echo $last_name ?>"  />
                <span class="text-danger"> <?php echo $lnameError ; ?> </span>

                <input type ="text"  name="address"  class="form-control"  placeholder="Address" maxlength="255" value="<?php echo $address ?>"  />
                <span class="text-danger"> <?php echo $addressError; ?> </span>

                <input type ="text"  name="city"  class="form-control"  placeholder="City" maxlength="120" value="<?php echo $city ?>"  />
                <span class="text-danger"> <?php echo $cityError; ?> </span>

                <input type ="text"  name="postcode"  class="form-control"  placeholder="ZIP Code" maxlength="12" value="<?php echo $postcode ?>"  />
                <span class="text-danger"> <?php echo $postcodeError; ?> </span>

                <input type ="text"  name="country"  class="form-control"  placeholder="Country" maxlength="50" value="<?php echo $country ?>"  />
                <span class="text-danger"> <?php echo $countryError; ?> </span>

                <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="100" value ="<?php echo $email ?>"  />
                <span  class="text-danger"> <?php echo $emailError; ?> </span>
                <div class="d-flex">
                    <input class='form-control w-50' type="date"  name="birthdate" value ="<?php echo $birthdate ?>"/>
                    <span class="text-danger"> <?php echo $birthdateError; ?> </span>

                    <input class='form-control w-50' type="file" name="profile_image" >
                    <span class="text-danger"> <?php echo $picError; ?> </span>
                </div>
                <input type="password" name="pass" class="form-control" placeholder="Enter Password" maxlength="255"/>
                <span class="text-danger"> <?php echo $passError; ?> </span>
                <hr/>
                <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Register</button>
                <hr/>
                <a href="index.php">Login in Here...</a>
            </form>
        </div>
    </body>
</html>