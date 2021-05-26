<?php
$filter = $_GET['filter'];
$value = $_GET['value'];
$sort = $_GET['sort'];
$order = $_GET['order'];

require_once '../../components/db_connect.php';
require_once 'helper-functions.php';

mysqli_select_db($conn,"ajax_demo");

$sql="SELECT * FROM product WHERE status = 'active'";

if (($filter === 'category' || $filter === 'clear') && $value !== 'all') {
  $sql .= " AND category = '".$value."'";
  
} 
if ($sort === 'price') {
  $sql .= " ORDER BY price / 100 * (100 - discount_procent) ".$order."";
}
if ($sort === 'default') {
  // To Do Calculate average rating of products ------------------------------------------
  $sql .= " ORDER BY pk_product_id DESC";
  
}

$result = mysqli_query($conn,$sql);

$resultHtml=''; 
$currentPrice='';

while($row = mysqli_fetch_array($result)) {

  //$currentPrice = $row['price'] / 100 * (100-$row['discount_procent']);
  $price = '';
  if ($row['discount_procent']) {
      $price = '€'. number_format($row['price'], 2, ',', ' ');
  }   
  $currentPrice = discountedPrice($row['price'],$row['discount_procent']);

  $sqlRating = "SELECT AVG(rating) AS rating FROM review WHERE fk_product_id = $row[pk_product_id]";
  $resultRating = mysqli_query($conn ,$sqlRating);
  $dataRating = $resultRating->fetch_assoc();
  $stars = getStars(round($dataRating['rating']));

  $resultHtml .= "<div class='col-12 col-md-4 col-lg-3 py-3 box_height'>
                  <a href='product-details.php?id=" .$row['pk_product_id']."'>
                      <div class='square'>
                          <img class='content' src='../../img/product_images/{$row['image']}' alt=''>
                      </div>
                      <div class='row py-3 text-center'>
                          <div class='col-12 fs-5 my-2'>{$row['name']}</div>
                          <div class='col-12 my-1 my_text_maincolor'>{$row['category']}</div>
                          <div class='col-12'>{$row['brand']}</div> 
                          <div class='col-12 fw-bold mt-2'>".$stars."</div>
                          <div class='col-12 text-decoration-line-through mt-2'>{$price}</div>
                          <div class='col-12 fw-bold'>€{$currentPrice} ";
  if ($row['discount_procent'] != '0') {
      $resultHtml .= "<span class='my_text_maincolor'>(-{$row['discount_procent']}%)</span>";
  }
  $resultHtml .= "</div></div></a></div>";  

}
echo $resultHtml;
mysqli_close($conn);
?>