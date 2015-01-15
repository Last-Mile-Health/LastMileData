<?php

// For both XAMPP localhost and GoDaddy localhost
$host = "localhost"; $user = "lastmile_admin"; $password = "LastMile14"; $db = "lastmile_db";

// Set connection variable
// !!!!! Handle errors differently (test for no DB access); set warnings off !!!!!
// !!!!! Need global configuration file !!!!!
$cxn = mysqli_connect($host,$user,$password,$db) or die("Error " . mysqli_error($cxn));

?>