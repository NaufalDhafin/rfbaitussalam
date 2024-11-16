<?php 
    $apiKey = 'DEV-WeLRMjIYnGObKXjG2Nnc3W87DToLQsbjDyrPxOGZ';

    $payload = [
        'code' => 'BRIVA',
        'allow_html' => 1
    ];
    
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_FRESH_CONNECT  => true,
        CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/payment/instruction?'.http_build_query($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
        CURLOPT_FAILONERROR    => false,
        CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
    ]);
    
    $response = curl_exec($curl);
    $json     = json_decode($response, true);
    foreach($json['data'] AS $data){
        echo "Judul: ".$data['title']."<br>";
        foreach($data['steps'] AS $steps){
            echo " ".$steps."<br>";
        }
        echo "<br><br>";
    }
?>