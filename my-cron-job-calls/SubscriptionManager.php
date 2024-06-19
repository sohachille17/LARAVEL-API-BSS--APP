<?php


$headers = array(
  'Accept: application/json',
  'Authorization: Bearer XXX'
);

$API_ENDPOINT = 'https://ssobloosat.com/api_bss_v3/public'.'/api/subscriptions';

/*
The First request below triggers the server to update subscriptions
that have an expired activity period to move thier status from ongoing to finished
*/

$curl1 = curl_init();

curl_setopt_array($curl1, array(
  CURLOPT_URL => "{$API_ENDPOINT}/update-expired-subscriptions",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => $headers,
));

$response1 = curl_exec($curl1);

curl_close($curl1);
echo $response1."<br>\r\n";


/*
The second request below triggers the server to update subscriptions
which have dates within the current period but are set as royalties to ongoing
*/


$curl2 = curl_init();

curl_setopt_array($curl2, array(
  CURLOPT_URL => "{$API_ENDPOINT}/update-royalty-subscriptions",
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
echo $response2."<br/>\r\n";

/*
The 3rd request below triggers the server to create royalty subscriptions for KAF
which have dates within the current period but are set as royalties to ongoing
*/


$curl3 = curl_init();

curl_setopt_array($curl3, array(
  CURLOPT_URL => "{$API_ENDPOINT}/renew-next-subscriptions-kaf",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER =>  $headers,
));

$response3 = curl_exec($curl3);

curl_close($curl3);
echo $response3."<br/>\r\n";


/*
The 4rd request below triggers the server to create royalty subscriptions for IWAY
which have dates within the current period but are set as royalties to ongoing
*/


$curl4 = curl_init();

curl_setopt_array($curl4, array(
  CURLOPT_URL => "{$API_ENDPOINT}/renew-next-subscriptions-iway",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER =>  $headers,
));

$response4 = curl_exec($curl4);

curl_close($curl4);
echo $response4."<br/>\r\n";

/*
The 5rd request below triggers the server to create royalty subscriptions for IWAY
which have dates within the current period but are set as royalties to ongoing
*/


$curl5 = curl_init();

curl_setopt_array($curl5, array(
  CURLOPT_URL => "{$API_ENDPOINT}/renew-next-subscriptions-bluestar",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER =>  $headers,
));

$response5 = curl_exec($curl5);

curl_close($curl5);
echo $response5."<br/>\r\n";


























































chdir("httpdocs/api_bss_v3/mods");
exec("mysqldump --user=userBloo23 --password=j55g?L7m1 --host=localhost oos_bloo > custom_file_".date('d-m-y').".gi");

