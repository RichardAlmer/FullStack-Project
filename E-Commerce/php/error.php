<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <?php require_once 'components/boot.php'?>
    <link rel="stylesheet" href="../style/main-style.css" />
</head>

<body>
    <?php 
        require_once 'components/db_connect.php';
        session_start();
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
        navbar("../", "", "", $id, $session, $cartCount);
    ?>
    <div class="container">
        <div class="row my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Invalid Request</div>
        </div>
        <div class="col-12 bg_maincolor p-5 my-4 fs-5" role="alert">
            <div>You've made an invalid request. Please <a href="../index.php" class="alert-link">go back</a> to index and try again.</div>
        </div>
    </div>

    <?php 
        require_once 'components/footer.php';
        footer("../");
        require_once 'components/boot-javascript.php';
    ?>
</body>

</html>