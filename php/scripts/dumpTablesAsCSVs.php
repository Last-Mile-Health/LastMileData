<?php

// Dumps specific MySQL tables as CSVs into /LastMileData/backups/CSVs. Should be run daily at roughly 1am (server time - EST)
// URL: localhost/LastMileData/php/scripts/dumpTablesAsCSVs.php

// Set include path; require connection file
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

executeStatements('lastmile_chwdb','staging_chwMonthlyServiceReportStep1', $cxn);
executeStatements('lastmile_chwdb','staging_odk_departurechecklog', $cxn);
executeStatements('lastmile_chwdb','staging_odk_arrivalchecklog', $cxn);
executeStatements('lastmile_chwdb','staging_odk_chwrestock', $cxn);
executeStatements('lastmile_chwdb','staging_odk_sickchildform', $cxn);
executeStatements('lastmile_chwdb','staging_odk_routinevisit', $cxn);
executeStatements('lastmile_chwdb','staging_odk_vaccinetracker', $cxn);
executeStatements('lastmile_chwdb','staging_odk_supervisionvisitlog', $cxn);

function executeStatements($schema, $table, $cxn) {

    // 
    $query1 = "SET SESSION group_concat_max_len=10000;";
    mysqli_query($cxn, $query1) or die(mysqli_error($cxn));
    
    // Parse and run query string that selects column names
    $query2 = "SELECT GROUP_CONCAT(CONCAT(\"'\",column_name, \"'\")) AS `val` FROM information_schema.columns
    WHERE `table_schema`='$schema' AND table_name='$table';";
    $result = mysqli_query($cxn, $query2) or die(mysqli_error($cxn));
    $columnHeaders = mysqli_fetch_assoc($result)['val'];
//    echo $columnHeaders . "<br><br>";
    
    // Parse full query string
    $query3 = "SELECT $columnHeaders
    UNION
    SELECT * FROM `$schema`.`$table`
    INTO OUTFILE '" . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/backups/CSVs/$table" . "_" . date('Y-m-d') . ".csv'
    FIELDS TERMINATED BY ',' ENCLOSED BY '\\\"' LINES TERMINATED BY '\\n';";

//echo $query2 . "<br><br>";
    
    // Run query
    mysqli_query($cxn, $query3) or die(mysqli_error($cxn));
 
}