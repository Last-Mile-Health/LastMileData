<?php

// For both XAMPP localhost and GoDaddy localhost
$host = "localhost"; $user = ""; $password = "";

// Set connection variable
// !!!!! Need to gracefully handle errors (e.g. test for no DB access); set warnings off !!!!!
// !!!!! Need global configuration file !!!!!
$cxn = mysqli_connect($host,$user,$password) or die("Error");

?>
