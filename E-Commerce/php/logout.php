<?php
session_start();
if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
else if(isset($_SESSION['user']) != "") {
    header("Location: product/product-catalog.php");
}
else if(isset($_SESSION['admin']) != "") {
    header("Location: admin/dashboard.php");
}

if(isset($_GET['logout'])) {
    unset($_SESSION['user']);
    unset($_SESSION['admin']);
    session_unset();
    session_destroy();
    header("Location: " . $_GET["level"] . "index.php");
    exit;
}
