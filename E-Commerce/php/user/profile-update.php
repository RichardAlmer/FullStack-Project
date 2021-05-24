<?php

// To Do: Session Stuff ------------------------------------------------

// To Do - Nice have: Update image ------------------------------------------------

require_once '../components/db_connect.php';



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
    }   
}

$error = false;
$firstName = $lastName  = $address = $city = $postcode = $country = $birthdate = '';
$fnameError = $lnameError = $addressError = $cityError = $postcodeError = $countryError = $birthdateError =  '';

//update on submit
$class = 'd-none';
if (isset($_POST["submit"])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postcode = $_POST['postcode'];
    $country = $_POST['country'];
    $birthdate = $_POST['birthdate'];
    $id = $_POST['ids'];

    $firstName = trim($_POST['first_name']);
    $firstName = strip_tags($firstName);
    $firstName = htmlspecialchars($firstName);

    $lastName = trim($_POST['last_name']);
    $lastName = strip_tags($lastName);
    $lastName = htmlspecialchars($lastName);

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

   

    $birthdate = trim($_POST['birthdate']);
    $birthdate = strip_tags($birthdate);
    $birthdate = htmlspecialchars($birthdate);

    
    $uploadError = '';    

     // basic name validation
     if (empty($firstName) || empty($lastName)) {
        $error = true;
        $fnameError = "Please enter your full name and surname.";
    } else if (strlen($firstName) < 3 || strlen($lastName) < 3) {
        $error = true;
        $fnameError = "Name and surname must have at least 3 characters.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $firstName) || !preg_match("/^[a-zA-Z]+$/", $lastName)) {
        $error = true;
        $fnameError = "Name and surname must contain only letters and no spaces.";
    }


    //checks if the other inputs was left empty
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

    $sql = "UPDATE user SET first_name = '$firstName', last_name = '$lastName', address = '$address', city = '$city', postcode = '$postcode', country = '$country', birthdate = '$birthdate' 
    WHERE pk_user_id = '$id'";
    
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

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <?php require_once '../components/boot.php'?>
    <link rel="stylesheet" href="../../style/main-style.css" />
</head>

<body>
    <?php 
        require_once '../components/header.php';
        navbar("../../");
    ?>
    <div id="container" class="container">
        <div id="content" class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Update Profile</div>

            <div class="<?php echo $class; ?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>       
            </div>

            <form class="my-3"  method="post" enctype="multipart/form-data">
                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">First name</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName ?>" />
                        <span class="text-danger"> <?php echo $fnameError; ?> </span>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Last name</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="lastName" placeholder="Last Name" value="<?php echo $lastName ?>" />
                        <span class="text-danger"> <?php echo $lnameError; ?> </span>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Address</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="address" placeholder="Adress" value="<?php echo $address ?>" />
                        <span class="text-danger"> <?php echo $addressError; ?> </span>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">City</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="city" placeholder="City" value="<?php echo $city ?>" />
                        <span class="text-danger"> <?php echo $cityError; ?> </span>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Postcode</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="postcode" placeholder="Postcode" value="<?php echo $postcode ?>" />
                        <span class="text-danger"> <?php echo $postcodeError; ?> </span>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Country</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="text" name="country" placeholder="County" value="<?php echo $country ?>" />
                        <span class="text-danger"> <?php echo $countryError; ?> </span>
                    </div>
                </div>

                <div class="row py-2 align-items-center">
                    <div class="col-12 col-md-3 fw-bold py-2">Birthdate</div>
                    <div class="col-12 col-md-9 pb-3 py-md-2">
                        <input class="form-control" type="date" name="birthdate" placeholder="Birthdate" value="<?php echo $birthdate ?>" />
                        <span class="text-danger"> <?php echo $birthdateError; ?> </span>
                    </div>
                </div>

                <div class="row py-4">
                    <input type="hidden" name="ids" value="<?php echo $data['pk_user_id'] ?>" />

                    <div class="">
                        <a href="javascript:history.back()">
                            <button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1' type="button">Back</button>
                        </a>
                        <button name="submit" class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type= "submit">Save Changes</button>
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