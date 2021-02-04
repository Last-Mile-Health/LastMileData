<?php

// !!!!! Test app throughout to ensure that DB access errors are handled gracefully !!!!!

//Inexplicably, localhost stopped working, so we now have to use 127.0.0.1.
// Set connection variable
$cxn = mysqli_connect("127.0.0.1","","") or die("Error");

// !!!!! When database is down, user gets a non-friendly error message (when logging in, and elsewhere) !!!!!

function getCXN() {
    $cxn = mysqli_connect("127.0.0.1","","") or die("Error");
    return $cxn;
}