<?php
    require_once '../components/db_connect.php';

    session_start();

    $userId = '';
    if(isset($_SESSION['admin'])){
        $userId = $_SESSION['admin'];
    } else if(isset($_SESSION['user'])){
        $userId = $_SESSION['user'];
    }
    
    if ($_GET['id']) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM product WHERE pk_product_id = {$id}";
        $result = $conn->query($sql);
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
        }
    }
    
    // Create Review
    if (isset($_POST['submitRev'])) { 
        if ($_POST['review']&&$_POST['rating']){
            $review = $_POST['review'];
            $rating = $_POST['rating'];
            $title = $_POST['title'];
            $date = date('d-m-y h:i:s');
            $product_id = $_GET['id'];
            
            $sql = "INSERT INTO review (rating, title, comment, create_datetime, fk_product_id, fk_user_id) VALUES ($rating, '$title', '$review', '$date', $product_id, $userId)";

            if ($conn->query($sql) === true ) {
                $class = "success";
                $messageReview = "The review was successfully created";
            } else {
                $class = "danger";
                $messageReview = "Error while creating review. Try again: <br>" . $conn->error;
            }
        } else {
            $class = "danger";
            $messageReview = "Fill in all fields and don't forget the Stars!";
        }
    }

    // Print Reviews
    $id = $_GET['id'];
    $sql = "SELECT review.rating, review.title, review.comment, review.create_datetime, product.name, user.first_name FROM review INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_product_id = {$id}";
    $result = mysqli_query($conn ,$sql);
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
                <div class='col-12 col-md-8'>
                    <div class='row align-items-center'>
                        <div class='col-12 col-md-8 my-2'><span class='my_text_maincolor'>$row[first_name]</span> wrote a review on <span class='my_text_maincolor'>$row[name]</span></div>
                        <div class='col-12 col-md-4 my_text_lightgray'>$row[create_datetime]</div>
                    </div>
                    <div class='fs-5'>$stars</div>
                    <div class='fs-5 fw-bold my-2'>$row[title]</div>
                    <div class='mt-3'>$row[comment]</div>
                </div>
            ";  
        }
    }

    // Average and Count Rating
    $sql = "SELECT AVG(rating), COUNT(rating) FROM review WHERE fk_product_id = {$id}";
    $result = mysqli_query($conn ,$sql);
    $avgRating = "";
    if(mysqli_num_rows($result) > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $stars = "";
            $average = round($row['AVG(rating)']);
            switch($average){
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
            $avgRating .= $stars." | ".$row['COUNT(rating)'];  
        }
    }

    // Create Question
    $class = "";
    $messageQA = "";
    if (isset($_POST['submitQ'])) {
        if($_POST['question']){
            $question = $_POST['question'];
            $date = date('d-m-y h:i:s');
            $product_id = $_GET['id'];
        
            $sql = "INSERT INTO question (question, create_datetime, fk_product_id, fk_user_id) VALUES ('$question', '$date', $product_id, $userId)"; 

            if ($conn->query($sql) === true ) {
                $class = "success";
                $messageQA = "The comment was successfully created";
            } else {
                $class = "danger";
                $messageQA = "Error while creating comment. Try again: <br>" . $conn->error;
            }
        } else {
            $class = "danger";
            $messageQA = "Fill in the field!";
        }
    }

    // Cretate Answer
    if (isset($_POST['submitA'])) {
        if($_POST['answer']){
            $answer = $_POST['answer'];
            $questionId = $_POST['questionId'];
            $date = date('d-m-y h:i:s');
        
            $sql = "INSERT INTO answer (answer, create_datetime, fk_question_id, fk_user_id) VALUES ('$answer', '$date', $questionId, $userId)"; 

            if ($conn->query($sql) === true ) {
                $class = "success";
                $messageA = "The answer was successfully created";
            } else {
                $class = "danger";
                $messageA = "Error while creating comment. Try again: <br>" . $conn->error;
            }
        } else {
            $class = "danger";
            $messageA = "Fill in the field!";
        }
    }    

    // Print Question
    $messageA = "";
    $sql = "SELECT question.pk_question_id, question.question, question.create_datetime, product.name, user.first_name FROM question INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_product_id = {$id} ORDER BY create_datetime DESC";
    $result = mysqli_query($conn ,$sql);
    $question = "";
    $answer = "";
    if(mysqli_num_rows($result) > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $sqlA = "SELECT answer.answer, answer.fk_question_id, answer.create_datetime, user.first_name FROM answer INNER JOIN user ON answer.fk_user_id = pk_user_id WHERE answer.fk_question_id = $row[pk_question_id] ORDER BY create_datetime DESC";
            $resultA = mysqli_query($conn ,$sqlA);
            $aId = "";
            while($rowA = mysqli_fetch_array($resultA, MYSQLI_ASSOC)){
                $aId = "$rowA[fk_question_id]";
                $answer .= " 
                        <p>Answer from $rowA[first_name]</p>
                        <p>$rowA[create_datetime]</p>
                        <p>$rowA[answer]</p>
                        <hr>
                    ";
            }
            if($aId == $row['pk_question_id']){
                $question .= " 
                    <div>
                        <p>$row[first_name] has a question about $row[name]</p>
                        <p>$row[create_datetime]</p>
                        <p>$row[question]</p>
                        <button type='button' class='answerBtn btn btn-warning'>Answer</button>
                        <div class='answerForm'>
                            <form method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$_GET['id']."' autocomplete='off'>
                                <div class='mb-3'>
                                    <input class='form-control' type='text' name='answer' placeholder='Leave a answer here' id='answerText' style='width: 80vw'></input>
                                    <input type='hidden' name='questionId' value='$row[pk_question_id]' />
                                </div>
                                <span class='text-".$class."'>".$messageA."</span><br>
                                <button type='submit' name='submitA' class='createAnswerBtn btn btn-primary'>Create Answer</button>
                            </form>
                        </div>
                        <div class='answer'>$answer</div>
                    </div>
                ";
                $answer = "";
            } else {
                $question .= " 
                    <div>
                        <p>$row[first_name] has a question about $row[name]</p>
                        <p>$row[create_datetime]</p>
                        <p>$row[question]</p>
                        <button type='button' class='answerBtn btn btn-warning'>Answer</button>
                        <div class='answerForm'>
                            <form method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$_GET['id']."' autocomplete='off'>
                                <div class='mb-3'>
                                    <input class='form-control' type='text' name='answer' placeholder='Leave a answer here' id='answerText' style='width: 80vw'></input>
                                    <input type='hidden' name='questionId' value='$row[pk_question_id]' />
                                </div>
                                <span class='text-".$class."'>".$messageA."</span><br>
                                <button type='submit' name='submitA' class='createAnswerBtn btn btn-primary'>Create Answer</button>
                            </form>
                        </div>
                    </div>
                ";
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
    <title><?php echo $name ?></title>
    <?php require_once '../../php/components/boot.php'?>
    <link rel="stylesheet" href="../../style/main-style.css" />
    <link rel="stylesheet" href="../../style/details.css">
</head>
<body>
    <?php 
        require_once '../../php/components/header.php';
        navbar("../../", "../");
    ?>
    <div class="container">
        <div id="product" class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">About product</div>

            <div class="row my-4">
                <div class="col-12 col-md-6">
                    <img id="proImg" src="../../img/product_images/<?php echo $image ?>" alt="<?php echo $image ?>">
                </div>

                <div class="col-12 col-md-6">
                    <div class="row align-items-center">
                        <div id="name" class="col-12 col-md-10 py-3 py-md-0 fs-4"><?php echo $name ?></div>
                        <div class="col-12 col-md-2 my_text_maincolor fw-bold"><?php echo $status ?></div>
                    </div>
                    <div class="col-12 my-3 fs-5"><?php echo $avgRating ?></div>
                    <div class="col-12 fs_6 my_text_maincolor fw-bold"><?php echo $price ?>€</div>
                    <div class="col-12 my_text_lightgray fs_7 mb-4">Price without shipping</div>
                    <div class="col-12"><span class="my_text_lightgray">from </span><a href=""><?php echo $brand ?></a></div>
                    <div class="col-12 my-2"><a class="my_text_maincolor" href=""><?php echo $category ?></a></div>
                    <div class="col-12 my_text_lightgray fs-5">Description:</div>
                    <div id="desc" class="col-12"><?php echo $description ?></div>

                    <?php
                        if(isset($_SESSION['admin']) || isset($_SESSION['user'])){ 
                    ?>
                        <button id="addToCartBtn" type="button" class="btn btn bg_gray bg_hover rounded-pill col-12 py-2 px-4 text-white my-5">Add to Cart</button>
                    <?php
                        }
                    ?>

                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <hr>
        <div id="review" class="py-4">
            <div class="col-12 fs_6 text-uppercase my-2 text-center">Reviews</div>
            <div id="reviews" class="row text-center justify-content-center"><?= $review;?></div>

            <?php
                if(isset($_SESSION['admin']) || isset($_SESSION['user'])){ 
            ?>
            <div class="row my-5 py-3 text-center justify-content-center">
                <div id="stars" class="text-center mb-4 my_text_lightgray">
                    <span id="st1">★</span><span id="st2">★</span><span id="st3">★</span><span id="st4">★</span><span id="st5">★</span>
                </div>
                <form class="col-12 col-md-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" autocomplete="off">
                    <div class="my-2">
                        <input class="form-control mb-2" type="text" name="title" placeholder="Leave a title here" id="reviewTitle">
                        <textarea class="form-control mb-2" type="text" name="review" placeholder="Leave a review here" id="reviewText"></textarea>
                    </div>
                    <div class="my-2 text-<?=$class;?>"><?php echo ($messageReview) ?? ""; ?></div>
                    <input id="rating" type="hidden" name="rating" value="" />
                    <button type="submit" name="submitRev" class="btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-2">Create Review</button>
                </form>
            </div>
            <?php
                }
            ?>

        </div>
    </div>

    <div class="container">
        <hr>
        <div id="qAndA">
            <h3>Q&A</h3>
            <div id="questions"><?= $question;?></div>
            <?php
                if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
            ?>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" autocomplete="off">
                    <div class="mb-3">
                        <textarea class="form-control" type="text" name="question" placeholder="Leave a question here" id="questionText" style="width: 80vw"></textarea>
                    </div>
                    <span class="text-<?=$class;?>"><?php echo ($messageQA) ?? ''; ?></span><br>
                    <button type="submit" name="submitQ" class="btn btn-primary">Create Question</button>
                </form>
            <?php
                }
            ?>
        </div>
    </div>

    <?php 
        require_once '../../php/components/footer.php';
        footer("../../");
        require_once '../../php/components/boot-javascript.php';
    ?>
    <script src="../../script/review.js"></script>
</body>
</html>