<?php

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Get passed variables
$username = $_GET['username'];
$pw_old = $_GET['pw_old'];
$pw_new_1 = $_GET['pw_new_1'];
$pw_new_2 = $_GET['pw_new_2'];

// Run query to get current username, password, pk
require_once("cxn.php");
$query = "SELECT pk, username, password FROM tbl_utility_users WHERE username='$username';";
$result = mysqli_query($cxn, $query) or die("failure");
$row = mysqli_fetch_assoc($result);
extract($row);

// Check if old password is correct
if ( sha1($pw_old) == $password ) { $oldPasswordCorrect = true ; } else { $oldPasswordCorrect = false ; }

// Check if new passwords match
if ( ($pw_new_1 <> "") AND ($pw_new_1 == $pw_new_2) ) { $newPasswordsMatch = true ; } else { $newPasswordsMatch = false ; }

// Assign "old password bad" result code
if ($oldPasswordCorrect <> true) { $result_code = "old_pw_bad"; }

// Assign "new passwords don't match" result code
elseif ($newPasswordsMatch <> true) { $result_code = "new_pws_dont_match"; }

// Success handler
elseif ( $oldPasswordCorrect AND $newPasswordsMatch )
{
    // Generate hashed password (sha1)
    $hashedPassword = sha1($pw_new_1);
    
    // Run query to update password
    $query = "UPDATE tbl_utility_users SET password='$hashedPassword' WHERE pk='$pk'";
    mysqli_query($cxn, $query) or $result_code = "error";
    
    // Assign "success" result code
    $result_code = "pw_success";
}

// Assign "error" result code
else { $result_code = "error"; }

// Return result code (used by ajax success callback)
echo json_encode($result_code);

?>