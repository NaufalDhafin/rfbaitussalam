<?php

$apiKey = 'DEV-WeLRMjIYnGObKXjG2Nnc3W87DToLQsbjDyrPxOGZ';

$channel_curl = curl_init();

curl_setopt_array($channel_curl, array(
  CURLOPT_FRESH_CONNECT  => true,
  CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/merchant/payment-channel',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_HEADER         => false,
  CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
  CURLOPT_FAILONERROR    => false,
  CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
));

$channel_response = curl_exec($channel_curl);
?>