<?php 
    date_default_timezone_set("Asia/Jakarta");
    $conf = mysqli_connect("localhost","rfbp5717_sdk","!RFbaitussalam","rfbp5717_sdk");
    // $website = $conf->query("SELECT * FROM website");
    $appname = "RF Baitussalam";
    
    // AUTH WITH GOOGLE
    $clientID               = "1095651048442-dacenji4r38lmj3kdtlk7v2thd3fm38d.apps.googleusercontent.com";
    $clientSecret           = "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";
    $create_userid          = "USR".random_int(111,999).date("dmYHis");
    $create_trxid_coin      = "CN".random_int(11,99).date("dmyHis");
    $create_trxid_sedekah   = "SDK".random_int(11,99).date("dmyHis");

    function rp($angka){
        $hasil_rupiah   = "Rp " . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }

    $rpPerToken = (float)5050;
    include "coin.php";
?>