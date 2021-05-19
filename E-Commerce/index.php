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
        <div class="container my-5 p-5">   
            <div class="row px-md-5">
                <div class="py-5 col-12 col-lg-6 ">
                    <div class="text-uppercase banner_text fw-bold">Find your <br><span class="my_text_maincolor">perfect gift</span> <br>here</div>
                    <div class="my_text_lightgray my-4">More than 10 000 products to buy from home now</div>
                    <div class="btn bg_gray rounded-pill my-4 col-12 col-md-6 py-3">
                        <a href="/" class="text-white">To all products</a>
                    </div>
                </div>
                <div class="py-5 col-12 col-lg-6 ">
                    <div class="row">
                        <img src="img/general_images/banner.png" alt="">
                    </div>
                </div>
            </div>
        </div>

        <?php require_once 'php/components/footer.php'?>
        <?php require_once 'php/components/boot-javascript.php'?>
    </body>
</html>