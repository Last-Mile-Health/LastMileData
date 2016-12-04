<?php

// Downloads "system data" (e.g. most recent list of villges) from MySQL database and puts it into localStorage
// Userful for populating dynamic dropdown menus or comboboxes in paper data entry forms

// Disable PHP warnings
error_reporting(0);

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );

// Require connection strings
require_once("cxn.php");

// 1. Update "deqaUsers" (object)
//  Note that the LOCATE('admin',`userGroups`)>0 clause will pick up both "admin" and "superadmin"
$query = "SELECT username, password FROM lastmile_dataportal.tbl_utility_users WHERE LOCATE('admin',`userGroups`)>0 OR LOCATE('deqa',`userGroups`)>0;";
$result = mysqli_query($cxn, $query) or die("failure");
for ($i=1;$i<=mysqli_num_rows($result);$i++)
{
    $row = mysqli_fetch_assoc($result);
    extract($row);
    $json_deqaUsers[$username]=$password;
}

// 2. Update "villages" (array)
$query = "SELECT `name` FROM lastmile_chwdb.admin_community WHERE archived<>1;";
$result = mysqli_query($cxn, $query) or die("failure");
$json_villages = array();
for ($i=1;$i<=mysqli_num_rows($result);$i++)
{
    $row = mysqli_fetch_assoc($result);
    extract($row);
    array_push($json_villages,$villageName);
}

// 3. Update "FHWs" (array)
$query = "SELECT staffName FROM lastmile_chwdb.view_staffPosition WHERE title='CHW';";
$result = mysqli_query($cxn, $query) or die("failure");
$json_fhws = array();
for ($i=1;$i<=mysqli_num_rows($result);$i++)
{
    $row = mysqli_fetch_assoc($result);
    extract($row);
    array_push($json_fhws,$staffName);
}

// !!!!! build error handler; this results in an infinite loop if any of the above queries fail !!!!!

// Return results
echo '{' ;
echo '"deqaUsers":' . json_encode($json_deqaUsers) . ", " ;
echo '"villages":' . json_encode($json_villages) . ", " ;
echo '"fhws":' . json_encode($json_fhws) ;
echo '}' ;
