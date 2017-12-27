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
//  Note that the LOCATE('admin',`user_groups`)>0 clause will pick up both "admin" and "superadmin"
$query = "SELECT username, password FROM lastmile_dataportal.tbl_utility_users WHERE LOCATE('admin',`user_groups`)>0 OR LOCATE('deqa',`user_groups`)>0;";
$result = mysqli_query($cxn, $query) or die("failure");
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    $json_deqaUsers[$row['username']] = $row['password'];
}

// 2. Update "facilities" (array)
$query = "SELECT health_facility FROM lastmile_cha.health_facility ORDER BY health_facility ASC;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_facilities = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_facilities, $row['health_facility']);
}

// 3. Update "health_facility_id" (array)
$query = "SELECT health_facility_id FROM lastmile_cha.health_facility where not ( health_facility_id like 'Rivercess' OR health_facility_id like 'Grand Gedeh' ) ORDER BY health_facility_id ASC;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_health_facility_id = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_health_facility_id, $row['health_facility_id']);
}

// 4. Update "health_facility_id_concat" (array)
$query = "SELECT concat( health_facility, ' (', health_facility_id, ')' ) as health_facility_id_concat FROM lastmile_cha.health_facility where not ( ( health_facility_id like 'Rivercess' ) OR ( health_facility_id like 'Grand Gedeh' ) OR ( health_facility_id like 'Grand Bassa' ) ) ORDER BY health_facility ASC;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_health_facility_id_concat = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_health_facility_id_concat, $row['health_facility_id_concat']);
}

// 5. Update "districts" (array)
$query = "SELECT health_district FROM lastmile_cha.health_district WHERE (health_district_id BETWEEN 20 AND 32) OR (health_district_id=6)";
$result = mysqli_query($cxn, $query) or die("failure");
$json_districts = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_districts, $row['health_district']);
}

// 6. Update "county" (array)
$query = "SELECT county FROM lastmile_cha.county WHERE ( ( county_id = 6 ) OR ( county_id = 14) ) ORDER BY county ASC;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_county = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_county, $row['county']);
}

// 7. Update "chss" (array)
$query = "SELECT CONCAT(first_name,' ',last_name) AS chss FROM lastmile_cha.view_position_chss_person WHERE person_id IS NOT NULL ORDER BY chss ASC;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_chss = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_chss, $row['chss']);
}

// 8. Update "chss_id_concat" (array)
$query = "SELECT CONCAT( first_name,' ',last_name, ' (', person_id, ')' ) AS chss_id_concat FROM lastmile_cha.view_position_chss_person WHERE person_id IS NOT NULL ORDER BY chss_id_concat ASC;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_chss_id_concat = array();
for ($i = 1; $i <= mysqli_num_rows($result); $i++) {
    $row = mysqli_fetch_assoc($result);
    array_push($json_chss_id_concat, $row['chss_id_concat']);
  }

// Return results
echo '{';
    echo '"deqaUsers":'                     . json_encode($json_deqaUsers)                  . ", ";
    echo '"facilities":'                    . json_encode($json_facilities)                 . ", ";
    echo '"health_facility_id":'            . json_encode($json_health_facility_id)         . ", ";
    echo '"health_facility_id_concat":'     . json_encode($json_health_facility_id_concat)  . ", ";
    echo '"districts":'                     . json_encode($json_districts)                  . ", ";
    echo '"county":'                        . json_encode($json_county)                     . ", ";
    echo '"chss":'                          . json_encode($json_chss)                       . ", ";
    echo '"chss_id_concat":'                . json_encode($json_chss_id_concat);
echo '}';
