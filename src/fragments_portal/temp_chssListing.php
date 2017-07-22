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

<h1>CHSS Listing</h1>

<?php
    $queryString = "SELECT chss, chss_id, COUNT(cha) as numCHAs, health_facility FROM lastmile_cha.view_base_cha where county='Grand Gedeh' and chss_id IS NOT NULL GROUP BY chss_id ORDER BY health_facility, chss_id, cha_id;";
    $result = mysqli_query($cxn, $queryString);
?>

<h2>
    Grand Gedeh
    <?php echo " (" . mysqli_num_rows($result) . ")" ?>
</h2>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Health Facility</th>
            <th>CHSS</th>
            <th>Number of CHAs assigned</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while ( $row = mysqli_fetch_assoc($result) ) {
                extract($row);
                $tableRow = "<tr>";
                $tableRow .= "<td>$health_facility</td>";
                $tableRow .= "<td>$chss ($chss_id)</td>";
                $tableRow .= "<td>$numCHAs</td>";
                $tableRow .= "</tr>";
                echo $tableRow;
            }
        ?>
    </tbody>
</table>


<?php
    $queryString = "SELECT chss, chss_id, COUNT(cha) as numCHAs, health_facility FROM lastmile_cha.view_base_cha where county='Rivercess' and chss_id IS NOT NULL GROUP BY chss_id ORDER BY health_facility, chss_id, cha_id;";
    $result = mysqli_query($cxn, $queryString);
?>

<h2>
    Rivercess
    <?php echo " (" . mysqli_num_rows($result) . ")" ?>
</h2>

<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Health Facility</th>
            <th>CHSS</th>
            <th>Number of CHAs assigned</th>
        </tr>
    </thead>
    <tbody>
        <?php
            while ( $row = mysqli_fetch_assoc($result) ) {
                extract($row);
                $tableRow = "<tr>";
                $tableRow .= "<td>$health_facility</td>";
                $tableRow .= "<td>$chss ($chss_id)</td>";
                $tableRow .= "<td>$numCHAs</td>";
                $tableRow .= "</tr>";
                echo $tableRow;
            }
        ?>
    </tbody>
</table>
