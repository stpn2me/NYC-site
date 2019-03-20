<?php

include "inc/db_config.php";

$ip = $_POST['ip'];
$port = $_POST['port'];

$select_query = sprintf("select * from tbl_node where ip_addr='%s' and port = '%s'", $ip, $port);
$ret = $mysqli->query($select_query);

$result = [
    'status' => false
];

if ($ret->num_rows > 0) {
    $result['status'] = true;
    $result['info'] = $ret->fetch_array();
}

echo json_encode($result);
