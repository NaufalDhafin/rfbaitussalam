<?php 
    date_default_timezone_set("Asia/Jakarta");
    $conf = mysqli_connect("localhost","root","","sedekah");
    // $website = $conf->query("SELECT * FROM website");
    
    // AUTH WITH GOOGLE
    $clientID               = "1095651048442-dacenji4r38lmj3kdtlk7v2thd3fm38d.apps.googleusercontent.com";
    $clientSecret           = "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";
    $create_userid          = "USR".random_int(111,999).date("dmYHis");
    $create_trxid_coin      = "CN".random_int(11,99).date("dmyHis");
    $create_trxid_sedekah   = "SDK".random_int(11,99).date("dmyHis");

    function rp($angka){
        $hasil_rupiah   = "Rp " . number_format($angka,2,',','.');
        return $hasil_rupiah;
    }
    $appname = "RF Baitussalam";
    $rpPerToken = (float)4050;
    //include "conf-admin.php";
    include "coin.php";
?>