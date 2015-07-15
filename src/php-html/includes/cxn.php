<?php

// !!!!! Test app throughout to ensure that DB access errors are handled gracefully !!!!!

// Set connection variable
$cxn = mysqli_connect("localhost","lastmile_admin","LastMile14") or die("Error");

function getCXN() {
    $cxn = mysqli_connect("localhost","lastmile_admin","LastMile14") or die("Error");
    return $cxn;
}
