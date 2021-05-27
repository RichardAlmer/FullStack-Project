<?php
    require_once '../components/db_connect.php';

    session_start();
    if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
        header("Location: ../../index.php");
        exit;
    }
    if (isset($_SESSION["user"])) {
        header("Location: ../product/product-catalog.php");
        exit;
    }

    $id = $_GET['id'];
    $sql ="SELECT pk_review_id, title, fk_product_id FROM review WHERE pk_review_id = {$id}";
    $result = mysqli_query($conn ,$sql);
    $data = $result->fetch_assoc();
    if ($result->num_rows == 1) {
        $name = $data['title'];
        $id = $data['pk_review_id'];
        $productId = $data['fk_product_id'];
    } else {
        header("location: ../error.php");
    }

    $class = "";
    $message = "";
    if(isset($_POST['submitR'])){
        $sql = "DELETE FROM review WHERE pk_review_id = {$id}";
        if ($conn->query($sql) === TRUE) {
            $class = "success";
            $message = "Successfully Deleted!";
        } else {
            $class = "danger";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Review</title>
    <?php require_once '../../php/components/boot.php'?>
    <link rel="stylesheet" href="../../style/main-style.css" />
</head>
<body>
    <div id="container" class="container">
        <?php require_once '../components/header.php'; 
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
        
        <div class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">Delete request</div>

            <?php
                if($message !== ""){
            ?>
            <div class="alert alert-<?=$class;?>" role="alert">
                <p><?=$message;?></p >
                <a href ='reviews.php?id=<?php echo $productId ?>'><button class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type='button'>Back</button></a>
            </div>
            <?php } ?>
            <?php
                if($message === ""){
            ?>

            <div class="col-12 fs-5 my-3">You have selected the data below:</div>
            <table class="table my-3 col-12" >
                <tr>
                    <td><?php echo $name?></td>
                </tr>
            </table>
            <div class="fs-5 my-4">Do you really want to delete this review?</div>
            <form action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>"  method="post">
                <input type="hidden" name="id" value ="<?php echo $id ?>"/>
                <a href="reviews.php?id=<?php echo $productId ?>"><button class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="button">No, go back!</button></a>
                <button class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="submit" name="submitR">Yes, delete it!</button>
            </form>
        </div>
        <?php } ?>
    </div>

    <?php 
        require_once '../components/footer.php';
        footer("../../");
        require_once '../components/boot-javascript.php';
    ?>
</body>
</html>