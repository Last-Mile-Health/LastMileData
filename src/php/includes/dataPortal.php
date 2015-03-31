<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

function echoJSON($cxn, $table, $x, $yList, $yNames) {
    
    $query = "SELECT ";
    for($i=0; $i<count($yList); $i++) {
        $query .= "$yList[$i], ";
    }
    $query .= $x;
    $query .= " FROM lastmile_db.$table";
    $result = mysqli_query($cxn, $query);
    
    // Parse into JSON
    $myData = "'[";
        while($row = mysqli_fetch_array($result)) {
            for($i=0; $i<count($yList); $i++) {
                $myData .= '{"Month":"' . $row[$x] . '", "Value":"' . $row[$yList[$i]] . '", "Split":"' . $yNames[$i] . '"}, ';
            }
        }
    $myData = substr($myData,0,-2);
    $myData .= "]'";
    echo $myData;
}

?>