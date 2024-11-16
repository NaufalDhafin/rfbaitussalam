<?php
    $coinUser       = $conf->query("SELECT SUM(coin) FROM users");
    $qremainCoin    = $conf->query("SELECT * FROM coin");
    $rremainCoin    = $qremainCoin->fetch_array();
    $totalUsedCoin  = $coinUser->fetch_array();
?>