<?php

// Receive data from frm_TEST_dataSend.html OR sendRecords.js and send it to server

// Get query string; set $result var to "success"
$queryString = "INSERT INTO 0_testde SET var1='hey', var2='you';" ;

// GoDaddy      !!!!! check !!!!!
//              sias hostname is "siasdatahub.db.11647557.hostedresource.com"
$cxn = mysqli_connect("localhost","lastmiledata","LastMile14","lastmiledata") or die("failure") ;

$result = mysqli_query($cxn, $queryString) or die("failure") ;

?>