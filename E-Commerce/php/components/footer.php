<?php 
function footer($level=""){
echo '
<footer class="bg_gray py-5">
    <div class="container py-5">
        <div class="row justify-content-between">
            <div class="col-12 col-lg-4 mb-5 mb-lg-0 text-center text-lg-start">
                <img class="logo col-auto" src="'.$level.'img/general_images/logo-white.png" alt="">
            </div>

            <div class="col-12 col-lg-8 text-center text-lg-end">
                <div class="row">
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <a class="px-3 text-white text-center text-md-end" href="'.$level.'html/imprint.html">Impressum</a>
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <a class="px-3 text-white text-center text-md-end" href="'.$level.'html/dataProtection.html">Data-Protection</a>
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <a class="px-3 text-white text-center text-md-end" href="#">About us</a>
                    </div>
                    <div class="col-12 col-md-3 mb-2 mb-md-0">
                        <a class="px-3 text-white text-center text-md-end" href="'.$level.'php/contact.php">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center text-white">&copy; '. date("Y") .'</div>;
</footer>';
}


