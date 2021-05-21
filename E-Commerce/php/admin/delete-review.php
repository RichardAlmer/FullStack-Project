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
    
    $conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Review</title>
    <?php require_once '../components/boot.php'?>
</head>
<body>
    <div id="container">
        <?php require_once '../components/header.php'; 
        navbar("../../", "../");?>
        <?php
            if($message !== ""){
        ?>
        <div class="alert alert-<?=$class;?>" role="alert">
            <p><?=$message;?></p >
            <a href ='reviews.php?id=<?php echo $productId ?>'><button class= "btn btn-success" type='button'>Back</button></a>
        </div>
        <?php } ?>
        <?php
            if($message === ""){
        ?>
        <fieldset>
            <legend class='h2 mb-3'>Delete request</legend>
            <h5>You have selected the review below:</h5>
            <table class="table w-75 mt-3" >
                <tr>
                    <td><?php echo $name?></td>
                </tr>
            </table>
            <h3 class="mb-4">Do you really want to delete this review?</h3>
            <form action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>"  method="post">
                <input type="hidden" name="id" value ="<?php echo $id ?>"/>
                <button class="btn btn-danger" type="submit" name="submitR">Yes, delete it!</button>
                <a href="reviews.php?id=<?php echo $productId ?>"><button class="btn btn-warning" type="button">No, go back!</button></a>
            </form>
        </fieldset>
        <?php } ?>
    </div>
</body>
</html>