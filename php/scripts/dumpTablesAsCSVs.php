<?php

// Dumps specific MySQL tables as CSVs into /LastMileData/backups/CSVs. Should be run daily at roughly 1am (server time - EST)
// URL: localhost/LastMileData/php/scripts/dumpTablesAsCSVs.php

// Set include path; require connection file
// set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
// Apparently, cron does not inherit all the user shell info, like DOCUMENT_ROOT, so I had to hard code the .../php/includes path.
// Same goes for the query3 string below.
set_include_path( "/home/lastmilehealth/public_html/LastMileData/php/includes" );
require_once("cxn.php");

// Delete old files
$files = glob('../../backups/CSVs/*');
foreach($files as $file){ 
    if(is_file($file)) {
        unlink($file); 
    }
}

executeStatements('lastmile_upload','odk_sickChildForm', $cxn);
executeStatements('lastmile_upload','odk_chaRestock', $cxn);
executeStatements('lastmile_upload','odk_routineVisit', $cxn);
executeStatements('lastmile_upload','odk_supervisionVisitLog', $cxn);
executeStatements('lastmile_upload','odk_vaccineTracker', $cxn);
executeStatements('lastmile_upload','odk_OSFKAPSurvey', $cxn);
executeStatements('lastmile_upload','odk_osf_routine', $cxn);
executeStatements('lastmile_upload','odk_QAOSupervisionChecklistForm', $cxn);

executeStatements('lastmile_upload','de_chaHouseholdRegistration', $cxn);
executeStatements('lastmile_upload','de_cha_monthly_service_report', $cxn);
executeStatements('lastmile_upload','de_chss_monthly_service_report', $cxn);
executeStatements('lastmile_upload','de_cha_status_change_form', $cxn);
executeStatements('lastmile_upload','de_chss_commodity_distribution', $cxn);

executeStatements('lastmile_upload','de_direct_observation', $cxn);
executeStatements('lastmile_upload','de_register_review', $cxn);

executeStatements('lastmile_program','train_cha', $cxn);
executeStatements('lastmile_program','train_chss', $cxn);

executeStatements('lastmile_liberiamohdata','federated_de_integrated_supervision_tool_facility', $cxn);
executeStatements('lastmile_liberiamohdata','federated_de_integrated_supervision_tool_facility_spot_check', $cxn);
executeStatements('lastmile_liberiamohdata','federated_de_integrated_supervision_tool_community', $cxn);
executeStatements('lastmile_liberiamohdata','federated_de_integrated_supervision_tool_community_spot_check', $cxn);

function executeStatements($schema, $table, $cxn) {

    // Increase maximum length of row entry for GROUP_CONCAT
    $query1 = "SET SESSION group_concat_max_len=10000;";
    mysqli_query($cxn, $query1) or die(mysqli_error($cxn));
    
    // Parse and run query string that selects column names
    $query2 = "SELECT GROUP_CONCAT(CONCAT(\"'\",column_name, \"'\")) AS `val` FROM information_schema.columns
    WHERE `table_schema`='$schema' AND table_name='$table';";
    $result = mysqli_query($cxn, $query2) or die(mysqli_error($cxn));
    $columnHeaders = mysqli_fetch_assoc($result)['val'];
    
    // Parse full query string
 //   $query3 = "SELECT $columnHeaders
 //   UNION
 //   SELECT * FROM `$schema`.`$table`
 //   INTO OUTFILE '" . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/backups/CSVs/$table" . "_" . date('Y-m-d') . ".csv'
 //   FIELDS TERMINATED BY ',' ENCLOSED BY '\\\"' LINES TERMINATED BY '\\n';";
    
    $query3 = "SELECT $columnHeaders
    UNION
    SELECT * FROM `$schema`.`$table`
    INTO OUTFILE '" . "/home/lastmilehealth/public_html" . "/LastMileData/backups/CSVs/$table" . "_" . date('Y-m-d') . ".csv'
    FIELDS TERMINATED BY ',' ENCLOSED BY '\\\"' LINES TERMINATED BY '\\n';";
   
    // Run query
    mysqli_query($cxn, $query3) or die(mysqli_error($cxn));
 
}
