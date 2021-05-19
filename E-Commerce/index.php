<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <?php require_once 'php/components/boot.php'?>
        <link rel="stylesheet" href="style/main-style.css" />
    </head>
    <body>
        <?php require_once 'php/components/header.php'?>
        <div class="container my-5 p-md-5">   
            <div class="row px-md-5">
                <div class="py-5 col-12 col-lg-6">
                    <div class="text-uppercase banner_text fw-bold">Find your <br><span class="my_text_maincolor">perfect gift</span> <br>here</div>
                    <div class="my_text_lightgray my-4">More than 10 000 products to buy from home now</div>
                    <div class="btn bg_gray rounded-pill my-4 col-12 col-md-6 py-3">
                        <a href="php/product/product-catalog.php" class="text-white">To all products</a>
                    </div>
                </div>
                <div class="py-5 col-12 col-lg-6 ">
                    <div class="row">
                        <img src="img/general_images/banner.png" alt="">
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row my-4">
                <div class="col-12 text-center fs_6 text-uppercase my-2">Our bestsellers. <span class="my_text_maincolor">Discover more</span></div>
                <div class="col-12 text-center my_text_lightgray">Find thing you'll love</div>
            </div>
        </div>

        <div class="row row_width py-5">
            <div class="col-12 col-md-4 col-lg-2 py-2 box_height">
                <a href="/">
                    <div class="square">
                        <img class="content" src="img/general_images/img1.jpg" alt="">
                    </div>
                    <div class="row py-3 text-center">
                        <div class="col-12 fs-5 my-2">Simple red lipstick</div>
                        <a href="/" class="col-12 my-1 my_text_maincolor">cosmetics</a>
                        <a href="/" class="col-12 my-1">LilySeller</a>
                        <div class="col-12 fw-bold my-3">€19.60 <span class="my_text_maincolor">(-25%)</span></div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4 col-lg-2 py-2 box_height">
                <a href="/">
                    <div class="square">
                        <img class="content" src="img/general_images/img2.jpg" alt="">
                    </div>
                    <div class="row py-3 text-center">
                        <div class="col-12 fs-5 my-2">Rolex watch</div>
                        <a href="/" class="col-12 my-1 my_text_maincolor">watches</a>
                        <a href="/" class="col-12 my-1">Harry Potter</a>
                        <div class="col-12 fw-bold my-3">€20.30</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4 col-lg-2 py-2 box_height">
                <a href="/">
                    <div class="square">
                        <img class="content" src="img/general_images/img3.jpg" alt="">
                    </div>
                    <div class="row py-3 text-center">
                        <div class="col-12 fs-5 my-2">To buy now</div>
                        <a href="/" class="col-12 my-1 my_text_maincolor">tecnics</a>
                        <a href="/" class="col-12 my-1">John Snow</a>
                        <div class="col-12 fw-bold my-3">€34.50 <span class="my_text_maincolor">(-25%)</span></div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4 col-lg-2 py-2 box_height">
                <a href="/">
                    <div class="square">
                        <img class="content" src="img/general_images/img4.jpg" alt="">
                    </div>
                    <div class="row py-3 text-center">
                        <div class="col-12 fs-5 my-2">Something</div>
                        <a href="/" class="col-12 my-1 my_text_maincolor">home & garden</a>
                        <a href="/" class="col-12 my-1">Lord of the Rings</a>
                        <div class="col-12 fw-bold my-3">€38.50</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4 col-lg-2 py-2 box_height">
                <a href="/">
                    <div class="square">
                        <img class="content" src="img/general_images/img5.jpg" alt="">
                    </div>
                    <div class="row py-3 text-center">
                        <div class="col-12 fs-5 my-2">Best product ever</div>
                        <a href="/" class="col-12 my-1 my_text_maincolor">cosmetics</a>
                        <a href="/" class="col-12 my-1">Someone</a>
                        <div class="col-12 fw-bold my-3">€2.50</div>
                    </div>
                </a>
            </div>
            <div class="col-12 col-md-4 col-lg-2 py-2 box_height">
                <a href="/">
                    <div class="square">
                        <img class="content" src="img/general_images/img6.jpg" alt="">
                    </div>
                    <div class="row py-3 text-center">
                        <div class="col-12 fs-5 my-2">Lorem ipsum</div>
                        <a href="/" class="col-12 my-1 my_text_maincolor">tecnics</a>
                        <a href="/" class="col-12 my-1">LilySeller</a>
                        <div class="col-12 fw-bold my-3">€2.50 <span class="my_text_maincolor">(-25%)</span></div>
                    </div>
                </a>
            </div>
        </div>
        <?php require_once 'php/components/footer.php'?>
        <?php require_once 'php/components/boot-javascript.php'?>
    </body>
</html>