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

$userId = $_SESSION['admin'];
$protectedRole = 'superadmin';
//protect super admin from deletion
$sqlSelect = "SELECT * FROM user WHERE role != ?";
$stmt = $conn->prepare($sqlSelect);
$stmt->bind_param("s", $protectedRole);
$work = $stmt->execute();
$result = $stmt->get_result();

//this variable will hold the body for the table
$tbody = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $tbody .= "
        <tr>
            <td><img class='img-thumbnail rounded-circle' src='../../img/user_images/" . $row['profile_image'] . "' alt=" . $row['first_name'] . "></td>
            <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
            <td>" . $row['birthdate'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['banned_until'] . "</td>
            <td><a href='update.php?id=" . $row['pk_user_id'] . "'><button class='col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-4 text-white' type='button'>Update</button></a>
            <a href='delete.php?id=" . $row['pk_user_id'] . "'><button class='col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-4 text-white' type='button'>Delete</button></a></td>
        </tr>";
    }
} else {
    $tbody = "<tr><td colspan='6'><center>No Data Available</center></td></tr>";
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
    <title>Manage Users</title>
    <?php require_once '../components/boot.php' ?>
    <link rel="stylesheet" href="../../style/main-style.css" />
    <style type="text/css">
        .img-thumbnail {
            width: 10rem;
            height: 10rem;
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

    <div id="container" class="container">
        <div id="content" class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Manage Users</div>

            <a href='../admin/dashboard.php' class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-4">Back to dashboard</a>
            <a href="create.php"><button class='col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-4' type="button">Add User</button></a>

            <table class='table table-striped'>
                <thead class='bg_maincolor'>
                    <tr>
                        <th class="border-0">Picture</th>
                        <th class="border-0">Name</th>
                        <th class="border-0">Date of birth</th>
                        <th class="border-0">Email</th>
                        <th class="border-0">Banned until</th>
                        <th class="border-0">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $tbody ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
        require_once '../components/footer.php';

    ?>

    <?php require_once '../components/boot-javascript.php' ?>

</body>

</html>