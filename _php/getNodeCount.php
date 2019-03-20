<?php

$url = 'https://explorer.nycoin.info/api/getconnectioncount';
$count = 0;

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);

$obj = json_decode($result);
if (isset($obj)) {
    $count = $obj;
}

echo $count;