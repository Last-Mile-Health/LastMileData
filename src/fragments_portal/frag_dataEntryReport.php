<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>Data Entry Details</h1>
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

            UNION SELECT 'Births, Deaths, Movements (new)',
            meta_DE_init, meta_DE_date, COUNT(*), SUM(meta_qa_init<>'')
            FROM lastmile_chwdb.staging_birthsDeathsMovementsStep1
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'Registration (new)',
            meta_DE_init, meta_DE_date, COUNT(*), SUM(meta_qa_init<>'')
            FROM lastmile_chwdb.staging_registrationStep1
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'Training Results Record',
            meta_DE_init, meta_DE_date, COUNT(*), SUM(meta_qa_init<>'')
            FROM lastmile_chwdb.staging_trainingResultsRecordStep1
            GROUP BY meta_DE_init, meta_DE_date

            UNION SELECT 'CHW Monthly Service Report',
            meta_DE_init, meta_DE_date, COUNT(*), SUM(meta_qa_init<>'')
            FROM lastmile_chwdb.staging_chwMonthlyServiceReportStep1
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
