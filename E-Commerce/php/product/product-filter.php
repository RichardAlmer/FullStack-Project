<?php
$filter = $_GET['filter'];
$value = $_GET['value'];

require_once '../components/db_connect.php';

mysqli_select_db($conn,"ajax_demo");

$sql="SELECT * FROM product";

if ($filter === 'category' && $value !== 'All products') {
  $sql .= " WHERE category = '".$value."'";
  
} 
if ($filter === 'rating') {
  $sql .= " ORDER BY price ASC";
}
if ($filter === 'price') {
  $sql .= " WHERE category = sport";
  $sql .= " ORDER BY price ASC";
}

$result = mysqli_query($conn,$sql);

while($row = mysqli_fetch_array($result)) {
  echo "<div class='col-12 col-md-4 col-lg-2 py-2 box_height'>
          <a href='product-details.php?id=" .$row['pk_product_id']."'>
            <div class='square'><img class='content' src='../../img/general_images/img2.jpg' alt=''></div>
            <div class='row py-3 text-center'>
            <div class='col-12 fs-5 my-2'>{$row['name']}</div>
          </a>
          <a href='/' class='col-12 my-1 my_text_maincolor'>{$row['category']}</a>
          <a href='/' class='col-12 my-1'>{$row['brand']}</a>
          <div class='col-12 fw-bold my-3'>€{$row['price']} ";
  if ($row['discount_procent'] != '100') {
    echo "<span class='my_text_maincolor'>(-{$row['discount_procent']}%)</span>";
  }
  echo "</div></div></a></div>";  
}

mysqli_close($conn);
?>