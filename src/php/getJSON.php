<?php

// Receives a SQL query and returns a JSON-formatted result set

// Disable PHP warnings
error_reporting(0);

// Get query string
$queryString = $_POST['queryString'] ;

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

// Uncomment next line to debug queries
//mysqli_query($cxn, 'INSERT INTO lastmile_db.test (col_1) VALUES ("' . $queryString . '")');
// !!!!! create a "turn debug queries on/off" switch on DEQA !!!!!

// If there is a connection, run query
if ( !($cxn && $result = mysqli_query($cxn, $queryString)) ) {
    // Send error header to trigger AJAX error handler
    // !!!!! Can we send a "custom header" instead ?????
    header("HTTP/1.1 404 Not Found");
}

// Parse result into JSON
// !!!!! rewrite using mysqli_fetch_all
// !!!!! eg: echo '{"users": ' . json_encode(mysqli_fetch_all($result,MYSQLI_ASSOC)) . '}';
$i = 0;
$jsonString = "{";
while ($row = mysqli_fetch_assoc($result)) {
    if (json_encode($row,JSON_FORCE_OBJECT) <> "") {
        $jsonString .= '"' . $i . '": ';
        $jsonString .= json_encode($row,JSON_FORCE_OBJECT);
        $jsonString .= ', ';
        $i++;
    }
}

// Send JSON back to AJAX handlers
$jsonString = substr($jsonString,0,-2);
echo $jsonString . "}";

?>