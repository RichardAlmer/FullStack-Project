<?php
header("Content-Type:application/json");
//EMAIL API

//Get user and purchase data with purchase ID
if (!empty($_GET['id'])) {
    require_once 'db_connect.php';

    $purchaseId = $_GET['id'];

    $sql = "SELECT * 
            FROM purchase_item pi 
            INNER JOIN purchase pu ON pi.fk_purchase_id = pu.pk_purchase_id 
            INNER JOIN user u ON pu.fk_user_id = u.pk_user_id 
            INNER JOIN product pr ON pi.fk_product_id = pr.pk_product_id
            WHERE pk_purchase_id = '$purchaseId'";

    $result = $conn->query($sql);
    $productList = "";
    if ($result) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $firstName = $rows[0]['first_name'];
        $lastName = $rows[0]['last_name'];
        $email = $rows[0]['email'];
        $createDateTimeStrg = $rows[0]['create_datetime'];
        $createDateTime = strtotime($createDateTimeStrg);
        $createDateTimeResult = date('d.m.y H:i:s', $createDateTime);
        $total = 0;

        foreach ($rows as $row) {
            $price = number_format($row['price'], 2);
            $quantity = number_format($row['quantity']);
            $discountPercent = number_format($row['discount_procent']);
            $discountPercent /= 100; 
            if($discountPercent == 0) {
                $discountPercent = 1;
            }
            $price = ($price * $quantity) * $discountPercent;
            $total += $price;

            $productList .= '
                <tr>
                    <td>' . $row['name'] . '</td>
                    <td> | ' . $row['brand'] . '</td>
                    <td> | amount: ' . $quantity . '</td>
                    <td> | price: ' . $price . '&#8364;</td>
                </tr>
            ';
        }

        // Receiver <----------------- Email address
        $receiver  = $email;

        // Subject
        $subject = 'Swoosh Order Notification';

        // Message
        $message = '
        <html>
        <head>
          <title>Swoosh Order Notification</title>
        </head>
        <body>
            <h3>Hi ' . $firstName . '!</h3>
            <h3 style="color: #06d7a0">Your Swoosh Orders</h3>
            <hr>
            <p>Purchase number: ' . $purchaseId . '</p>
            <p>Ordered on: ' . $createDateTimeResult . '</p>
            <table>
                ' . $productList . '
                <hr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><strong>&nbsp;&nbsp;total: ' . $total . '&#8364;</strong></td>
                </tr>
            </table>
        </body>
        </html>
        ';

        // For HTML-E-Mails the 'Content-type'-Header has to be defined
        $header[] = 'MIME-Version: 1.0';
        $header[] = 'Content-type: text/html; charset=iso-8859-1';

        // additional headers
        $header[] = 'To: ' . $firstName . ' <' . $email . '>';
        $header[] = 'From: Swoosh <noreply@swoosh.com>';

        // Send email
        if (mail($receiver, $subject, $message, implode("\r\n", $header))) {
            response(200, "A notificaton has been send to your email addresss.", $rows);
            // response(200, "Email error");
        } else {
            response(200, "Email error, please contact our support.", $rows);
            // response(200, "Email error");
        }
    } else {
        response(200, "DB Error:" . $conn->error, null);
        // response(200, "DB Error:" . $conn->error);
    }
} else {
    response(400, "Invalid request", null);
    // response(400, "Invalid request");
}

//returning JSON response
function response($status, $statusMessage, $data)
// function response($status, $statusMessage)
{
    //array
    $response['status'] = $status;
    $response['statusMessage'] = $statusMessage;
    $response['data'] = $data;

    //JSON
    $jsonResponse = json_encode($response);

    // Outputting JSON to the client
    echo $jsonResponse;
}