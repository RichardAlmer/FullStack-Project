<?php 

function discountedPrice($price, $discount_procent) {
    return $price / 100 * (100-$discount_procent);
}

function getStars($rating) {
    $stars = "";
    switch($rating){
        case 1:
            $stars = "<span class='my_text_maincolor'>★</span>☆☆☆☆";
            break;
        case 2:
            $stars = "<span class='my_text_maincolor'>★★</span>☆☆☆";
            break;
        case 3:
            $stars = "<span class='my_text_maincolor'>★★★</span>☆☆";
            break;
        case 4:
            $stars = "<span class='my_text_maincolor'>★★★★</span>☆";
            break;
        case 5:
            $stars = "<span class='my_text_maincolor'>★★★★★</span>";
            break;
        default:
            $stars = "☆☆☆☆☆";
    }
    return $stars;    
}

?>
