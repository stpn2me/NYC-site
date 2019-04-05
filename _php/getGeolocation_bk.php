<?php

ini_set("allow_url_fopen", 1);

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

include "inc/db_config.php";

function getGeoLocationInfo($ip_addr) {

    $context = stream_context_create(array('http' => array(
        'timeout' => 1.0,
        'ignore_errors' => true,
    )));

    $query = unserialize(file_get_contents('http://ip-api.com/php/' . $ip_addr, false, $context));
    usleep(500 * 1000);
    // var_dump($query); exit;

    if($query && $query['status'] == 'success') {

        return $query;

    } else {

        return null;

    }

}

$url = 'https://explorer.nycoin.info/api/getpeerinfo';

$ch = curl_init();

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

curl_setopt($ch, CURLOPT_URL, $url);

$result = curl_exec($ch);

curl_close($ch);


$obj = json_decode($result);
// var_export($obj);

if (is_array($obj) || is_object($obj))

{
    $syn_flag = true;

    $delete_query = "delete from tbl_node";

    $mysqli->query($delete_query);

    foreach ($obj as $item) {

        //if (!$item->inbound) continue;
        $ip_addr = '';
        preg_match_all('/\[([^\]]*)\]/', $item->addr, $matches);
        if (empty($matches[1])) {
            $addr = explode(':', $item->addr);
            $ip_addr = $addr[0];
        } else {
            $ip_addr = $matches[1][0];
        }
//        var_export($ip_addr);

        $port = strrpos($item->addrlocal, ':');
        if ($port !== false) $port = substr($item->addrlocal, $port + 1);
//        var_export($port);

        $geo_info = getGeoLocationInfo($ip_addr);
//        var_export($geo_info);
        if($geo_info != null) {
            $subver = str_replace('/', '', $item->subver);
            $now_time = date("Y-m-d H:i:s");
            $inbound = $item->inbound == true ? 1: 0;
            $insert_query = sprintf("insert into tbl_node(id, ip_addr, port, city, country_name, country_code, region, version, lat, lon, last_update, inbound)
                VALUES(NULL, '%s', '%s' ,'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d)",
                $ip_addr, $port, $geo_info['city'], $geo_info['country'], $geo_info['countryCode'], $geo_info['regionName'],
                $subver, $geo_info['lat'], $geo_info['lon'], $now_time, $inbound);

//            var_export($insert_query);
            $mysqli->query($insert_query);
        } else {
            continue;
        }

    }
}

$url1 = 'https:/nodes.nycoin.info/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url1);
$result1 = curl_exec($ch);
curl_close($ch);

$result1 = trim($result1);
$result1 = preg_split('/\r\n|\r|\n/', $result1);
// var_export($result1);

for ($i = 0; $i < count($result1); $i++) {
    if ($i == 0) continue;

    $item = $result1[$i];
    $item = preg_split('/\s+/', $item);
    //    var_export($item);

    $address = explode(':', $item[0]);
    $ip = $address[0];
    $port = $address[1];

    $val_2h = $item[3];
    $val_8h = $item[4];
    $val_1d = $item[5];
    $val_7d = $item[6];
    $val_30d = $item[7];
    $blocks = $item[8];
    $ver = $item[11];
    //    $inbound = $item[1];
    $inbound = 0;

    $select_query = sprintf("select * from tbl_node where ip_addr='%s' and port = '%s'", $ip, $port);
    $ret = $mysqli->query($select_query);
    $query = '';

//    echo ('**************');
//    echo ('select query:' . $select_query);
//    echo ('**************');
//    var_export($ret->num_rows);

    if ($ret->num_rows > 0) {
        $row = $ret->fetch_array();
        $query = sprintf("update tbl_node set 2h='%s', 8h='%s', 1d='%s', 7d='%s', 30d='%s', blocks='%s' where id='%s'",
            $val_2h, $val_8h, $val_1d, $val_7d, $val_30d, $blocks, $row['id']);
    } else {
        $geo_info = getGeoLocationInfo($ip);
        if($geo_info != null) {
            $subver = str_replace(str_split('/"'), '', $ver);
            $now_time = date("Y-m-d H:i:s");
            //            $inbound = $item->inbound == true ? 1: 0;
            $query = sprintf("insert into tbl_node VALUES(NULL, '%s', '%s' ,'%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', %d,
                   '%s', '%s' ,'%s', '%s', '%s', '%s')",
                $ip, $port, $geo_info['city'], $geo_info['country'], $geo_info['countryCode'], $geo_info['regionName'],
                $subver, $geo_info['lat'], $geo_info['lon'], $now_time, $inbound,
                $val_2h, $val_8h, $val_1d, $val_7d, $val_30d, $blocks
            );
        } else {
            continue;
        }
    }

    if ($query != '') $mysqli->query($query);
}

$syn_flag = false;