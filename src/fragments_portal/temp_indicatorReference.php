<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<script>
$(document).ready(function(){
    $('.table').DataTable();
});
</script>

<h1>Indicator Reference</h1>

<?php
    $queryString = "SELECT indID, indName, indCategory, indDefinition, indSource FROM lastmile_dataportal.tbl_indicators where archived=0 AND indName NOT LIKE 'Numerator%' AND indName NOT LIKE 'Denominator%' ORDER BY indCategory, indName;";
    $result = mysqli_query($cxn, $queryString);
?>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>ID#</th>
            <th>Category</th>
            <th>Indicator name</th>
            <th>Definition</th>
            <th>Data source</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while ( $row = mysqli_fetch_assoc($result) ) {
                extract($row);
                $tableRow = "<tr>";
                $tableRow .= "<td>$indID</td>";
                $tableRow .= "<td>$indCategory</td>";
                $tableRow .= "<td>$indName</td>";
                $tableRow .= "<td>$indDefinition</td>";
                $tableRow .= "<td>$indSource</td>";
                $tableRow .= "</tr>";
                echo $tableRow;
            }
        ?>
    </tbody>
</table>
