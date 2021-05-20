<?php
session_start();
if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
    header("Location: ../../index.php");
    exit;
}
$backBtn = '';
if (isset($_SESSION["user"])) {
    $backBtn = "../product/produrct-catalog.php";
}
if (isset($_SESSION["admin"])) {
    $backBtn = "../admin/dashBoard.php";
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

        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $email = $data['email'];
        $password = $data['password'];

        $birthDate = $data['birthdate'];
        $profileImage = $data['profile_image'];

        $address = $data['address'];
        $postcode = $data['postcode'];
        $city = $data['city'];
        $country = $data['country'];

        //role dropdown
        $roleList = ["user", "admin"];
        $selectedRole = $data['role'];
        $roleOptions = "";
        foreach ($roleList as $role) {
            if ($role == $selectedRole) {
                $roleOptions .= "<option selected value='$selectedRole'>" . ucfirst($selectedRole) . "</option>";
            } else {
                $roleOptions .= "<option value='$role'>" . ucfirst($role) . "</option>";
            }
        }

        //status dropdown
        $statusList = ["user", "admin"];
        $selectedStatus = $data['role'];
        $statusOptions = "";
        foreach ($statusList as $status) {
            if ($status == $selectedStatus) {
                $statusOptions .= "<option selected value='$selectedStatus'>" . ucfirst($selectedStatus) . "</option>";
            } else {
                $statusOptions .= "<option value='$status'>" . ucfirst($status) . "</option>";
            }
        }

        // $status = $data['status'];
        // $bannedUnitl = $data['banned_until'];
        // if ($role == 'admin') {
        //     $bannedUnitl = null;
        // }
    }
}

// //update
// $class = 'd-none';
// if (isset($_POST["submit"])) {
//     $f_name = $_POST['first_name'];
//     $l_name = $_POST['last_name'];
//     $email = $_POST['email'];
//     $date_of_birth = $_POST['date_of_birth'];
//     $userId = $_POST['id'];
//     //variable for upload pictures errors is initialized
//     $uploadError = '';
//     $pictureArray = file_upload($_FILES['picture']); //file_upload() called
//     $profileImage = $pictureArray->fileName;
//     if ($pictureArray->error === 0) {
//         ($_POST["picture"] == "avatar.png") ?: unlink("pictures/{$_POST["picture"]}");
//         $sql = "UPDATE user SET first_name = '$f_name', last_name = '$l_name', email = '$email', date_of_birth = '$birthDate', picture = '$pictureArray->fileName' WHERE id = {$id}";
//     } else {
//         $sql = "UPDATE user SET first_name = '$f_name', last_name = '$l_name', email = '$email', date_of_birth = '$birthDate' WHERE id = {$id}";
//     }
//     if ($conn->query($sql) === true) {
//         $class = "alert alert-success";
//         $message = "The record was successfully updated";
//         $uploadError = ($pictureArray->error != 0) ? $pictureArray->ErrorMessage : '';
//         header("refresh:3;url=update.php?id={$id}");
//     } else {
//         $class = "alert alert-danger";
//         $message = "Error while updating record : <br>" . $conn->error;
//         $uploadError = ($pictureArray->error != 0) ? $pictureArray->ErrorMessage : '';
//         header("refresh:3;url=update.php?id={$id}");
//     }
// }

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <?php require_once '../components/boot.php' ?>
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
            <p><?php echo ($uploadError) ?? ''; ?></p>
        </div>

        <h2>Update</h2>

        <img class='img-thumbnail rounded-circle' src='pictures/<?php echo $data['profile_image'] ?>' alt="<?php echo $first_name ?>">

        <form method="post" enctype="multipart/form-data">
            <table class="table">
                <tr>
                    <th>First Name</th>
                    <td><input class="form-control" type="text" name="first_name" placeholder="First Name" value="<?php echo $first_name ?>" /></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name ?>" /></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><input class="form-control" type="email" name="email" placeholder="Email" value="<?php echo $email ?>" /></td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td><input class="form-control" type="text" name="pass" placeholder="Password" value="<?php echo $password ?>" /></td>
                </tr>
                <tr>
                    <th>Date of birth</th>
                    <td><input class="form-control" type="date" name="birthdate" placeholder="Date of birth" value="<?php echo $birthdate ?>" /></td>
                </tr>
                <tr>
                    <th>Picture</th>
                    <td><input class="form-control" type="file" name="picture" /></td>
                </tr>
                <tr>
                    <th>Street</th>
                    <td><input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name ?>" /></td>
                </tr>
                <tr>
                    <th>ZIP-Code</th>
                    <td><input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name ?>" /></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name ?>" /></td>
                </tr>
                <tr>
                    <th>Country</th>
                    <td><input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name ?>" /></td>
                </tr>

                <tr>
                    <th>Role</th>
                    <td>
                        <select class="form-select" name="supplier" aria-label="Default select example">
                            <?php echo $roleOptions; ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>Status</th>
                    <td>
                        <select class="form-select" name="supplier" aria-label="Default select example">
                            <?php echo $statusOptions; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Banned Until</th>
                    <td><input class="form-control" type="text" name="last_name" placeholder="Last Name" value="<?php echo $last_name ?>" /></td>
                </tr>
                <tr>
                    <input type="hidden" name="id" value="<?php echo $data['pk_user_id'] ?>" />
                    <input type="hidden" name="picture" value="<?php echo $profileImage ?>" />
                    <td><button name="submit" class="btn btn-success" type="submit">Save Changes</button></td>
                    <td><a href="<?php echo $backBtn ?>"><button class="btn btn-warning" type="button">Back</button></a></td>
                </tr>
            </table>
        </form>
    </div>



</body>

</html>