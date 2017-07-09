<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>Data Entry Details</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>DE Year</th>
        <th>DE Month</th>
        <th>DE Clerk</th>
        <th>Table name</th>
        <th># Records</th>
        <th># Records QA'd</th>
    </tr>
    <?php

        $queryString = "SELECT * FROM lastmile_report.view_data_entry ORDER BY de_year DESC, de_month DESC, form_name";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$de_year</td>";
            $tableRow .= "<td>$de_month</td>";
            $tableRow .= "<td>$de_name</td>";
            $tableRow .= "<td>$form_name</td>";
            $tableRow .= "<td>$num_records</td>";
            $tableRow .= "<td>$num_qa</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
