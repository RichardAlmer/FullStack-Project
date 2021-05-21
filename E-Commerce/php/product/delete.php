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
    <style type= "text/css">
        fieldset {
            margin: auto;
            margin-top: 100px;
            width: 70% ;
        }    
        .img-thumbnail{
            width: 70px !important;
                height: 70px !important;
        }    
    </style>
</head>
<body>
    <div id="container">
        <?php require_once '../components/header.php'; 
        navbar("../../", "../");?>
        <fieldset>
            <legend class='h2 mb-3'> Delete request <img class='img-thumbnail rounded-circle' src='pictures/<?php echo $image ?>' alt="<?php echo $name ?>"></legend>
            <?php if($message !== ""){ ?>
                <div class="container">
                    <div class="alert alert-<?=$class;?>" role="alert">
                        <p><?=$message;?></p >
                        <a href ='products.php'><button class= "btn btn-success" type='button'>Back</button></a>
                    </div>
                </div >
            <?php } ?>
            <?php if($message === ""){ ?>
                <h5>You have selected the data below: </h5>
                <table class="table w-75 mt-3">
                    <tr>
                        <td><?php echo $name?></td>
                    </tr>
                </table>
                <h3 class="mb-4">Do you really want to delete this product?</h3>
                <form action ="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>"  method="post">
                    <input type="hidden" name="id" value ="<?php echo $id ?>"/>
                    <button class="btn btn-danger" type="submit" name="submitP"> Yes, delete it! </button>
                    <a href="products.php?id=<?php echo $id ?>"><button class="btn btn-warning" type="button">No, go back!</button></a>
                </form>
            <?php } ?>
        </fieldset>
    </div>
</body>
</html>