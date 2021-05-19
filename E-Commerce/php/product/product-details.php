<?php
    $hostname = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "wf-backend-5-ecommerce";
    
    $connect = mysqli_connect($hostname, $username, $password, $dbname);
    
    // if($connect->connect_error) {
    //     die("Connection failed: " . $connect->connect_error);
    // }else {
    //     echo "Successfully Connected";
    // }

    // session_start();
    // if ( isset($_SESSION['user']) != "") {
    //     header("Location: home.php" ); //-----------------------------------
    // }
    // if (isset($_SESSION[ 'admin' ]) != "") {
    //     header("Location: dashboard.php"); //-----------------------------------
    // }
    
    if ($_GET['id']) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM product WHERE pk_product_id = {$id}";
        $result = $connect->query($sql);
        if ($result->num_rows == 1){
            $data = $result->fetch_assoc();
            $name = $data['name'];
            $description = $data['description'];
            $brand = $data['brand'];
            $image = $data['image'];
            $price = $data['price'];
            $category = $data['category'];
            $status = $data['status'];
            $discount_procent = $data['discount_procent'];
        };
    };
    
    //require_once 'db_connect.php';
    //Review
    if (isset($_POST['submitRev'])) { 
        if ($_POST['review']&&$_POST['rating']){
            $review = $_POST['review'];
            $rating = $_POST['rating'];
            $title = $_POST['title'];
            $date = date('d-m-y h:i:s');
            $product_id = $_GET['id'];
            $user_id = 1;//$_GET['user']; //---------------------------------------------------
            
            $sql = "INSERT INTO review (rating, title, comment, create_datetime, fk_product_id, fk_user_id) VALUES ($rating, '$title', '$review', '$date', $product_id, $user_id)";

            if ($connect->query($sql) === true ) {
                $class = "success";
                $messageReview = "The review was successfully created";
            } else {
                $class = "danger";
                $messageReview = "Error while creating review. Try again: <br>" . $connect->error;
            }
        } else {
            $class = "danger";
            $messageReview = "Fill in all fields and don't forget the Stars!";
        }
    }

    // Q&A
    if (isset($_POST['submitQ'])) {
        if($_POST['question']){
            $question = $_POST['question'];
            $date = date('d-m-y h:i:s');
            $product_id = $_GET['id'];
            $user_id = 1;//$_GET['user']; //---------------------------------------------------
        
            $sql = "INSERT INTO question (question, create_datetime, fk_product_id, fk_user_id) VALUES ('$question', '$date', $product_id, $user_id)"; 

            if ($connect->query($sql) === true ) {
                $class = "success";
                $messageQA = "The comment was successfully created";
            } else {
                $class = "danger";
                $messageQA = "Error while creating comment. Try again: <br>" . $connect->error;
            }
        } else {
            $class = "danger";
            $messageQA = "Fill in the fields!";
        }
    }

    // Print Reviews
    $id = $_GET['id'];
    $sql = "SELECT review.rating, review.title, review.comment, review.create_datetime, product.name, user.first_name FROM review INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_product_id = {$id}";
    $result = mysqli_query($connect ,$sql);
    $review='';
    if(mysqli_num_rows($result) > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $stars = "";
            switch($row['rating']){
                case 1:
                    $stars = "★";
                    break;
                case 2:
                    $stars = "★★";
                    break;
                case 3:
                    $stars = "★★★";
                    break;
                case 4:
                    $stars = "★★★★";
                    break;
                case 5:
                    $stars = "★★★★★";
                    break;
            }
            $review .= " 
                <div>
                    <p>$row[first_name] wrote a review on $row[name]</p>
                    <p>$stars</p>
                    <p>$row[title]</p>
                    <p>$row[create_datetime]</p>
                    <p>$row[comment]</p>
                </div>
            ";  
        };
    }

    // Print Q&A
    $sql = "SELECT question.question, question.create_datetime, product.name, user.first_name FROM question INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_product_id = {$id}";
    $result = mysqli_query($connect ,$sql);
    $question='';
    if(mysqli_num_rows($result) > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){        
            $question .= " 
                <div>
                    <p>$row[first_name] has a question about $row[name]</p>
                    <p>$row[create_datetime]</p>
                    <p>$row[question]</p>
                </div>
            ";
        };
    }

    $connect->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Name</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="../../style/details.css">
</head>
<body>
    <div class="container">
        <div id="product">
            <h2 id="name"><?php echo $name ?></h2>
            <img src="<?php echo $image ?>" alt="<?php echo $name ?>" width="300px">
            <ul id="list">
                <li><?php echo $brand ?></li>
                <li><?php echo $category ?></li>
                <li><?php echo $status ?></li>
                <li><?php echo $price ?>€</li>
            </ul>
            <p id="desc"><?php echo $description ?></p>
        </div>
        <hr>
        <div id="review">
            <h3>Reviews</h3>
            <div id="reviews"><?= $review;?></div>
            <div id="stars">
                <span id="st1">★</span><span id="st2">★</span><span id="st3">★</span><span id="st4">★</span><span id="st5">★</span>
            </div><br>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" autocomplete="off">
                <div class="mb-3">
                    <input class="form-control" type="text" name="title" placeholder="Leave a title here" id="reviewTitle" style="width: 80vw"></input><br>
                    <input class="form-control" type="text" name="review" placeholder="Leave a review here" id="reviewText" style="width: 80vw"></input>
                </div>
                <span class="text-<?=$class;?>"><?php echo ($messageReview) ?? ''; ?></span><br>
                <input id="rating" type="hidden" name="rating" value="" />
                <button type="submit" name="submitRev" class="btn btn-primary">Create Review</button>
            </form>
        </div>
        <hr>
        <div id="qAndA">
            <h3>Q&A</h3>
            <div id="questions"><?= $question;?></div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" autocomplete="off">
                <div class="mb-3">
                    <input class="form-control" type="text" name="question" placeholder="Leave a review here" id="questionText" style="width: 80vw"></input>
                </div>
                <span class="text-<?=$class;?>"><?php echo ($messageQA) ?? ''; ?></span><br>
                <button type="submit" name="submitQ" class="btn btn-primary">Create Question</button>
            </form>
        </div>
    </div>
    <script src="../../script/review.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>