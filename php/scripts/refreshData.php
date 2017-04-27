<?php

// Downloads "system data" (e.g. most recent list of villges) from MySQL database and puts it into localStorage
// Userful for populating dynamic dropdown menus or comboboxes in paper data entry forms

// Disable PHP warnings
error_reporting(0);

// Set include path
set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes");

// Require connection strings
require_once("cxn.php");

// 1. Update "deqaUsers" (object)
//  Note that the LOCATE('admin',`userGroups`)>0 clause will pick up both "admin" and "superadmin"
$query = "SELECT username, password FROM lastmile_dataportal.tbl_utility_users WHERE LOCATE('admin',`userGroups`)>0 OR LOCATE('deqa',`userGroups`)>0;";
$result = mysqli_query($cxn, $query) or die("failure");
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    $json_deqaUsers[$row['username']] = $row['password'];
}

// 2. Update "facilities" (array)
$query = "SELECT healthFacility FROM lastmile_cha.healthfacility;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_facilities = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_facilities, $row['healthFacility']);
}

// 3. Update "districts" (array)
$query = "SELECT healthDistrict FROM lastmile_cha.healthdistrict WHERE (healthDistrictID BETWEEN 20 AND 32) OR (healthDistrictID=6);";
$result = mysqli_query($cxn, $query) or die("failure");
$json_districts = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_districts, $row['healthDistrict']);
}

// Return results
echo '{';
    echo '"deqaUsers":' . json_encode($json_deqaUsers) . ", ";
    echo '"facilities":' . json_encode($json_facilities) . ", ";
    echo '"districts":' . json_encode($json_districts);
echo '}';
