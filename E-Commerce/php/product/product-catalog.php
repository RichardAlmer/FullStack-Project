<?php

require_once '../components/db_connect.php';

$sqlCategories = ("SELECT DISTINCT category FROM product");
$resultCategories = mysqli_query($conn ,$sqlCategories);
$categories=''; 
if(mysqli_num_rows($resultCategories) > 0) {     
    while($row = mysqli_fetch_array($resultCategories, MYSQLI_ASSOC)){ 
        $categories .= "<option value=".$row['category'].">".$row['category']."</option>";
    }
}

$sql = ("SELECT * FROM product");
$result = mysqli_query($conn ,$sql);
$resultHtml=''; 
if(mysqli_num_rows($result) > 0) {     
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){ 
       $resultHtml .= "<div class='col-12 col-md-4 col-lg-2 py-2 box_height'>
                        <a href='detail.php?id=" .$row['pk_product_id']."'>
                            <div class='square'>
                                <img class='content' src='../../img/general_images/img2.jpg' alt=''>
                            </div>
                            <div class='row py-3 text-center'>
                                <div class='col-12 fs-5 my-2'>{$row['name']}</div>
                                <a href='/' class='col-12 my-1 my_text_maincolor'>{$row['category']}</a>
                                <a href='/' class='col-12 my-1'>{$row['brand']}</a>
                                <div class='col-12 fw-bold my-3'>€{$row['price']} ";
        if ($row['discount_procent'] != '100') {
            $resultHtml .= "<span class='my_text_maincolor'>(-{$row['discount_procent']}%)</span>";
        }
        $resultHtml .= "</div></div></a></div>";  
    };
} else  {
   $tbody =  "<div><center>No Data Available </center></div>";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>All products</title>
        <?php require_once '../../php/components/boot.php'?>
        <link rel="stylesheet" href="../../style/main-style.css" />
    </head>
    <body>
        <?php 
            require_once '../../php/components/header.php';
            navbar("../../");
        ?>

        <div class="container">
            <div class="row my-5 pt-5">
                <div class="col-12 col-md-6 fs_6 text-uppercase my-2">All products</div>
                <div class="col-12 col-md-6 my-2 text-end">
                    <a href="" class="col-12 col-md-auto my-2 px-1">
                        <div class="btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-3 text-white my-1 my-md-0">Sort by rating</div>
                    </a>
                    <a href="" onclick="showProducts('price', 'desc')" class="col-12 col-md-auto my-2 px-1">
                        <div class="btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-3 text-white">Sort by price</div>
                    </a>
                </div>
                <form>
                    <select name="users" onchange="showProducts('category', this.value)">
                        <option value="All products">All products</option>
                        <?php echo $categories; ?>
                    </select>
                </form>
            </div>
        </div>

        <div class="container mb-4">
            <div id="result" class="row row_width py-3">
                <?php echo $resultHtml; ?>
            </div>
        </div>
        <?php 
            require_once '../../php/components/footer.php';
            footer("../../");
            require_once '../../php/components/boot-javascript.php';
        ?>
        <script>
            function showProducts(filter, value, order="desc") {
                if (value == "") {
                    document.getElementById("result").innerHTML = "no result";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("result").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET", "product-filter.php?filter="+filter+"&value="+value, true);
                    xmlhttp.send();
                }
            }
        </script>
        <?php require_once '../../php/components/boot-javascript.php'?>
    </body>
</html>