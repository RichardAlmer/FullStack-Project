<?php
    session_start();
    require_once 'components/db_connect.php'; //-----------------------------------

    if (isset($_SESSION['user']) != "") {
        header("Location: home.php"); //-----------------------------------
        exit;
    }
    if (isset($_SESSION['adm']) != "") {
        header("Location: dashboard.php"); //-----------------------------------
    }

    $error = false ;
    $email = $password = $emailError = $passError = '';

    if (isset ($_POST['btn-login'])) {
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
            $password = hash('sha256', $pass);
            $sqlSelect = "SELECT id, first_name, password, status FROM user WHERE email = ? "; //-----------------------------------
            $stmt = $connect->prepare($sqlSelect);
            $stmt->bind_param("s", $email);
            $work = $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $result->num_rows;
            if ($count == 1 && $row['password'] == $password) {
                if($row['status'] == 'adm'){
                $_SESSION['adm'] = $row['id'];          
                header( "Location: dashboard.php");} //-----------------------------------
                else{
                    $_SESSION['user'] = $row['id'];
                    header( "Location: home.php"); //-----------------------------------
                }          
            } else {
                $errMSG = "Incorrect Credentials, Try again..." ;
            }
        }
    }

    $connect->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/login.css">
</head>
<body>
    <div class="container">
        <form>
            <?php
                if (isset($errMSG)) {
                    echo $errMSG;
                }
            ?>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1">
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Remember Login</label>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>