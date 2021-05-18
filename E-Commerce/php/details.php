<?php
    // session_start();
    // if ( isset($_SESSION['user']) != "") {
    //     header("Location: home.php" ); //-----------------------------------
    // }
    // if (isset($_SESSION[ 'admin' ]) != "") {
    //     header("Location: dashboard.php"); //-----------------------------------
    // }

    // //require_once 'db_connect.php';
    // // Review
    // if ($_POST) {  
    //     $review = $_POST['review'];
    
    //     $sql = ""; //-----------------------------------

    //     if ($connect->query($sql) === true ) {
    //         $class = "success";
    //         $messageReview = "The review was successfully created";
    //     } else {
    //         $class = "danger";
    //         $messageReview = "Error while creating eview. Try again: <br>" . $connect->error;
    //     }
    //     $connect->close();
    // } else {
    //     //header("location: ../error.php"); //-----------------------------------
    // }
    // // Q&A
    // if ($_POST) {  
    //     $question = $_POST['question'];
    
    //     $sql = ""; //-----------------------------------

    //     if ($connect->query($sql) === true ) {
    //         $class = "success";
    //         $messageQA = "The comment was successfully created";
    //     } else {
    //         $class = "danger";
    //         $messageQA = "Error while creating comment. Try again: <br>" . $connect->error;
    //     }
    //     $connect->close();
    // } else {
    //     //header("location: ../error.php"); //-----------------------------------
    // }

    // // Print Reviews
    // $sql = "SELECT * FROM "; //-----------------------------------
    // $result = mysqli_query($connect ,$sql);
    // $review='';
    // if(mysqli_num_rows($result) > 0) {    
    //     while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){        
    //     $review .= " ";  //-----------------------------------
    // };
    // } else {
    //     $review =  " ";  //-----------------------------------
    // }

    // // Print Q&A
    // $sql = "SELECT * FROM "; //-----------------------------------
    // $result = mysqli_query($connect ,$sql);
    // $question='';
    // if(mysqli_num_rows($result) > 0) {    
    //     while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){        
    //     $question .= " ";  //-----------------------------------
    // };
    // } else {
    //     $question =  " ";  //-----------------------------------
    // }

    // $connect->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Name</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/details.css">
</head>
<body>
    <div class="container">
        <div id="product">
            <h2 id="name">Product Name</h2>
            <img src="https://pbs.twimg.com/profile_images/708173709/Picture_2_400x400.png" alt="Product" width="300px">
            <ul id="list">
                <li>product info</li>
                <li>product info</li>
                <li>product info</li>
                <li>product info</li>
            </ul>
            <p id="desc">Description: Lorem, ipsum dolor sit amet consectetur adipisicing elit. Animi nemo corrupti commodi cum amet molestiae fugit, incidunt voluptatem quo quidem quasi rerum eum non optio, magni fugiat aliquid, sequi est!</p>
        </div>
        <hr>
        <div id="review">
            <h3>Reviews</h3>
            <p class="text-warning">PHP echo reviews <?= $review;?></p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                <div class="mb-3">
                    <input class="form-control" type="text" name="review" placeholder="Leave a review here" id="reviewText" style="width: 80vw"></input>
                </div>
                <span class="text-<?=$class;?>"><?php echo ($messageReview) ?? ''; ?></span><br>
                <button type="submit" class="btn btn-primary">Create Review</button>
            </form>
        </div>
        <hr>
        <div id="qAndA">
            <h3>Q&A</h3>
            <p class="text-warning">PHP echo Questions <?= $question;?></p>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                <div class="mb-3">
                    <input class="form-control" type="text" name="question" placeholder="Leave a review here" id="questionText" style="width: 80vw"></input>
                </div>
                <span class="text-<?=$class;?>"><?php echo ($messageQA) ?? ''; ?></span><br>
                <button type="submit" class="btn btn-primary">Create Question</button>
            </form>
        </div>
    </div>
    <!-- <?php require_once 'components/footer.php' ?> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>