<?php

require_once 'components/db_connect.php';

// product -----------------------------------------------------------
$sqlProduct = ("SELECT * FROM product ORDER BY pk_product_id DESC");
$resultProduct = mysqli_query($conn ,$sqlProduct);
$htmlProduct="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_product_id</th>
                        <th>name</th>
                        <th>description</th>
                        <th>brand</th>
                        <th>image</th>
                        <th>price</th>
                        <th>category</th>
                        <th>status</th>
                        <th>discount_procent</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultProduct) > 0) {     
    while($row = mysqli_fetch_array($resultProduct, MYSQLI_ASSOC)){ 
        $htmlProduct .= "
        <tr>
            <td>" . $row['pk_product_id'] . "</td>
            <td>" . $row['name'] . "</td>
            <td>" . $row['description'] . "</td>
            <td>" . $row['brand'] . "</td>
            <th>" . $row['image'] . "</th>
            <td>" . $row['price'] . "</td>
            <td>" . $row['category'] . "</td>
            <td>" . $row['status'] . "</td>
            <td>" . $row['discount_procent'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlProduct =  "<center>No Data Available </center>";
}
$htmlProduct .= "</tbody></table>";


// question -----------------------------------------------------------
$sqlQuestion = ("SELECT * FROM question");
$resultQuestion = mysqli_query($conn ,$sqlQuestion);
$htmlQuestion="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_question_id</th>
                        <th>question</th>
                        <th>create_datetime</th>
                        <th>fk_product_id</th>
                        <th>fk_user_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultQuestion) > 0) {     
    while($row = mysqli_fetch_array($resultQuestion, MYSQLI_ASSOC)){ 
        $htmlQuestion .= "
        <tr>
            <td>" . $row['pk_question_id'] . "</td>
            <td>" . $row['question'] . "</td>
            <td>" . $row['create_datetime'] . "</td>
            <th>" . $row['fk_product_id'] . "</th>
            <td>" . $row['fk_user_id'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlQuestion =  "<center>No Data Available </center>";
}
$htmlQuestion .= "</tbody></table>";


// answer -----------------------------------------------------------
$sqlAnswer = ("SELECT * FROM answer");
$resultAnswer = mysqli_query($conn ,$sqlAnswer);
$htmlAnswer="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_answer_id</th>
                        <th>answer</th>
                        <th>create_datetime</th>
                        <th>fk_question_id</th>
                        <th>fk_user_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultAnswer) > 0) {     
    while($row = mysqli_fetch_array($resultAnswer, MYSQLI_ASSOC)){ 
        $htmlAnswer .= "
        <tr>
            <td>" . $row['pk_answer_id'] . "</td>
            <td>" . $row['answer'] . "</td>
            <td>" . $row['create_datetime'] . "</td>
            <th>" . $row['fk_question_id'] . "</th>
            <td>" . $row['fk_user_id'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlAnswer =  "<center>No Data Available </center>";
}
$htmlAnswer .= "</tbody></table>";


// review -----------------------------------------------------------
$sqlReview = ("SELECT * FROM review");
$resultReview = mysqli_query($conn ,$sqlReview);
$htmlReview="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_review_id</th>
                        <th>rating</th>
                        <th>title</th>
                        <th>comment</th>
                        <th>create_datetime</th>
                        <th>fk_product_id</th>
                        <th>fk_user_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultReview) > 0) {     
    while($row = mysqli_fetch_array($resultReview, MYSQLI_ASSOC)){ 
        $htmlReview .= "
        <tr>
            <td>" . $row['pk_review_id'] . "</td>
            <td>" . $row['rating'] . "</td>
            <td>" . $row['title'] . "</td>
            <th>" . $row['comment'] . "</th>
            <td>" . $row['create_datetime'] . "</td>
            <td>" . $row['fk_product_id'] . "</td>
            <td>" . $row['fk_user_id'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlReview =  "<center>No Data Available </center>";
}
$htmlReview .= "</tbody></table>";


// user -----------------------------------------------------------
$sqlUser = ("SELECT * FROM user");
$resultUser = mysqli_query($conn ,$sqlUser);
$htmlUser="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_user_id</th>
                        <th>email</th>
                        <th>password</th>
                        <th>first_name</th>
                        <th>last_name</th>
                        <th>address</th>
                        <th>city</th>
                        <th>postcode</th>
                        <th>country</th>
                        <th>birthdate</th>
                        <th>status</th>
                        <th>role</th>
                        <th>profile_image</th>
                        <th>banned_until</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultUser) > 0) {     
    while($row = mysqli_fetch_array($resultUser, MYSQLI_ASSOC)){ 
        $htmlUser .= "
        <tr>
            <td>" . $row['pk_user_id'] . "</td>
            <td>" . $row['email'] . "</td>
            <td>" . $row['password'] . "</td>
            <th>" . $row['first_name'] . "</th>
            <td>" . $row['last_name'] . "</td>
            <td>" . $row['address'] . "</td>
            <td>" . $row['city'] . "</td>
            <td>" . $row['postcode'] . "</td>
            <td>" . $row['country'] . "</td>
            <td>" . $row['birthdate'] . "</td>
            <td>" . $row['status'] . "</td>
            <td>" . $row['role'] . "</td>
            <td>" . $row['profile_image'] . "</td>
            <td>" . $row['banned_until'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlUser =  "<center>No Data Available </center>";
}
$htmlUser .= "</tbody></table>";


// cart_item -----------------------------------------------------------
$sqlCartItemr = ("SELECT * FROM cart_item");
$resultCartItem = mysqli_query($conn ,$sqlCartItemr);
$htmlCartItem="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_cart_item_id</th>
                        <th>quantity</th>
                        <th>fk_product_id</th>
                        <th>fk_user_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultCartItem) > 0) {     
    while($row = mysqli_fetch_array($resultCartItem, MYSQLI_ASSOC)){ 
        $htmlCartItem .= "
        <tr>
            <td>" . $row['pk_cart_item_id'] . "</td>
            <td>" . $row['quantity'] . "</td>
            <td>" . $row['fk_product_id'] . "</td>
            <th>" . $row['fk_user_id'] . "</th>
            <td>
        </tr>";
    };
} else  {
   $htmlCartItem =  "<center>No Data Available </center>";
}
$htmlCartItem .= "</tbody></table>";


