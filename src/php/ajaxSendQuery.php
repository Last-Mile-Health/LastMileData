<?php

// Receives data from sendRecords.js and sends it to server

// Disable PHP warnings
error_reporting(0);

// Get query string and rKey (record identifier)
$queryString = $_POST['queryString'] ;
$rKey = $_POST['rKey'] ;

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

// Uncomment next line to debug queries
//mysqli_query($cxn, 'INSERT INTO test (col_1) VALUES ("' . $queryString . '")');
// !!!!! create a "turn debug queries on/off" configuration switch on DEQA !!!!!

// If there is a connection, run query
if ( !($cxn && $result = mysqli_query($cxn, $queryString)) ) {
    // Send error header to trigger AJAX error handler
    // !!!!! Can we send a "custom header" instead ?????
    header("HTTP/1.1 404 Not Found");
}

// Send JSON back to AJAX handlers
echo '{"rKeyAJAX":"' . $rKey . '"}';

?>