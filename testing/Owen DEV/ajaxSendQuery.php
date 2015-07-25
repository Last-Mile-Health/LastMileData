<?php

// Receives data from sendRecords.js and sends it to server

// Disable PHP warnings
error_reporting(0);

// Get query string and rKey (record identifier)
$queryString = $_POST['queryString'];
$rKey = $_POST['rKey'];
$transaction = $_POST['transaction'];

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

// Uncomment next line to debug queries
//mysqli_query($cxn, 'INSERT INTO lastmile_db.test (col_1) VALUES ("' . $queryString . '")');
mysqli_query($cxn, 'INSERT INTO lastmile_chwdb.test (col_1) VALUES ("' . $queryString . '")');

// !!!!! create a "turn debug queries on/off" configuration switch on DEQA !!!!!


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
        header("HTTP/1.1 404 Not Found");
        
    };
    
} else {
    // If there is a connection, run query
    if ( !($cxn && mysqli_query($cxn, $queryString)) ) { // !!!!! $result var is unnecessary !!!!!
        // Send error header to trigger AJAX error handler
        // !!!!! Can we send a "custom header" instead ?????
        header("HTTP/1.1 404 Not Found");
    }
}

mysqli_autocommit($cxn,TRUE); // !!!!! Is this necessary ?????
mysqli_close($cxn);

// Send JSON back to AJAX handlers
echo '{"rKeyAJAX":"' . $rKey . '"}';

?>