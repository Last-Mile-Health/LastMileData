<?php

// Downloads "system data" (e.g. most recent list of villges) from MySQL database and puts it into localStorage

// Disable PHP warnings
error_reporting(0);

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Require connection strings
require_once("cxn.php");

// 1. Update "deqaUsers" (object)
$query = "SELECT username, password FROM tbl_utility_users WHERE usertype='admin' OR usertype='deqa'";
$result = mysqli_query($cxn, $query) or die("failure");
for ($i=1;$i<=mysqli_num_rows($result);$i++)
{
    $row = mysqli_fetch_assoc($result);
    extract($row);
    $json_deqaUsers[$username]=$password;
}

// 2. Update "villages" (array)
$query = "SELECT villageName FROM tbl_data_village WHERE 1";
$result = mysqli_query($cxn, $query) or die("failure");
$json_villages = array();
for ($i=1;$i<=mysqli_num_rows($result);$i++)
{
    $row = mysqli_fetch_assoc($result);
    extract($row);
    array_push($json_villages,$villageName);
}

// !!!!! build error handler !!!!!

// Return results
echo '{' ;
echo '"deqaUsers":' . json_encode($json_deqaUsers) . ", " ;
echo '"villages":' . json_encode($json_villages) ;
echo '}' ;

?>