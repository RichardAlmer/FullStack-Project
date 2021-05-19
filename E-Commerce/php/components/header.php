<?php 
function navbar($level=""){
echo '<header class="my-4">
    <nav class="navbar navbar-expand-lg navbar-light my_bg">
        <div class="container container-fluid">
            <a class="navbar-brand" href="'.$level.'">
                <img class="logo" src="'.$level.'img/general_images/logo.png" alt="">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <li class="nav-item px-2">
                        <a class="nav-link my_text" href="'.$level.'/">Home</a>
                    </li>
                    <li class="nav-item dropdown px-2">
                        <a class="nav-link my_text dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="/">Products</a>

                        <ul class="dropdown-menu my_text" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item my_text" href="#">Fashion</a></li>
                            <li><a class="dropdown-item my_text" href="#">Cosmetics</a></li>
                            <li><a class="dropdown-item my_text" href="#">Electronics</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item my_text" href="'.$level.'php/product/product-catalog.php">All products</a></li>
                        </ul>
                    </li>

                    <?php
                        if ( !isset($_SESSION["adm"]) && !isset($_SESSION["user"]) ) {
                    ?>
                    <li class="nav-item px-2">
                        <a class="nav-link my_text" href="/">Sign in</a>
                    </li>

                    <?php }
                    ?>

                    <li class="nav-item px-2">
                        <a class="nav-link text-dark" href="/">
                            <img class="cart_img" src="'.$level.'img/general_images/cart.png" alt="">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>';
}