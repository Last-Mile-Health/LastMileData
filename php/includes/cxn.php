<?php

// Credentials file must be a single line with username and password separated by a colon (":").
$f = fopen( $_SERVER['DOCUMENT_ROOT'] . '/../' . '.credentials', 'r');
$line = fgets( $f );
fclose($f);

// Then, strip all blanks out of substrings and store substring before colon in $username and substring after colon in $password.
list( $username, $password ) = explode( ":", str_replace( ' ', '', $line ) );

$cxn = mysqli_connect( "127.0.0.1",$username, $password ) or die("Error");

// This function is only called from ./php/scripts/LMD_REST.php
function getCXN() {

    $f = fopen( $_SERVER['DOCUMENT_ROOT'] . '/../' . '.credentials', 'r');
    $line = fgets( $f );
    fclose($f);
    list( $username, $password ) = explode( ":", str_replace( ' ', '', $line ) );

    $cxn = mysqli_connect( "127.0.0.1",$username, $password ) or die("Error");
    return $cxn;
}

