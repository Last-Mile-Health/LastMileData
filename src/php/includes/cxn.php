<?php

// For localhost
$host = "localhost"; $user = "root"; $password = ""; $db = "lastmiledata";

// For GoDaddy host
//$host = "localhost"; $user = "lastmiledata"; $password = "LastMile14"; $db = "lastmiledata";

// Set connection variable
// !!!!! Handle errors differently (test for no DB access); set warnings off !!!!!
// !!!!! Need global configuration file !!!!!
$cxn = mysqli_connect($host,$user,$password,$db) or die("failure") ;

?>