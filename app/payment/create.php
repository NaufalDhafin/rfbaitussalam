<?php 
    function createTransaction($apiKey, $privateKey, $merchantCode, $method, $merchantRef, $amount, $customer_name, $customer_email, $customer_phone, $category){
        $data = [
            'method'         => $method,
            'merchant_ref'   => $merchantRef,
            'amount'         => (int)$amount,
            'customer_name'  => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'order_items'    => [
                [
                    'sku'         => $merchantRef,
                    'name'        => $category,
                    'price'       => (int)$amount,
                    'quantity'    => 1
                ]
            ],
            'return_url'   => 'http://localhost/sedekah/dashboard/',
            'expired_time' => (time() + (24 * 60 * 60)), // 24 jam
            'signature'    => hash_hmac('sha256', $merchantCode.$merchantRef.$amount, $privateKey)
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => 'https://tripay.co.id/api-sandbox/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => ['Authorization: Bearer '.$apiKey],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ]);
        
        $response = curl_exec($curl);
        return $response;
    }

?>