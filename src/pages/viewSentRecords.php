<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

?>

<!DOCTYPE html>
<!--
    !!!!! "Template" this page to match other "utility" pages !!!!!
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>View sent records</title>
        <style>
            #myTable { border-collapse:collapse; }
            #myTable th { border: 1px solid black; padding:5px; }
            #myTable td { border: 1px solid black; padding:5px; }
        </style>
        <script src="/LastMileData/lib/jquery.min.js"></script>
    </head>
    <body>
        <table id="myTable">
            <tr>
                <th>DE Date</th>
                <th>DE Initials</th>
                <th>Table name</th>
                <th># Records</th>
                <th># Records QA'd</th>
            </tr>
            <?php
                
                $queryString = "
                    
                    SELECT 'tbl_data_fhw_bbf_bigbellyfollowup' AS myTable,
                    de_init, de_date, COUNT(pk) AS recordCount, SUM(qa_init<>'') AS qaCount
                    FROM tbl_data_fhw_bbf_bigbellyfollowup
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_bbi_bigbellyinitial',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_bbi_bigbellyinitial
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_bdm_movements',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_bdm_movements
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_ees_ebolaeducationscreening',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_ees_ebolaeducationscreening
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_kpi_kpiassessment',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_kpi_kpiassessment
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_pnf_postnatalfollowup',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_pnf_postnatalfollowup
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_pni_postnatalinitial',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_pni_postnatalinitial
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_ref_referral',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_ref_referral
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_reg_registration',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_reg_registration
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_sch_sickchild',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_sch_sickchild
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_fhw_sst_sicknessscreening',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_fhw_sst_sicknessscreening
                    GROUP BY de_init, de_date
                    
                    UNION SELECT 'tbl_data_prg_trl_trainingledger',
                    de_init, de_date, COUNT(pk), SUM(qa_init<>'')
                    FROM tbl_data_prg_trl_trainingledger
                    GROUP BY de_init, de_date
                    
                    ORDER BY de_date DESC, de_init, myTable;
                    
                ";
                
                
                $result = mysqli_query($cxn, $queryString);
                echo('.. version [2] ..');//DEBUG
                echo(print_r($result));//DEBUG
                echo('hey 2');//DEBUG
//                while ( $row = mysqli_fetch_assoc($result) ) {
//                if ( $row = mysqli_fetch_assoc($result) ) {//DEBUG
                if ( 1 ) {//DEBUG
                $row = mysqli_fetch_assoc($result);//DEBUG
                echo('hey 3');//DEBUG
                echo(print_r($row));//DEBUG
                    extract($row);
                    $tableRow = "<tr>";
                    $tableRow .= "<td>$de_date</td>";
                    $tableRow .= "<td>$de_init</td>";
                    $tableRow .= "<td>$myTable</td>";
                    $tableRow .= "<td>$recordCount</td>";
                    $tableRow .= "<td>$qaCount</td>";
                    $tableRow .= "</tr>";
                    echo $tableRow;
                }
                
            ?>
        </table>
        <br>
        <a href="/LastMileData/src/pages/page_deqa.html">Return to DEQA page.</a>
    </body>
</html>
