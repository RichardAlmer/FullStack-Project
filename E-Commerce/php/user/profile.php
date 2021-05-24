<?php
session_start();
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
        $id = "";
        $session = "";
        if(isset($_SESSION['admin'])){
            $id = $_SESSION['admin'];
            $session = "admin";
        } else if(isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
            $session = "user";
        }
        navbar("../../", "../", "../", $id, $session);
    ?>
    <div id="container" class="container">
        <div class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Hello, <span class="my_text_maincolor"><?php echo $firstName ?></span></div>
            <div id="content" class="row align-items-start">
                <?php
                if (isset($errMSG)) {
                    ?>
                <div class="alert alert-<?php echo $errTyp ?>">
                    <p><?php echo $errMSG; ?></p>
                </div>
                <?php
                }
                ?>

                <div class="row col-12 col-md-4">
                    <?php 
                        if ($role == 'admin') {
                            echo "<img src='../../img/user_images/default-admin.jpg'>";
                        } else {
                            echo "<img src='../../img/user_images/default-user.jpg'>";
                        }
                    
                    ?>
                </div>
                <div class="row col-12 col-md-8 py-5">
                    <div class="col-12 col-md-4 fw-bold py-2">First name</div>
                    <div class="col-12 col-md-8 pb-3 py-md-2"><?php echo $firstName ?></div>

                    <div class="col-12 col-md-4 fw-bold py-2">Last name</div>
                    <div class="col-12 col-md-8 pb-3 py-md-2"><?php echo $lastName ?></div>

                    <div class="col-12 col-md-4 fw-bold py-2">Country</div>
                    <div class="col-12 col-md-8 pb-3 py-md-2"><?php echo $country ?></div>

                    <div class="col-12 col-md-4 fw-bold py-2">Address</div>
                    <div class="col-12 col-md-8 pb-3 py-md-2"><?php echo $city .' '. $postcode .', '. $address ?></div>

                    <div class="col-12 col-md-4 fw-bold py-2">Birthdate</div>
                    <div class="col-12 col-md-8 pb-3 py-md-2"><?php echo $birthdate ?></div>
                </div>
            </div>
            
            <div class="container">
                <a href="javascript:history.back()">
                    <button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1' type="button">Back</button>
                </a>

                <a href='profile-update.php?id="<?php echo $id ?>"'>
                    <button class='col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1' type='button'>Update</button>
                </a>
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