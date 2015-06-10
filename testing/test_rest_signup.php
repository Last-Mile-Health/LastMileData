<?php

// Database config (this is normall in cxn.php)
$host = "localhost"; $user = "lastmile_admin"; $password = "LastMile14";
$cxn = mysqli_connect($host,$user,$password) or die("Error");

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $district = isset($_POST['district']) ? mysqli_real_escape_string($cxn, $_POST['district']) : '';
    $numFacilities = isset($_POST['numFacilities']) ? mysqli_real_escape_string($cxn, $_POST['numFacilities']) : '';
    $numCHWs = isset($_POST['numCHWs']) ? mysqli_real_escape_string($cxn, $_POST['numCHWs']) : '';
    $query = "INSERT INTO test_rest.tbl_mydata SET `district`='$district', `numFacilities`='$numFacilities', `numCHWs`='$numCHWs';";
    $result = mysqli_query($cxn, $query);
    
    if ($result) {
        $json = array("status"=>1, "msg"=>"Success!");
    } else {
        $json = array("status"=>0, "msg"=>"Failure. Database insert unsuccessful.");
    }
    
} else {
    $json = array("status"=>0, "msg"=>"Failure. Request method not accepted");
}

mysqli_close($cxn);

header('Content-type: application/json');
echo json_encode($json);
