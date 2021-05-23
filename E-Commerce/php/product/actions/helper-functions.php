<?php 

function discountedPrice($price, $discount_procent) {
    return $price / 100 * (100-$discount_procent);
}

function getStars($rating) {
    $stars = "";
    switch($rating){
        case 1:
            $stars = "★☆☆☆☆";
            break;
        case 2:
            $stars = "★★☆☆☆";
            break;
        case 3:
            $stars = "★★★☆☆";
            break;
        case 4:
            $stars = "★★★★☆";
            break;
        case 5:
            $stars = "★★★★★";
            break;
        default:
            $stars = "☆☆☆☆☆";
    }
    return $stars;    
}

?>
