<?php

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Extract POST vars
$input_username = $_POST['username'];
$input_password = $_POST['password'];

// Query password hash from database
require_once("cxn.php");
$query = "SELECT * FROM tbl_utility_users WHERE username = '$input_username'";
$result = mysqli_query($cxn, $query) or Header("Location:/LastMileData/index.php?retry=1");
$row = mysqli_fetch_assoc($result);

// Test credentials: success
if( $input_username <> " AND $input_password <> " AND sha1($input_password) == $row['password'] )
{
    // Start session; set $_SESSION vars
    Session_start() ;
    $_SESSION['username'] = $row['username'] ;
    $_SESSION['usertype'] = $row['usertype'] ;
    
    // Update login table; redirect to page_home
    updateLoginTable($input_username, $cxn) ;
    Header("Location:/LastMileData/src/pages/page_home.php") ;
}

// Test credentials: failure
else
{
    // Redirect to index; display "retry" message
    Header("Location:/LastMileData/index.php?retry=1") ;
}

function updateLoginTable($username, $cxn)
{
    // Set time zone; set loginTime
    date_default_timezone_set("America/New_York") ;
    $loginTime = date('Y-m-d H:i:s') ;
    
    // Run query to update login table
    $query = "INSERT INTO tbl_utility_logins (username, loginTime) VALUES ('$username', '$loginTime');" ;
    mysqli_query($cxn, $query) or die("failure") ;
}

?>