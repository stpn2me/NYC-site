<?php

// ini_set('display_errors', 0);
// error_reporting(E_ERROR | E_PARSE);

include "inc/db_config.php";

$ret = [
    "type" => "FeatureCollection",
    "name" => "current_nodes_geo_1",
    "crs" => ["type" => "name", "properties" =>  ["name" => "urn:ogc:def:crs:OGC:1.3:CRS84"]],
    "features" => [],
];

$select_query = "select * from tbl_node where 1";
if ($result = $mysqli->query($select_query)){

    /* fetch object array */
    while ($obj = $result->fetch_object()) {
        $geo_item = [
            "type" => "Feature",
            "properties" => ["IP"=> $obj->ip_addr, "City"=> $obj->city,
                "Country" => $obj->country_name, "Region" => $obj->region, "Version" => $obj->version, "Inbound" => $obj->inbound],
            "geometry" => ["type" => "Point", "coordinates" => [$obj->lon, $obj->lat]],
        ];

        array_push($ret['features'], $geo_item);
    }
}

echo json_encode($ret);