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
 if (isset($_POST["btnDelete"])) {

    $userId = $_POST['id'];

    $picture = $_POST['picture'];
    ($picture =="default-user.jpg")?: unlink("../../img/user_images/$picture");
    $sql = "DELETE FROM user WHERE pk_user_id = {$userId}";
    if ($conn->query($sql) === TRUE) {
        $class = "alert alert-success";
        $message = "Successfully Deleted!";
        header("refresh:3;url=users.php");
    } else {
        $class = "alert alert-danger";
        $message = "The entry was not deleted due to: <br>" . $conn->error;
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
        </div>

        <h2>Delete User</h2>
        <h5>You have selected the data below:</h5>

        <table class="table w-75 mt-3">
            <thead class='table-success'>
                <tr>
                    <th>Picture</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date of birth</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Banned until</th>
                </tr>
            </thead>
            <tbody>
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

        <h3 class="mb-4">Do you really want to delete this user?</h3>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $userId ?>" />
            <input type="hidden" name="picture" value="<?php echo $picture ?>" />
            <button class="btn btn-danger" type="submit" name="btnDelete">Yes, delete it!</button>
            <a href="users.php"><button class="btn btn-warning" type="button">No, go back!</button></a>
        </form>
    </div>

</body>

</html>