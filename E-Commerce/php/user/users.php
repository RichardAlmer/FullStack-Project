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
$status = 'admin';
$sqlSelect = "SELECT * FROM user WHERE status != ? ";
$stmt = $conn->prepare($sqlSelect);
$stmt->bind_param("s", $status);
$work = $stmt->execute();
$result = $stmt->get_result();

//this variable will hold the body for the table
$tbody = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        $tbody .= "
        <tr>
            <td><img class='img-thumbnail' src='../../img/user_images/" . $row['profile_image'] . "' alt=" . $row['first_name'] . "></td>
            <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
            <td>" . $row['birthdate'] . "</td>
            <td>" . $row['email'] . "</td>
            <td><a href='update.php?id=" . $userId . "'><button class='btn btn-primary btn-sm' type='button'>Update</button></a>
            <a href='delete.php?id=" . $userId . "'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a></td>
        </tr>";
    }
} else {
    $tbody = "<tr><td colspan='5'><center>No Data Available</center></td></tr>";
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
    <link rel='stylesheet' type='text/css' href='../../main-style.css'>
</head>

<body>

    <?php
    require_once '../components/header.php';
    navbar("../../", "../");
    ?>

    <div id="container">
        <div id="content">
            <h1>Manage Users</h1>

            <a href="create.php"><button class='btn btn-primary' type="button">Add Product</button></a>

            <table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>Picture</th>
                        <th>Name</th>
                        <th>Date of birth</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $tbody ?>
                </tbody>
            </table>

            <a href='dashboard.php'>Back to dashboard</a>
        </div>
    </div>

    <?php
        require_once '../components/footer.php';

    ?>

    <?php require_once '../components/boot-javascript.php' ?>

</body>

</html>