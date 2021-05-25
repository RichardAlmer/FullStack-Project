<?php
session_start();
if (isset($_SESSION['user']) != "") {
    header("Location: product/product-catalog.php");
    exit;
}
if (isset($_SESSION['admin']) != "") {
    header("Location: admin/dashboard.php");
}

require_once 'components/db_connect.php';
require_once 'components/banChecker.php';

$error = false;
$email = $password = $emailError = $passError = '';

if (isset($_POST['btn-login'])) {

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);
    $pass = trim($_POST['pass']);
    $pass = strip_tags($pass);
    $pass = htmlspecialchars($pass);

    if (empty($email)) {
        $error = true;
        $emailError = "Please enter your email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    }

    if (empty($pass)) {
        $error = true;
        $passError = "Please enter your password.";
    }

    if (!$error) {
        $inputPW = hash('sha256', $pass);
        $sqlSelect = "SELECT pk_user_id, first_name, password, status, role, banned_until FROM user WHERE email = ? ";
        $stmt = $conn->prepare($sqlSelect);
        $stmt->bind_param("s", $email);
        $work = $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        $count = $result->num_rows;
        //Email check if account exists if not to else
        if ($count != 0) {

            // var_dump($count);

            $userId = $row['pk_user_id'];
            $password = $row['password'];
            $role = $row['role'];
            $status = $row['status'];
            $bannedUntil = $row['banned_until'];
            $banDate = date('d-m-y H:i:s', strtotime($bannedUntil));

            if ($count == 1 && $inputPW == $password) {
                if ($status == 'active' && ($role == 'admin' || $role == 'superadmin')) {
                    $_SESSION['admin'] = $userId;
                    header("Location: admin/dashboard.php");
                } else if ($status == 'active' && $role == 'user' && banChecker($conn, $userId)) {
                    $_SESSION['user'] = $userId;
                    header("Location: product/product-catalog.php");
                } else {
                    if ($bannedUntil != NULL) {
                        $errMSG = "You are banned until " . $banDate;
                    } else {
                        $errMSG = "Your account is deactivated, please contact our support.";
                    }
                }
            } else {
                $errMSG = "Incorrect Credentials, Try again...";
            }
        } else {
            $errMSG = "Incorrect Credentials, Try again...";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require_once 'components/boot.php' ?>
    <link rel="stylesheet" href="../style/main-style.css" />
    <link rel="stylesheet" href="../style/login.css">
</head>

<body>
    <?php
    require_once 'components/header.php';
    $id = "";
        $session = "";
        if(isset($_SESSION['admin'])){
            $id = $_SESSION['admin'];
            $session = "admin";
        } else if(isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
            $session = "user";
        }
        navbar("../", "", "", $id, $session);
    ?>

    <div class="container py-5">
        <div class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2 text-center">Login</div>

            <div class="col-12 text-center text-danger">
                <?php
                if (isset($errMSG)) {
                    echo $errMSG;
                }
                ?>
            </div>
            <div class="justify-content-center d-flex">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off" class="col-12 col-md-6 col-lg-4 flex-column">
                    <div class="col-12 my-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="your.email@mail.com">
                        <span class="text-danger"><?php echo $emailError; ?></span>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="exampleInputPassword1" class="form-label">Password</label>
                        <input type="password" class="form-control" name="pass" id="exampleInputPassword1" placeholder="your password">
                        <span class="text-danger"><?php echo $passError; ?></span>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Remember Login</label>
                    </div>
                    <button type="submit" name="btn-login" class="btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-3">Sign in</button>
                </form>
            </div>
        </div>
    </div>

    <?php
    require_once 'components/footer.php';
    footer("../");
    ?>

    <?php require_once 'components/boot-javascript.php' ?>

</body>

</html>