// Purchase -----------------------------------------------------------
$sqlPurchase = ("SELECT * FROM purchase");
$resultPurchase = mysqli_query($conn ,$sqlPurchase);
$htmlPurchase="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_purchase_id</th>
                        <th>create_datetime</th>
                        <th>fk_user_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultPurchase) > 0) {     
    while($row = mysqli_fetch_array($resultPurchase, MYSQLI_ASSOC)){ 
        $htmlPurchase .= "
        <tr>
            <td>" . $row['pk_purchase_id'] . "</td>
            <td>" . $row['create_datetime'] . "</td>
            <td>" . $row['fk_user_id'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlPurchase =  "<center>No Data Available </center>";
}
$htmlPurchase .= "</tbody></table>";


// purchase_item -----------------------------------------------------------
$sqlPurchaseItem = ("SELECT * FROM purchase_item");
$resultPurchaseItem = mysqli_query($conn ,$sqlPurchaseItem);
$htmlPurchaseItem="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_purchase_item_id</th>
                        <th>quantity</th>
                        <th>fk_product_id</th>
                        <th>fk_purchase_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultPurchaseItem) > 0) {     
    while($row = mysqli_fetch_array($resultPurchaseItem, MYSQLI_ASSOC)){ 
        $htmlPurchaseItem .= "
        <tr>
            <td>" . $row['pk_purchase_item_id'] . "</td>
            <td>" . $row['quantity'] . "</td>
            <td>" . $row['fk_product_id'] . "</td>
            <th>" . $row['fk_purchase_id'] . "</th>
            <td>
        </tr>";
    };
} else  {
   $htmlPurchaseItem =  "<center>No Data Available </center>";
}
$htmlPurchaseItem .= "</tbody></table>";


// chat -----------------------------------------------------------
$sqlChat = ("SELECT * FROM chat");
$resultChat = mysqli_query($conn ,$sqlChat);
$htmlChat="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_chat_id</th>
                        <th>status</th>
                        <th>fk_user_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultChat) > 0) {     
    while($row = mysqli_fetch_array($resultChat, MYSQLI_ASSOC)){ 
        $htmlChat .= "
        <tr>
            <td>" . $row['pk_chat_id'] . "</td>
            <td>" . $row['status'] . "</td>
            <td>" . $row['fk_user_id'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlChat =  "<center>No Data Available </center>";
}
$htmlChat .= "</tbody></table>";


$htmlChatMessage = '';
// chat_message -----------------------------------------------------------
$sqlChatMessage = ("SELECT * FROM chat_message");
$resultChatMessage = mysqli_query($conn ,$sqlChatMessage);
$htmlChatMessage="<table class='table table-striped'>
                <thead class='table-success'>
                    <tr>
                        <th>pk_chat_message_id</th>
                        <th>message</th>
                        <th>create_datetime</th>
                        <th>fk_user_id</th>
                        <th>fk_chat_id</th>
                    </tr>
                </thead>
                <tbody>"; 
if(mysqli_num_rows($resultChatMessage) > 0) {     
    while($row = mysqli_fetch_array($resultChat, MYSQLI_ASSOC)){ 
        $htmlChat .= "
        <tr>
            <td>" . $row['pk_chat_message_id'] . "</td>
            <td>" . $row['message'] . "</td>
            <td>" . $row['create_datetime'] . "</td>
            <td>" . $row['fk_user_id'] . "</td>
            <td>" . $row['fk_chat_id'] . "</td>
            <td>
        </tr>";
    };
} else  {
   $htmlChatMessage =  "<center>No Data Available </center>";
}
$htmlChatMessage .= "</tbody></table>";




$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tables from DB</title>
    <?php require_once 'components/boot.php' ?>
    <link rel='stylesheet' type='text/css' href='../style/main-style.css'>
</head>

<body>
    <div id="container">
        <div id="content">
            <h1>product</h1><div><?= $htmlProduct ?></div>     
            <h1>question</h1><div><?= $htmlQuestion ?></div>
            <h1>answer</h1><div><?= $htmlAnswer ?></div>
            <h1>review</h1><div><?= $htmlReview ?></div>
            <h1>user</h1><div><?= $htmlUser ?></div>
            
            <h1>cart_item</h1><div><?= $htmlCartItem ?></div>
            <h1>purchase</h1><div><?= $htmlPurchase ?></div>
            <h1>purchase_item</h1><div><?= $htmlPurchaseItem ?></div>
            <h1>chat</h1><div><?= $htmlChat ?></div>
            <h1>chat_message</h1><div><?= $htmlChatMessage ?></div>

        </div>
    </div>

    <?php require_once 'components/boot-javascript.php' ?>
</body>

</html>