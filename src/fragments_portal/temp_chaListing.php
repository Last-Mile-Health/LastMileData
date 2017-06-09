<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>CHA Listing</h1>

<?php
    $queryString = "SELECT chss, chss_id, cha, cha_id, community_list, community_id_list, health_facility FROM lastmile_cha.view_base_cha where county='Grand Gedeh' ORDER BY health_facility, chss_id, cha_id;";
    $result = mysqli_query($cxn, $queryString);
?>

<h2>
    Grand Gedeh
    <?php echo " (" . mysqli_num_rows($result) . ")" ?>
</h2>

<table class="table table-striped table-hover">
    <tr>
        <th>Health Facility</th>
        <th>CHSS</th>
        <th>CHA</th>
        <th>Communities</th>
        <th>Community IDs</th>
    </tr>
    <?php
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $chssPlusID = $chss!="" ? $chss . " (" . $chss_id . ")" : "unassigned";
            $tableRow = "<tr>";
            $tableRow .= "<td>$health_facility</td>";
            $tableRow .= "<td>$chssPlusID</td>";
            $tableRow .= "<td>$cha ($cha_id)</td>";
            $tableRow .= "<td>$community_list</td>";
            $tableRow .= "<td>$community_id_list</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }
    ?>
</table>


<?php
    $queryString = "SELECT chss, chss_id, cha, cha_id, community_list, community_id_list, health_facility FROM lastmile_cha.view_base_cha where county='Rivercess' ORDER BY health_facility, chss_id, cha_id;";
    $result = mysqli_query($cxn, $queryString);
?>

<h2>
    Rivercess
    <?php echo " (" . mysqli_num_rows($result) . ")" ?>
</h2>

<table class="table table-striped table-hover">
    <tr>
        <th>Health Facility</th>
        <th>CHSS</th>
        <th>CHA</th>
        <th>Communities</th>
        <th>Community IDs</th>
    </tr>
    <?php
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $chssPlusID = $chss!="" ? $chss . " (" . $chss_id . ")" : "unassigned";
            $tableRow = "<tr>";
            $tableRow .= "<td>$health_facility</td>";
            $tableRow .= "<td>$chssPlusID</td>";
            $tableRow .= "<td>$cha ($cha_id)</td>";
            $tableRow .= "<td>$community_list</td>";
            $tableRow .= "<td>$community_id_list</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }
    ?>
</table>
