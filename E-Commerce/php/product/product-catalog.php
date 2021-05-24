<?php

require_once '../components/db_connect.php';
require_once 'actions/helper-functions.php';

// get category filter links
$sqlCategories = ("SELECT DISTINCT category FROM product");
$resultCategories = mysqli_query($conn ,$sqlCategories);
$categories=''; 
if(mysqli_num_rows($resultCategories) > 0) {     
    while($row = mysqli_fetch_array($resultCategories, MYSQLI_ASSOC)){ 
        $categories .= "<div class='my_text my-3'><a class='my_text' href='#' onclick='filterProducts(\"category\", \"".$row['category']."\")'>".$row['category']."</a></div>";
    }
}

// get products
$sql = ("SELECT * FROM product WHERE status = 'active' ORDER BY pk_product_id DESC");
$result = mysqli_query($conn ,$sql);
$resultHtml=''; 
$currentPrice='';
if(mysqli_num_rows($result) > 0) {     
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){      
        $currentPrice = discountedPrice($row['price'],$row['discount_procent']);

        $sqlRating = "SELECT AVG(rating) AS rating FROM review WHERE fk_product_id = $row[pk_product_id]";
        $resultRating = mysqli_query($conn ,$sqlRating);
        $dataRating = $resultRating->fetch_assoc();
        $stars = getStars(round($dataRating['rating']));
        $resultHtml .= "<div class='col-12 col-md-4 col-lg-3 py-2 box_height'>
                        <a href='product-details.php?id=" .$row['pk_product_id']."'>
                            <div class='square'>
                                <img class='content' src='../../img/product_images/{$row['image']}' alt=''>
                            </div>
                            <div class='row py-3 text-center'>
                                <div class='col-12 fs-5 my-2'>{$row['name']}</div>
                                <a href='/' class='col-12 my-1 my_text_maincolor'>{$row['category']}</a>
                                <a href='/' class='col-12 my-1'>{$row['brand']}</a> 
                                <div class='col-12 fw-bold my-3'>€{$currentPrice} ";
        if ($row['discount_procent'] != '0') {
            $resultHtml .= "<span class='my_text_maincolor'>(-{$row['discount_procent']}%)</span>";
        }
        $resultHtml .= "</div><div class='col-12 fw-bold my-3'>".$stars."
        </div></div></a></div>";  
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
            navbar("../../", "../"); 
        ?>

        <div class="container">
            <div class="row my-5 pt-5">
                <div class="col-12 col-lg-4 fs_6 text-uppercase my-2">All products</div>
                <div class="col-12 col-lg-3 fs_6 my-3 my-lg-2">
                    <input type="text" class="form-control rounded-pill px-4" onkeyup="showResult(this.value)" name="search" id="search" aria-describedby="searchHelp" placeholder="Search for product">
                </div>
                <div class="col-12 col-lg-5 my-2 text-lg-end mt-3 my-lg-2 justify-content-lg-end">
                    <div class="row px-3 px-lg-0">
                        <a href="" class="col-12 col-md-auto my-lg-2 px-1">
                            <div class="btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-3 text-white my-1 my-md-0">
                                <a href='#' onclick='filterProducts("category","all","rating","DESC")'>Sort by rating</a>
                            </div>
                        </a>
                        <a href="" onclick="showProducts('price', 'desc')" class="col-12 col-md-auto my-lg-2 px-1">
                            <div class="btn bg_gray bg_hover rounded-pill col-12 col-md-auto py-2 px-3 text-white">
                                <a href='#' onclick='filterProducts("category","all","price","ASC")'>Sort by price</a>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mb-4">
            <div class="row py-3 flex-column-reverse flex-lg-row">
                <div id="result" class="row col-12 col-lg-10">
                    <?php echo $resultHtml; ?>
                </div>

                <div class="col-12 col-lg-2 py-1 ps-0 ps-lg-4 pe-0">
                    <div class="fw-bold mb-3 fs-5">Shop by category</div> 
                    <?php echo $categories; ?>
                    <div class='my_text'><a class='my_text' href='#' onclick='filterProducts("category","all")'>All categories</a></div><br/>
                </div>
            </div>
        </div>
        
        <script>
            function filterProducts(filter, value, sort="none", order="ASC") {
                if (filter == "") {
                    document.getElementById("result").innerHTML = "no result";
                    return;
                } else {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("result").innerHTML = this.responseText;
                        }
                    };
                    xmlhttp.open("GET", "actions/product-filter.php?filter="+filter+"&value="+value+"&sort="+sort+"&order="+order, true);
                    xmlhttp.send();
                }
                
            }

            function showResult(str) {
                if (str.length==0) {
                    document.getElementById("result").innerHTML="";
                    document.getElementById("result").style.border="0px";
                    return;
                }
                var xmlhttp=new XMLHttpRequest();
                xmlhttp.onreadystatechange=function() {
                if (this.readyState==4 && this.status==200) {
                    document.getElementById("result").innerHTML=this.responseText;
                    //document.getElementById("result").style.border="1px solid #A5ACB2";
                }
            }
                xmlhttp.open("GET","actions/product-search.php?search="+str,true);
                xmlhttp.send();
            }
        </script>
        
        <?php 
            require_once '../../php/components/footer.php';
            footer("../../");
            require_once '../../php/components/boot-javascript.php';
        ?>
    </body>
</html>