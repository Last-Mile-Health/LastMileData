<?php

// URL: localhost/LastMileData/php/scripts/call_dataPortalValues.php?month=X&year=X

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );

// Increase maximum execution time
set_time_limit(600);

// Get parameters
$month = $_GET['month'];
$year = $_GET['year'];

// Run query
require_once("cxn.php");
$query = "CALL `lastmile_dataportal`.`dataPortalValues`($month, $year);";
$result = mysqli_query($cxn, $query) or die("failure");

// Display result
echo $result;
