<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <?php require_once 'components/boot.php' ?>
    <style>
        #map {
            height: 400px;
            width: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Contact</h1>
        <div id="map"></div>
        <div>
            <p><b>Address:</b><br>Kettenbrückengasse 23 / 2 / 12, 1050 Wien</p>
            <p><b>Phone:</b><br>012353213</p>
            <p>You can also send us a message</p>
        </div>
        <form>
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Massage</label>
                <input type="text" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
    <script>
        var map;
        function initMap() {
            var location = {
                lat: 48.19646,
                lng: 16.35949
            };
            map = new google.maps.Map(document.getElementById('map'), {
                center: location,
                zoom: 14
            });
            var pinpoint = new google.maps.Marker({
                position: location,
                map: map
            });
        };
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtjaD-saUZQ47PbxigOg25cvuO6_SuX3M&callback=initMap" async defer></script>
</body>
</html>