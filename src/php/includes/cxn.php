<?php

// For both XAMPP localhost and GoDaddy localhost
$host = "localhost"; $user = "lastmile_admin"; $password = "LastMile14"; $db = "lastmile_db";
//$host = "localhost"; $user = "root"; $password = "LastMile14"; $db = "lastmile_db"; // !!!!! DEBUG; not secure !!!!!

// Set connection variable
// !!!!! Handle errors differently (test for no DB access); set warnings off !!!!!
// !!!!! Need global configuration file !!!!!
$cxn = mysqli_connect($host,$user,$password,$db) or die("failure") ;

?>