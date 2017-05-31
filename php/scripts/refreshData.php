<?php

// Downloads "system data" (e.g. most recent list of villges) from MySQL database and puts it into localStorage
// Userful for populating dynamic dropdown menus or comboboxes in paper data entry forms
// Note: if there is an error in this query, this will throw the DEQA page in an infinite "refresh system data" loop 

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
$query = "SELECT health_facility FROM lastmile_cha.health_facility order by health_facility asc;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_facilities = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_facilities, $row['health_facility']);
}

// 3. Update "districts" (array)
$query = "SELECT health_district FROM lastmile_cha.health_district WHERE (health_district_id BETWEEN 20 AND 32) OR (health_district_id=6)";
$result = mysqli_query($cxn, $query) or die("failure");
$json_districts = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_districts, $row['health_district']);
}

// 4. Update "county" (array)
$query = "SELECT county FROM lastmile_cha.county WHERE ( ( county_id = 6 ) or ( county_id = 14) ) order by county asc;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_county = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_county, $row['county']);
}

// 5. Update "chss" (array)
$query = "select chss from lastmile_dataportal.view_php_refresh_data_chss order by chss asc;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_chss = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_chss, $row['chss']);
}

// Return results
echo '{';
    echo '"deqaUsers":'     . json_encode($json_deqaUsers)  . ", ";
    echo '"facilities":'    . json_encode($json_facilities) . ", ";
    echo '"districts":'     . json_encode($json_districts)  . ", ";
    echo '"county":'        . json_encode($json_county)     . ", ";
    echo '"chss":'          . json_encode($json_chss);
echo '}';
