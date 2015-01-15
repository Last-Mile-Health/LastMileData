<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

// !!!!! DEBUG !!!!!
//$con = mysqli_init();
// capath = The pathname to a directory that contains trusted SSL CA certificates in PEM format.
// cipher = A list of allowable ciphers to use for SSL encryption.
//mysqli_ssl_set($con, $pathToKey, $pathToCert, $pathToCa, $capath, $cipher);
//$link = mysqli_real_connect($con,"localhost","lastmile_admin","LastMile14","lastmile_db",3306,null,MYSQLI_CLIENT_SSL) or die("Connection error " . mysqli_error($link)); 

$link = mysqli_connect("localhost","lastmile_admin","LastMile14","lastmile_db") or die("Connection error " . mysqli_error($link));
$query = "SELECT username FROM lastmile_db.tbl_utility_users" or die("Error in the consult.." . mysqli_error($link));
$result = mysqli_query($link, $query);

while($row = mysqli_fetch_array($result)) {
  echo $row["username"] . "<br>";
}
