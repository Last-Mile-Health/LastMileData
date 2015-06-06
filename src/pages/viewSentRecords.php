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
            meta_DE_init, meta_DE_date, COUNT(pk) AS recordCount, SUM(qa_init<>'') AS qaCount
            FROM lastmile_db.tbl_data_fhw_bbf_bigbellyfollowup
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_bbi_bigbellyinitial',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_bbi_bigbellyinitial
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_bdm_movements',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_bdm_movements
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_ees_ebolaeducationscreening',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_ees_ebolaeducationscreening
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_kpi_kpiassessment',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_kpi_kpiassessment
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_pnf_postnatalfollowup',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_pnf_postnatalfollowup
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_pni_postnatalinitial',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_pni_postnatalinitial
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_ref_referral',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_ref_referral
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_reg_registration',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_reg_registration
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_sch_sickchild',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_sch_sickchild
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_fhw_sst_sicknessscreening',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_fhw_sst_sicknessscreening
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'tbl_data_prg_trl_trainingledger',
            meta_DE_init, meta_DE_date, COUNT(pk), SUM(qa_init<>'')
            FROM lastmile_db.tbl_data_prg_trl_trainingledger
            GROUP BY meta_DE_init, meta_DE_date

            ORDER BY meta_DE_date DESC, meta_DE_init, myTable;

        ";


        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$meta_DE_date</td>";
            $tableRow .= "<td>$meta_DE_init</td>";
            $tableRow .= "<td>$myTable</td>";
            $tableRow .= "<td>$recordCount</td>";
            $tableRow .= "<td>$qaCount</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
