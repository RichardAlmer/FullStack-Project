<?php
    require_once '../components/db_connect.php';
    require_once 'actions/helper-functions.php';
    
    session_start();

    $userId = '';
    if(isset($_SESSION['admin'])){
        $userId = $_SESSION['admin'];
    } else if(isset($_SESSION['user'])){
        $userId = $_SESSION['user'];
    }
    
    $id = $_GET['id'];

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
    $userPurchase = array();
    $productPurchase = array();
    $bought = false;
    $sqlR = "SELECT DISTINCT fk_product_id, fk_user_id FROM purchase_item INNER JOIN purchase ON fk_purchase_id = pk_purchase_id WHERE fk_product_id = {$id}";
    $resultR = mysqli_query($conn ,$sqlR);
    if(mysqli_num_rows($resultR) > 0) {
        while($rowR = mysqli_fetch_array($resultR, MYSQLI_ASSOC)){
            array_push($productPurchase, $rowR['fk_product_id']);
            array_push($userPurchase, $rowR['fk_user_id']);
        }
    }
    for($i = 0; $i < count($userPurchase); $i++){
        if($userPurchase[$i] == $userId && $productPurchase[$i] == $id){
            $bought = true;
            break;
        }
    }

    $userReview = array();
    $productReview = array();
    $wroteReview = false;
    $sqlU = "SELECT DISTINCT fk_product_id, fk_user_id FROM review WHERE fk_product_id = {$id}";
    $resultU = mysqli_query($conn ,$sqlU);
    if(mysqli_num_rows($resultU) > 0) {
        while($rowU = mysqli_fetch_array($resultU, MYSQLI_ASSOC)){
            array_push($productReview, $rowU['fk_product_id']);
            array_push($userReview, $rowU['fk_user_id']);
        }
    }
    for($i = 0; $i < count($userReview); $i++){
        if($userReview[$i] == $userId && $productReview[$i] == $id){
            $wroteReview = true;
            break;
        }
    }
    
    if (isset($_POST['submitRev'])) {
        if($bought && !$wroteReview){
            if ($_POST['review'] && $_POST['rating']){
                $review = $_POST['review'];
                $rating = $_POST['rating'];
                $title = $_POST['title'];
                $date = date('y-m-d h:i:s');
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
                $messageReview = "Fill in all fields and don't forget the stars!";
            } 
        } else {
            $class = "danger";
            $messageReview = "You have to buy the product to leave a review or You have already written a review for this product!";
        } 
    } 

    // Print Reviews
    $id = $_GET['id'];
    $sql = "SELECT review.rating, review.title, review.comment, DATE_FORMAT(review.create_datetime, '%d.%m.%Y %H:%i') AS create_datetime, product.name, user.first_name FROM review INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_product_id = {$id}";
    $result = mysqli_query($conn ,$sql);
    $review='';
    if(mysqli_num_rows($result) > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $stars = getStars($row['rating']);
            $review .= " 
                <div class='col-12 col-md-8 my-4'>
                    <div class='row align-items-center'>
                        <div class='col-12 col-md-8 my-2'><span class='my_text_maincolor'>$row[first_name]</span> wrote a review on <span class='my_text_maincolor'>$row[name]</span></div>
                        <div class='col-12 col-md-4 my_text_lightgray text-md-end'>$row[create_datetime]</div>
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
            
            $average = round($row['AVG(rating)']);
            $stars = getStars($average);
            if ($row['COUNT(rating)'] > 0) {
                $avgRating .= $stars." | ".$row['COUNT(rating)'];  
            } else {
                $avgRating .= $stars;  
            }
        }
    }

    // Create Question
    $class = "";
    $messageQA = "";
    if (isset($_POST['submitQ'])) {
        if($_POST['question']){
            $question = $_POST['question'];
            $date = date('y-m-d h:i:s');
            $product_id = $_GET['id'];
        
            $sql = "INSERT INTO question (question, create_datetime, fk_product_id, fk_user_id) VALUES ('$question', '$date', $product_id, $userId)"; 

            if ($conn->query($sql) === true ) {
                $class = "success";
                $messageQA = "The question was successfully added.";
            } else {
                $class = "danger";
                $messageQA = "Error while posting question. Try again: <br>" . $conn->error;
            }
        } else {
            $class = "danger";
            $messageQA = "Fill in your question in the field above.";
        }
    }

    // Create Answer
    if (isset($_POST['submitA'])) {
        if($_POST['answer']){
            $answer = $_POST['answer'];
            $questionId = $_POST['questionId'];
            $date = date('y-m-d h:i:s');
        
            $sql = "INSERT INTO answer (answer, create_datetime, fk_question_id, fk_user_id) VALUES ('$answer', '$date', $questionId, $userId)"; 

            if ($conn->query($sql) === true ) {
                $class = "success";
                $messageA = "The answer was successfully added.";
            } else {
                $class = "danger";
                $messageA = "Error while posting answer. Try again: <br>" . $conn->error;
            }
        } else {
            $class = "danger";
            $messageA = "Enter an answer in the field above.";
        }
    }    

    // Print Question
    $messageA = "";
    $sql = "SELECT question.pk_question_id, question.question, DATE_FORMAT(question.create_datetime, '%d.%m.%Y %H:%i') AS create_datetime, product.name, user.first_name FROM question INNER JOIN product ON fk_product_id = pk_product_id INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_product_id = {$id} ORDER BY create_datetime DESC";
    $result = mysqli_query($conn ,$sql);
    $question = "";
    $answer = "";
    if(mysqli_num_rows($result) > 0) {    
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            $sqlA = "SELECT answer.answer, answer.fk_question_id, DATE_FORMAT(answer.create_datetime, '%d.%m.%Y %H:%i') AS create_datetime, user.first_name FROM answer INNER JOIN user ON answer.fk_user_id = pk_user_id WHERE answer.fk_question_id = $row[pk_question_id] ORDER BY create_datetime DESC";
            $resultA = mysqli_query($conn ,$sqlA);
            $aId = "";
            while($rowA = mysqli_fetch_array($resultA, MYSQLI_ASSOC)){
                $aId = "$rowA[fk_question_id]";
                $answer .= " 
                        <div class='row align-items-center ps-md-5'>
                            <div class='col-12 col-md-8 my-2'>Answer from <span class='my_text_maincolor'>$rowA[first_name]</span></div>
                            <div class='col-12 col-md-4 my_text_lightgray text-md-end'>$rowA[create_datetime]</div>
                        </div>
                        <div class='mb-3 ps-md-5'>$rowA[answer]</div>
                    ";
            }
            if($aId == $row['pk_question_id']){
                $question .= " 
                    <div class='col-12 col-md-8 my-4'>
                        <div class='row align-items-center'>
                            <div class='col-12 col-md-8 my-2'><span class='my_text_maincolor'>$row[first_name]</span> has a question about <span class='my_text_maincolor'>$row[name]</span></div>
                            <div class='col-12 col-md-4 my_text_lightgray text-md-end'>$row[create_datetime]</div>
                        </div>
                        <div class='my-3'>$row[question]</div>
                        <button type='button' class='answerBtn btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-md-3 mb-2'>Answer</button>
                        <div class='answerForm'>
                            <form class='my-3' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$_GET['id']."' autocomplete='off'>
                                <div class='mb-3'>
                                    <input class='form-control' type='text' name='answer' placeholder='Leave an answer here' id='answerText'></input>
                                    <input type='hidden' name='questionId' value='$row[pk_question_id]' />
                                </div>
                                <div class='text-".$class."'>".$messageA."</div>
                                <button type='submit' name='submitA' class='createAnswerBtn btn btn bg_lightgray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white'>Create Answer</button>
                            </form>
                        </div>
                        <div class='answer col-12 justify-content-end'>$answer</div>
                    </div>
                    <hr><br/><br/>
                ";
                $answer = "";
            } else {
                $question .= " 
                    <div class='col-12 col-md-8 my-4'>
                        <div class='row align-items-center'>
                            <div class='col-12 col-md-8 my-2'><span class='my_text_maincolor'>$row[first_name]</span> has a question about <span class='my_text_maincolor'>$row[name]</span></div>
                            <div class='col-12 col-md-4 my_text_lightgray text-md-end'>$row[create_datetime]</div>
                        </div>
                        <div class='my-3'>$row[question]</div>
                        <button type='button' class='answerBtn btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-md-3 mb-2'>Answer</button>

                        <div class='answerForm'>
                            <form class='my-3' method='post' action='".htmlspecialchars($_SERVER['PHP_SELF'])."?id=".$_GET['id']."' autocomplete='off'>
                                <div class='mb-3'>
                                    <input class='form-control' type='text' name='answer' placeholder='Leave an answer here' id='answerText'></input>
                                    <input type='hidden' name='questionId' value='$row[pk_question_id]' />
                                </div>
                                <div class='text-".$class."'>".$messageA."</div>
                                <button type='submit' name='submitA' class='createAnswerBtn btn btn bg_lightgray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white'>Create Answer</button>
                            </form>
                        </div>
                    </div>
                    <hr><br/><br/>
                ";
            }
        }
    }

    // add to cart
    $productId = "";
    if (isset($_POST['cartBtn'])){
        $productId = $_POST['productId'];
        $sql = "INSERT INTO cart_item (quantity, fk_product_id, fk_user_id) VALUES (1, $productId, $userId)";
        if ($conn->query($sql) === true ) {
            $class = "success";
            $messageC = "The product was successfully added to cart.";
        } else {
            $class = "danger";
            $messageC = "Error while adding to cart. Try again: <br>" . $conn->error;
        }
    }

    $cartCount = "";
    $imageP = "";
    if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
        $sqlCart = "SELECT COUNT(quantity), profile_image FROM cart_item INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_user_id = {$userId}";
        $result = $conn->query($sqlCart);
            if ($result->num_rows == 1){
                $data = $result->fetch_assoc();
                $imageP = $data['profile_image'];
                if($data['COUNT(quantity)'] != 0){
                    $cartCount = $data['COUNT(quantity)'];
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
        $id = "";
        $session = "";
        if(isset($_SESSION['admin'])){
            $id = $_SESSION['admin'];
            $session = "admin";
        } else if(isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
            $session = "user";
        }
        navbar("../../", "../", "../", $id, $session, $cartCount, $imageP);
    ?>
    <div class="container">
        <div id="product" class="my-5 py-5">
            <div class="col-12 fs_6 text-uppercase my-2">About product </div>
            <div class="my-2 text-<?=$class;?> my_text_maincolor"><?php echo ($messageC) ?? ""; ?></div>
            <div class="row my-4">
                <div class="col-12 col-lg-6">
                    <img id="proImg" src="../../img/product_images/<?php echo $image ?>" alt="<?php echo $image ?>">
                </div>

                <div class="col-12 col-lg-6">
                    <div class="row align-items-center">
                        <div id="name" class="col-12 col-md-10 py-3 py-lg-0 fs-4"><?php echo $name ?></div>
                        <div class="col-12 col-md-2 my_text_maincolor fw-bold"><?php echo ($status === 'deactive') ? "deactive" : "" ?></div>
                    </div>
                    <div class="col-12 my-3 fs-5"><?php echo $avgRating ?></div>
                    <div class="col-12 fs_6 fw-bold">
                        <span class="my_text_maincolor"><?php echo discountedPrice($price, $discount_procent); ?>€</span>    
                        <span class="my_text_lightgray text-decoration-line-through"> <?php echo ($discount_procent == 0) ? "" : " (".number_format($price, 2, ',', ' ')."€)"; ?></span>    
                    </div>
                    <div class="col-12 my_text_lightgray fs_7 mb-4">Price without shipping</div>
                    <div class="col-12"><span class="my_text_lightgray">from </span><a href=""><?php echo $brand ?></a></div>
                    <div class="col-12 my-2"><a class="my_text_maincolor" href=""><?php echo $category ?></a></div>
                    <div class="col-12 my_text_lightgray fs-5">Description:</div>
                    <div id="desc" class="col-12"><?php echo $description ?></div>

                    <?php
                        if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
                            if($status == 'active'){
                    ?>
                    <form class="col-12 col-md-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" autocomplete="off">
                        <input type="hidden" name="productId" value="<?php echo $_GET['id'] ?>" />
                        <button id="addToCartBtn" type="submit" name="cartBtn" class="btn btn bg_gray bg_hover rounded-pill col-md-auto py-2 px-4 text-white my-5">Add to cart</button>
                                                                                                                            
                    </form>
                    <?php
                        }}
                    ?>

                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <hr>
        <div id="review" class="py-4">
            <div class="col-12 fs_6 text-uppercase my-2 text-center">Reviews</div>
            <div id="reviews" class="row justify-content-center"><?= $review;?></div>

            <?php
                if(isset($_SESSION['admin']) || isset($_SESSION['user'])){ 
            ?>
            <div class="row my-5 py-3 text-center justify-content-center">
                <div id="stars" class="text-center mb-4 my_text_lightgray fs-5">
                    <span id="st1">★</span><span id="st2">★</span><span id="st3">★</span><span id="st4">★</span><span id="st5">★</span>
                </div>
                <form class="col-12 col-md-8" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" autocomplete="off">
                    <div class="my-2">
                        <input class="form-control mb-2" type="text" name="title" placeholder="Leave a title here" id="reviewTitle">
                        <textarea class="form-control mb-2" type="text" name="review" placeholder="Leave a review here" id="reviewText"></textarea>
                    </div>
                    <div class="text-<?=$class;?> my-2 my_text_maincolor"><?php echo ($messageReview) ?? ""; ?></div>
                    <input id="rating" type="hidden" name="rating" value="" />
                    <button type="submit" name="submitRev" class="btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-2">Create review</button>
                </form>
            </div>
            <?php
                }
            ?>

        </div>
    </div>
    
    <div class="container">
        <hr>
        <div id="qAndA" class="row my-5 py-3 justify-content-center">
            <div class="col-12 fs_6 text-uppercase my-2 text-center">Q&A</div>
            <div id="questions" class="row justify-content-center"><?= $question;?></div>
            <?php
                if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
            ?>
            <form class="col-12 col-md-8 text-center" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>" autocomplete="off">
                <div class="my-2">
                    <textarea class="form-control" type="text" name="question" placeholder="Leave a question here" id="questionText"></textarea>
                </div>
                <span class="text-<?=$class;?>"><?php echo ($messageQA) ?? ''; ?></span><br>
                <button type="submit" name="submitQ" class="btn btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-4 text-white my-2">Create question</button>
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