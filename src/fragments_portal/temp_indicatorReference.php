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
    $queryString = "SELECT ind_id, ind_name, ind_category, ind_definition, ind_source FROM lastmile_dataportal.tbl_indicators where archived=0 AND ind_name NOT LIKE 'Numerator%' AND ind_name NOT LIKE 'Denominator%' ORDER BY ind_category, ind_name;";
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
                $tableRow .= "<td>$ind_id</td>";
                $tableRow .= "<td>$ind_category</td>";
                $tableRow .= "<td>$ind_name</td>";
                $tableRow .= "<td>$ind_definition</td>";
                $tableRow .= "<td>$ind_source</td>";
                $tableRow .= "</tr>";
                echo $tableRow;
            }
        ?>
    </tbody>
</table>
