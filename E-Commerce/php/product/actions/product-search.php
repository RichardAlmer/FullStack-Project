<?php
$searchTerm = $_GET['search'];

require_once '../../components/db_connect.php';

mysqli_select_db($conn,"ajax_demo");

$sql="SELECT * FROM product WHERE status = 'active'";

if ($searchTerm != ''){
    $sql .= " AND name LIKE '%".$searchTerm."%'";
}

$sql .= " ORDER BY name";

$result = mysqli_query($conn,$sql);

$resultHtml=''; 
$currentPrice='';

while($row = mysqli_fetch_array($result)) {

    $currentPrice = $row['price'] / 100 * (100-$row['discount_procent']);
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
    $resultHtml .= "</div></div></a></div>";  
  
  }
  echo $resultHtml;
  mysqli_close($conn);