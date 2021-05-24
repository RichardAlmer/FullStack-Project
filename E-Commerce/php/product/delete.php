<?php
    require_once '../components/db_connect.php';

    session_start();
    if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
        header("Location: ../../index.php");
        exit;
    }
    if (isset($_SESSION["user"])) {
        header("Location: product-catalog.php");
        exit;
    }

    $id = $_GET['id'];
    $sql = "SELECT * FROM product WHERE pk_product_id = {$id}";
    $result = mysqli_query($conn ,$sql);
    $data = $result->fetch_assoc();
    if ($result->num_rows == 1) {
        $name = $data['name'];
        $price = $data['price'];
        $brand = $data['brand'];
        $category = $data['category'];
        $image = $data['image'];
    } else {
        header("location: ../error.php");
    }
    
    $class = "";
    $message = "";
    if (isset($_POST['submitP'])) {
        $id = $_POST[ 'id'];
        $sql = "DELETE FROM product WHERE pk_product_id = {$id}";
        if ($conn->query($sql) === TRUE) {
            $class = "success";
            $message = "Successfully Deleted!";
        } else {
            $class = "danger";
            $message = "The entry was not deleted due to: <br>" . $conn->error;
        }
        $conn->close();
    } else {
        //header("location: ../error.php");
    }
?>
     
     
<!DOCTYPE html>
<html lang= "en">
    <head>
        <meta  charset="UTF-8">
        <meta name="viewport"  content="width=device-width, initial-scale=1.0">
        <title>Delete Product</title>
        <?php require_once '../components/boot.php' ?>
        <link rel='stylesheet' type='text/css' href='../../style/main-style.css'>
        <style type= "text/css">
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
            navbar("../../", "../", "../", $id, $session);
        ?>

        <div id="container" class="container">
            <div class="my-5 py-5">
                <div class="col-12 fs_6 text-uppercase my-2">Delete request</div>
                <div class='col-12'> 
                    <img class='img-thumbnail rounded-circle' src='../../img/product_images/<?php echo $image ?>' alt="<?php echo $name ?>">
                </div>

                <?php if($message !== ""){ ?>
                    <div class="container">
                        <div class="alert alert-<?=$class;?>" role="alert">
                            <p><?=$message;?></p >
                            <a href ='products.php'><button class= "btn btn-success" type='button'>Back</button></a>
                        </div>
                    </div >
                <?php } ?>

                <?php if($message === ""){ ?>
                    <table class="table">
                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Name</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2"><?php echo $name?></div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Brand</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2"><?php echo $brand?></div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Price</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2"><?php echo $price?></div>
                        </div>

                        <div class="row py-2 align-items-center">
                            <div class="col-12 col-md-3 fw-bold py-2">Category</div>
                            <div class="col-12 col-md-9 pb-3 py-md-2"><?php echo $category?></div>
                        </div>
                    </table>

                    <div class="fs-5 mb-4">Do you really want to <span class="my_text_maincolor">delete this product?</span></div>
                    <form action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>"  method="post">
                        <input type="hidden" name="id" value ="<?php echo $id ?>"/>
                        <a href="products.php?id=<?php echo $id ?>"><button class="col-12 col-md-auto btn bg_lightgray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="button">No, go back!</button></a>
                        <button class="col-12 col-md-auto btn bg_gray bg_hover rounded-pill py-2 px-md-5 text-white my-1" type="submit" name="submitP"> Yes, delete it! </button>
                    </form>
                <?php } ?>
            </div>
        </div>

        <?php 
            require_once '../../php/components/footer.php';
            footer("../../");
            require_once '../../php/components/boot-javascript.php';
        ?>
    </body>
</html>