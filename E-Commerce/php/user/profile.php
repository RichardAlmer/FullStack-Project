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
    <link rel="stylesheet" href="../../style/main-style.css" />
</head>

<body>
    <?php 
        require_once '../components/header.php';
        navbar("../../");
    ?>
    <div id="container" class="container">
        <div class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Profile</div>
            <div id="content">
                <?php
                if (isset($errMSG)) {
                    ?>
                <div class="alert alert-<?php echo $errTyp ?>">
                    <p><?php echo $errMSG; ?></p>
                </div>
                <?php
                }
                ?>

                <div class="col-12 col-md-2">
                    <?php 
                        if ($role == 'admin') {
                            echo "<img src='../../img/user_images/default-admin.jpg'>";
                        } else {
                            echo "<img src='../../img/user_images/default-user.jpg'>";
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
                        <th><a href="javascript:history.back()"><button class='btn btn bg_lightgray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-md-3' type="button">Back</button></a></th>
                        <td>
                            <a href='profile-update.php?id="<?php echo $id ?>"'><button class='btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-md-3 mb-2' type='button'>Update</button></a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php 
        require_once '../components/footer.php';
        footer("../../");
        require_once '../components/boot-javascript.php';
    ?>
</body>

</html>