<?php
    include "vendor/autoload.php";
    $google_client = new Google_Client();
    $google_client->setClientId($clientID);
    $google_client->setClientSecret($clientSecret);
    $google_client->setRedirectUri('http://localhost/sedekah/login.php');
    $google_client->addScope('email');
    $google_client->addScope('profile');
?>