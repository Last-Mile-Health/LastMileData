<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

?>

<h1>Data Entry Summary</h1>
<table class="table table-striped table-hover">
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
        while ( $row = mysqli_fetch_assoc($result) ) {
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
