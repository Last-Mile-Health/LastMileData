


<?php

// Set include path
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );

// Establish database connection
require_once("cxn.php");


$tables = '*';

// Get all of the tables (!!!!! could make this an array if you don't want to download entire database !!!!!)
//if($tables == '*')
//{
//        $tables = array();
//        $result = mysqli_query('SHOW TABLES');
//        while($row = mysql_fetch_row($result))
//        {
//                $tables[] = $row[0];
//        }
//}
//else
//{
//        $tables = is_array($tables) ? $tables : explode(',',$tables);
//}

// Set time zone; set current date
date_default_timezone_set("America/New_York");
$dateNow = date('Y-m-d');


// Cycle through tables
//foreach ($tables as $table_name)
//{
    $table_name = "0_testde";   // DEBUG
    $backup_file  = "/home/lastmilehealth/public_html/LastMileData/testing/mytestbackup.sql";   // DEBUG
//    $backup_file  = "/home/lastmilehealth/public_html/LastMileData/testing/backup_" . $table_name . "_" . $dateNow . ".sql";    // !!!!! change path !!!!!
    $query = "SELECT * INTO OUTFILE '$backup_file' FROM $table_name";
    
    // Run query
    $retval = mysqli_query( $query, $cxn ) or die('Could not take data backup: ' . mysql_error());
//}


echo "Data backup successful!";

mysqli_close($cxn);

?>