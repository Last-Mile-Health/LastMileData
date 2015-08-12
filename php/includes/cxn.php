<?php

// !!!!! Test app throughout to ensure that DB access errors are handled gracefully !!!!!

// Set connection variable
$cxn = mysqli_connect("localhost","lastmile_admin","LastMile14") or die("Error");

// !!!!! When database is down, user gets a non-friendly error message (when logging in, and elsewhere) !!!!!

function getCXN() {
    $cxn = mysqli_connect("localhost","lastmile_admin","LastMile14") or die("Error");
    return $cxn;
}
