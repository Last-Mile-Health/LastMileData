<?php

// Receives data from deqa.js (via "Send records" button on page_deqa.html) and sends data to MySQL database on server

// Disable PHP warnings
error_reporting(0);

// Get query string, rKey (record identifier), and queryDebugging flag
$queryString = $_POST['queryString'];
$rKey = $_POST['rKey'];
$transaction = $_POST['transaction'];
$queryDebugging = isset($_POST['queryDebugging']) ? $_POST['queryDebugging'] : 'false';

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

// Debug queries with PHP Console (uncomment to use)
//require_once("../includes/PhpConsole.phar");
//$handler = PhpConsole\Handler::getInstance();
//$handler->start();
//$handler->debug($queryString);

// Debug queries
if ($queryDebugging=='true') {
    mysqli_query($cxn, 'INSERT INTO lastmile_dataportal.tbl_utility_dataUploadDebugging (queryString) VALUES ("' . mysqli_real_escape_string($cxn,$queryString) . '")');
}

if ($transaction) {
    
    // Break longer query strings down to individual queries to send together in a transaction
    $queryPieces = explode(";",$queryString);
    $all_query_ok=true;
    mysqli_autocommit($cxn,FALSE);

    for ($i=0;$i<count($queryPieces);$i++) {
        if ($queryPieces[$i]<>'') {
            mysqli_query($cxn, $queryPieces[$i]) ? null : $all_query_ok=false;
        }
    }
    
    // If all queries pass, commit the transaction
    if($all_query_ok) {
        mysqli_commit($cxn);
    } else {
        mysqli_rollback($cxn);
        // Send error header to trigger AJAX error handler
        echo '{"rKeyAJAX":"nope"}'; // !!!!!
//        header("HTTP/1.1 404 Not Found");
        
    };
    
} else {
    // If there is a connection, run query
    if ( !($cxn && mysqli_query($cxn, $queryString)) ) {
        // Send error header to trigger AJAX error handler
        // !!!!! Can we send a "custom header" instead ?????
        header("HTTP/1.1 404 Not Found"); // !!!!! change this to 500 / Internal server error !!!!!
    }
}

mysqli_autocommit($cxn,TRUE); // !!!!! Is this necessary ?????
mysqli_close($cxn);

// Send JSON back to AJAX handlers
echo '{"rKeyAJAX":"' . $rKey . '"}';