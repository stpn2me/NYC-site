<?php


include "inc/db_config.php";

$ret = [

    'fullNodeCount' => -1,
    'totalCount' => -1,
    'item' => [],
    'status' => false
];

if ($syn_flag == true) {
    echo json_encode($ret);
    die(0);
}

$ret['status'] = true;

$base_flag_url = '../images/_flags/64x64/';

$limit = 10;



$query = "SELECT *, COUNT(country_code) country_count FROM tbl_node 

            GROUP BY country_code ORDER BY country_count DESC LIMIT 0, ".$limit;

if ($result = $mysqli->query($query)){



    /* fetch object array */

    $i = 0;

    while ($obj = $result->fetch_object()) {

        $item = [

            'rank' => $i + 1,

            'country' => strtoupper($obj->country_name),

            'count' => $obj->country_count,

            'flag' => $base_flag_url.strtolower($obj->country_code).'.png'

        ];

        $ret['item'][] = $item;

        $i++;

    }

}





$select_query = "select * from tbl_node where 1";

if ($result = $mysqli->query($select_query)) {

    $cnt = $result->num_rows;

    $ret['totalCount'] = $cnt;

}

$select_query = "select * from tbl_node where inbound = 1";

if ($result = $mysqli->query($select_query)) {

    $cnt = $result->num_rows;

    $ret['fullNodeCount'] = $cnt;

}

echo json_encode($ret);
