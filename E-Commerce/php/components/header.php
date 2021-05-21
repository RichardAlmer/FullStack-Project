<?php 
function navbar($level1="", $level2="", $id=""){

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
                            <a class="nav-link my_text" href="'.$level1.'/">Home</a>
                        </li>
                        <li class="nav-item dropdown px-2">
                            <a class="nav-link my_text dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" href="/">Products</a>

                            <ul class="dropdown-menu my_text" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item my_text" href="#">Cosmetics</a></li>
                                <li><a class="dropdown-item my_text" href="#">Sports</a></li>
                                <li><a class="dropdown-item my_text" href="#">Fashion</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item my_text" href="'.$level1.'php/product/product-catalog.php">All products</a></li>
                            </ul>
                        </li>

                        <?php
                            if (!isset($_SESSION["admin"]) && !isset($_SESSION["user"])) {
                        ?>

                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level1.'php/login.php">Login</a>
                        </li>

                        <?php } ?>

                        <?php
                        if ( isset($_SESSION["admin"]) || isset($_SESSION["user"])) {
                        ?>

                        <li class="nav-item px-2">
                            <a class="nav-link my_text" href="'.$level2.'logout.php?logout&level='.$level2.'">Logout</a>
                        </li>

                        <?php } ?>

                        <li class="nav-item px-2">
                            <a class="nav-link text-dark" href="/">
                                <img class="cart_img" src="'.$level1.'img/general_images/cart.png" alt="cart">
                            </a>
                        </li>
                        <li class="nav-item px-2">
                            <a class="nav-link text-dark" href="'.$level1.'php/user/profile.php?id='.$id.'">
                                <img class="cart_img" src="'.$level1.'img/user_images/default-user.jpg" alt="profile">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>';
}