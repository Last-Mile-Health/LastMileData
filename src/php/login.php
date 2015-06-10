<?php

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Extract POST vars
$input_username = $_POST['username'];
$input_password = $_POST['password'];
$redirect = $_POST['redirect'];

// Query password hash from database
require_once("cxn.php");
$query = "SELECT * FROM lastmile_db.tbl_utility_users WHERE username = '$input_username'";
$result = mysqli_query($cxn, $query) or Header("Location:/LastMileData/index.php?retry=1");
$row = mysqli_fetch_assoc($result);

// Test credentials: success
if( $input_username <> " AND $input_password <> " AND sha1($input_password) == $row['password'] )
{
    // Start session; set $_SESSION vars
    Session_start() ;
    $_SESSION['username'] = $row['username'];
    $_SESSION['usertype'] = $row['usertype'];
    
    // Update login table
    updateLoginTable($input_username, $cxn);
    
    // Redirect
    if ( $redirect == "" ) {
        Header("Location:/LastMileData/src/pages/page_home.php");
    } else {
        Header("Location:" . $redirect);
    }
}

// Test credentials: failure
else
{
    // Redirect; display "retry" message
    if ( $redirect == "" ) {
        Header("Location:/LastMileData/index.php?retry=1");
    } else {
        Header("Location:/LastMileData/index.php?retry=1&redirect=" . urlencode($redirect));
    }
}

function updateLoginTable($username, $cxn)
{
    // Set time zone; set loginTime
    date_default_timezone_set("America/New_York");
    $loginTime = date('Y-m-d H:i:s');
    
    // Run query to update login table
    $query = "INSERT INTO lastmile_db.tbl_utility_logins (username, loginTime) VALUES ('$username', '$loginTime');";
    mysqli_query($cxn, $query) or die("failure");
}

?>