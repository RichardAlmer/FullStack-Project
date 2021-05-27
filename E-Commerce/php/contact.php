<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <?php require_once 'components/boot.php'?>
    <link rel="stylesheet" href="../style/main-style.css" />
    <link rel="stylesheet" href="../style/contact.css" />
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
        if(isset($_SESSION['admin']) || isset($_SESSION['user'])){
            $sqlCart = "SELECT COUNT(quantity), profile_image FROM cart_item INNER JOIN user ON fk_user_id = pk_user_id WHERE fk_user_id = {$id}";
            $result = $conn->query($sqlCart);
                if ($result->num_rows == 1){
                    $data = $result->fetch_assoc();
                    $image = $data['profile_image'];
                    if($data['COUNT(quantity)'] != 0){
                        $cartCount = $data['COUNT(quantity)'];
                    }
                }
        }
        navbar("../", "", "", $id, $session, $cartCount, $image);
    ?>
    <div class="container">
        <div class="row my-5 pt-5">
            <div class="col-12 col-md-6 fs_6 text-uppercase my-2">Contact</div>
        </div>
    </div>
            
    <div class="container">
        <div class="row my-5 py-4">
            <div class="col-12 col-lg-6 pe-lg-5 mb-5 mb-lg-0">
                <div class="col-12 fw-bold my-1">Address:</div>
                <div class="col-12 mb-3">Kettenbrückengasse 23 / 2 / 12, 1050 Wien</div>
                <div class="col-12 fw-bold my-1">Phone:</div>
                <div class="col-12 mb-3">012353213</div>
                <div class="mt-5">
                    <div class="fs-5">You can also <span class="my_text_maincolor">send us a message</span></div>
                    <form>
                        <div id="emailHelp" class="form-text my_text_lightgray my-2">We'll never share your email with anyone else.</div>
                        <input type="email" class="form-control my-3" aria-describedby="emailHelp" placeholder="Insert your E-Mail">
                        <textarea class="form-control my-3" id="exampleFormControlTextarea1" rows="3" placeholder="Write your message"></textarea>
                        <button type="submit" class="btn bg_gray rounded-pill my-2 col-12 col-md-6 py-2 text-white bg_hover">Send</button>
                    </form>
                </div>
            </div>
            <div id="map" class="col-12 col-lg-6 map_height"></div>
        </div>
    </div>

    <?php 
        require_once 'components/footer.php';
        footer("../");
        require_once 'components/boot-javascript.php';
    ?>

    <script>
        var map;
        function initMap() {
            var location = {
                lat: 48.19646,
                lng: 16.35949
            };
            map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 14
            });
            var pinpoint = new google.maps.Marker({
                position: location,
                map: map
            });
        };
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtjaD-saUZQ47PbxigOg25cvuO6_SuX3M&callback=initMap" async defer></script>
</body>
</html>