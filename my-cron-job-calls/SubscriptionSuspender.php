<?php

$headers = array(
  'Accept: application/json',
  'Authorization: Bearer 11|NJcsuKpqH5f56KAkdfOINuEtI2zpv2nxCCWjYJEd'
);

$API_ENDPOINT = 'https://ssobloosat.com/api_bss_v3/public'.'/api/subscriptions';


$curl2 = curl_init();

curl_setopt_array($curl2, array(
  CURLOPT_URL => "{$API_ENDPOINT}/check-unpaid-ongoing-and-suspend-kaf",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => $headers,
));

$response2 = curl_exec($curl2);

curl_close($curl2);

echo"-------------------------------------------------------------------------------\r\n";
echo"updating royalty subscriptions that are suposed to be ongoing\r\n";
echo"-------------------------------------------------------------------------------\r\n";
echo $response2."<br/>\r\n";
echo"-------------------------------------------------------------------------------\r\n";


