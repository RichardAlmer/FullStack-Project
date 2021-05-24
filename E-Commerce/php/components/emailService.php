<?php
function curl_get($url)
{
    //Initializes a new CURL and prepares for next step
    $client = curl_init($url);

    //Set an option for a CURL transfer
    curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

    //it executes the CURL and return a string
    $response = curl_exec($client);

    //Closes a CURL session and frees all resources.
    curl_close($client);

    return $response;
}

function purchaseNotification($purchaseId)
{
    $url = "https://obermayer.codefactory.live/emailAPI.php?id=" . $purchaseId . "";

    $jsonStrg = curl_get($url);
    return var_dump($jsonStrg);
    // $jsonData = json_decode($jsonStrg, true); ------------------------------> No response object NULL when json decode
    // return $response;
}

// purchaseNotification(1);
?>