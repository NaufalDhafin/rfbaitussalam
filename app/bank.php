<?php 
    $curlBank = curl_init();
    curl_setopt_array($curlBank, array(
      CURLOPT_URL => 'http://localhost/sedekah/app/bank.json',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $bank = curl_exec($curlBank);
?>