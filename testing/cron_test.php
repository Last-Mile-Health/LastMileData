<?php

date_default_timezone_set("America/New_York");
$loginTime = date('Y-m-d H:i:s');

$query = "INSERT INTO lastmiledata.test (col_datetime) VALUES ('$loginTime');";

$cxn = mysqli_connect("localhost","lastmiledata","LastMile14","lastmiledata") or die("failure");
mysqli_query($cxn, $query) or die("failure");

?>