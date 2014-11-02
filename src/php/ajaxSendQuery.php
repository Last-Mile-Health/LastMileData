<?php

// Receives data from sendRecords.js and sends it to server

// Disable PHP warnings
error_reporting(0);

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

// Get query string and rKey (record identifier)
$queryString = $_POST['queryString'] ;
$rKey = $_POST['rKey'] ;

// Run query
if ( $result = mysqli_query($cxn, $queryString) ) {
    // Send JSON back to AJAX success handler
    echo '{"rKeyDelete":"' . $rKey . '"}';
}
else {
    // Send error header to trigger AJAX error handler
    header("HTTP/1.1 404 Not Found");
}

?>