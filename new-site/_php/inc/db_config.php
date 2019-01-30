<?php

// error_reporting(-1);
// ini_set('display_errors', 1);

$server = "localhost";
$dbusername = "cruiz";
$dbpassword = "P@ssw0rd";
$database = "admin_nodesdb";
$syn_flag = false;

$mysqli = new mysqli($server, $dbusername, $dbpassword, $database);