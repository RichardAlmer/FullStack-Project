<?php 

function navbar($level1="", $level2="", $level3="", $id="", $session = "", $cartCount = "", $image = ""){
    if($session == ""){
        echo '<header class="my-4">
        <nav class="navbar navbar-expand-lg navbar-light my_bg">
            <div class="container container-fluid">
                <a class="navbar-brand" href="'.$level1.'">
                    <img class="logo" src="'.$level1.'img/general_images/logo.png" alt="">
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'">Home</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'php/product/product-catalog.php">Products</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'php/login.php">Login</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'php/register.php">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>';
    } else if ($session == "user"){

        if ($cartCount) {
           $cartCount = '<span id="count" class="position-absolute bg_maincolor text-white rounded-circle fs_7 text-center align-bottom cart-number">'.$cartCount.'</span>';
        }
        
        echo '<header class="my-4">
        <nav class="navbar navbar-expand-lg navbar-light my_bg">
            <div class="container container-fluid">
                <a class="navbar-brand" href="'.$level1.'">
                    <img class="logo" src="'.$level1.'img/general_images/logo.png" alt="">
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'">Home</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'php/product/product-catalog.php">Products</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level2.'logout.php?logout&level='.$level3.'">Logout</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-dark position-relative" href="'.$level1.'php/cart/cart.php">
                                <img class="cart_img" src="'.$level1.'img/general_images/cart.png" alt="cart">
                                '.$cartCount.'
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-dark" href="'.$level1.'php/user/profile.php?id='.$id.'">
                                <img class="profile_img img-thumbnail rounded-circle" src="'.$level1.'img/user_images/'.$image.'" alt="profile">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>';
    } else if($session == "admin"){
        if ($cartCount) {
           $cartCount = '<span id="count" class="position-absolute bg_maincolor text-white rounded-circle fs_7 text-center align-bottom cart-number">'.$cartCount.'</span>';
        }

        echo '<header class="my-4">
        <nav class="navbar navbar-expand-lg navbar-light my_bg">
            <div class="container container-fluid">
                <a class="navbar-brand" href="'.$level1.'">
                    <img class="logo" src="'.$level1.'img/general_images/logo.png" alt="">
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'">Home</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'php/product/product-catalog.php">Products</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level2.'logout.php?logout&level='.$level3.'">Logout</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'php/admin/dashboard.php">Admin Dashboard</a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-dark position-relative" href="'.$level1.'php/cart/cart.php">
                                <img class="cart_img" src="'.$level1.'img/general_images/cart.png" alt="cart">
                                '.$cartCount.'
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-dark" href="'.$level1.'php/user/profile.php?id='.$id.'">
                                <img class="profile_img img-thumbnail rounded-circle" src="'.$level1.'img/user_images/'.$image.'" alt="profile">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>';
    }
}
?>
