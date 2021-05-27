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

        $role = $data['role'];
        $status = $data['status'];

        //banned until
        $bannedUntil = $data['banned_until'];
        $time = strtotime($bannedUntil);
        // echo date('d-m-y H:i:s', $time);
    }
}

//Delete
//protect super admin from deletion
 if (isset($_POST["btnDelete"])) {

    $userId = $_POST['id'];

    $picture = $_POST['picture'];
    ($picture =="default-user.jpg")?: unlink("../../img/user_images/$picture");
    $sql = "DELETE FROM user WHERE pk_user_id = {$userId} AND ROLE != 'superadmin'";
    if ($conn->query($sql) === TRUE && ($_POST['role'] === 'user' || $_POST['role'] === 'admin')) {
        $class = "alert alert-success";
        $message = "Successfully Deleted!";
        header("refresh:3;url=users.php");
    } elseif($conn->query($sql) === TRUE && $_POST['role'] === 'superadmin') {
        $class = "alert alert-danger";
        $message = "You are not allowed to delete this user!";
    } else {
        $class = "alert alert-danger";
        $message = "The entry was not deleted due to: <br>" . $conn->error;
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
        $data = $result->fetch_assoc();
        $image = $data['profile_image'];
        if($data['COUNT(quantity)'] != 0){
            $cartCount = $data['COUNT(quantity)'];
        }
    }

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <?php require_once '../components/boot.php' ?>
    <link rel="stylesheet" href="../../style/main-style.css" />
    <link rel="stylesheet" href="../../style/updateUser.css">
    <style type="text/css">
        .img-thumbnail {
            width: 70px !important;
            height: 70px !important;
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
            <div class="col-12 fs_6 text-uppercase my-2">Delete User</div>

            <div class="<?php echo $class; ?>" role="alert">
                <p><?php echo ($message) ?? ''; ?></p>
            </div>

            <div class="col-12 fs-5 my-3">You have selected the data below:</div>
            <table class="table my-3 col-12">
                <thead class='bg_maincolor'>
                    <tr>
                        <th class="border-0">Picture</th>
                        <th class="border-0">Name</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Date of birth</th>
                        <th class="border-0">Role</th>
                        <th class="border-0">Status</th>
                        <th class="border-0">Banned until</th>
                    </tr>
                </thead>
                <tbody class="my-3">
                    <tr>
                        <td>
                            <img class='img-thumbnail rounded-circle' src='../../img/user_images/<?php echo $picture ?>' alt="<?php echo $firstName ?>">
                        </td>
                        <td><?php echo "$firstName $lastName" ?></td>
                        <td><?php echo $email ?></td>
                        <td><?php echo $birthDate ?></td>
                        <td><?php echo $role ?></td>
                        <td><?php echo $status ?></td>
                        <td><?php echo $bannedUntil ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="fs-5 my-4">Do you really want to delete this user?</div>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $userId ?>" />
                <input type="hidden" name="role" value="<?php echo $role ?>" />
                <input type="hidden" name="picture" value="<?php echo $picture ?>" />
                <a href="users.php"><button class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="button">No, go back!</button></a>
                <button class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="submit" name="btnDelete">Yes, delete it!</button>
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