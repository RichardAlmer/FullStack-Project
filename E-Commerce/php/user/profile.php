<?php

// To Do: Session Stuff ------------------------------------------------

require_once '../components/db_connect.php';

if (isset($_POST["submit"])) {
    $id = $_POST['id'];
}

if ($_GET['id']) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM user WHERE pk_user_id = {$id}";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();
        $id = $data['pk_user_id'];
        $email = $data['email'];
        $firstName = $data['first_name'];
        $lastName = $data['last_name'];
        $address = $data['address'];
        $city = $data['city'];
        $postcode = $data['postcode'];
        $country = $data['country'];
        $birthdate = $data['birthdate'];
        $role = $data['role'];
        //$image = $data['image'];
    } else {
        header("location: ../error.php");
    }
    $conn->close();
} else {
    header("location: ../error.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php require_once '../components/boot.php'?>
    <link rel='stylesheet' type='text/css' href='styles.css'>
</head>

<body>
    <div id="container">
        <div id="content">
            <h1>Profile</h1>
            <?php
            if (isset($errMSG)) {
                ?>
            <div class="alert alert-<?php echo $errTyp ?>">
                <p><?php echo $errMSG; ?></p>
            </div>
            <?php
            }
            ?>
            <div>
                <?php 
                    if ($role == 'admin') {
                        echo "<img src='../../img/default-admin.jpg'>";
                    } else {
                        echo "<img src='../../img/default-user.jpg'>";
                    }
                
                ?>
            </div>
            <table class='table'>
                <tr>
                    <th>First name</th>
                    <td>
                        <?php echo $firstName ?>
                    </td>
                </tr>
                <tr>
                    <th>Last name</th>
                    <td>
                        <?php echo $lastName ?>
                    </td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>
                        <?php echo $address ?>
                    </td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?php echo $city ?></td>
                </tr>
                <tr>
                    <th>Postcode</th>
                    <td><?php echo $postcode ?></td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td><?php echo $country ?></td>
                </tr>
                <tr>
                    <th>Birthdate</th>
                    <td><?php echo $birthdate ?></td>
                </tr>
                <tr>
                    <th><a href="javascript:history.back()"><button class='btn btn-light' type="button">Back</button></a></th>
                    <td>
                        <a href='profile-update.php?id="<?php echo $id ?>"'><button class='btn btn-secondary' type='button'>Update</button></a>
                    </td>
                </tr>
            </table>

            
    </div>
</body>

</html>