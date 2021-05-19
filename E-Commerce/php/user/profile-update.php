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
    
    $uploadError = '';    

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
    <link rel='stylesheet' type='text/css' href='styles.css'>
</head>

<body>
    <div id="container">
        <div id="content">

            <div class="<?php echo $class; ?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>       
            </div>

            <h1>Update Profile</h1>
           
            <form  method="post" enctype="multipart/form-data">
            <table class='table'>
                <tr>
                    <th>First name</th>
                    <td>
                        <input class="form-control" type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Last name</th>
                    <td>
                        <input class="form-control" type="text" name="lastName" placeholder="First Name" value="<?php echo $lastName ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        <input class="form-control" type="text" name="address" placeholder="First Name" value="<?php echo $address ?>" />
                    </td>
                </tr>
                <tr>
                    <th>City</th>
                    <td>
                        <input class="form-control" type="text" name="city" placeholder="First Name" value="<?php echo $city ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Postcode</th>
                    <td>
                        <input class="form-control" type="text" name="postcode" placeholder="First Name" value="<?php echo $postcode ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td>
                        <input class="form-control" type="text" name="country" placeholder="First Name" value="<?php echo $country ?>" />
                    </td>
                </tr>
                <tr>
                    <th>Birthdate</th>
                    <td>
                        <input class="form-control" type="text" name="birthdate" placeholder="First Name" value="<?php echo $birthdate ?>" />
                    </td>
                </tr>
                <tr>
                    <th>
                        <input type="hidden" name="ids" value="<?php echo $data['pk_user_id'] ?>" />
                        <a href="javascript:history.back()"><button class='btn btn-light' type="button">Back</button></a>
                    </th>
                    <td><button name="submit" class="btn btn-dark" type= "submit">Save Changes</button></td>
                </tr>
            </table>
            </form> 

        </div>
</body>

</html>