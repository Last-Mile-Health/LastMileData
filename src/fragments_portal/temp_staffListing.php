<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>Staff Listing</h1>

<h2>Grand Gedeh</h2>
<table class="table table-striped table-hover">
    <tr>
        <th>Health Facility</th>
        <th>CHSS</th>
        <th>CHA</th>
        <th>Communities</th>
        <th>Community IDs</th>
    </tr>
    <?php

        $queryString = "SELECT chss, chssID, cha, chaID, community, communityID, healthFacility FROM lastmile_cha.temp_view_chss_cha_report where county='Grand Gedeh' ORDER BY healthFacility, chssID, chaID;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $chssPlusID = $chss!="" ? $chss . " (" . $chssID . ")" : "unassigned";
            $tableRow = "<tr>";
            $tableRow .= "<td>$healthFacility</td>";
            $tableRow .= "<td>$chssPlusID</td>";
            $tableRow .= "<td>$cha ($chaID)</td>";
            $tableRow .= "<td>$community</td>";
            $tableRow .= "<td>$communityID</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>


<h2>Rivercess</h2>
<table class="table table-striped table-hover">
    <tr>
        <th>Health Facility</th>
        <th>CHSS</th>
        <th>CHA</th>
        <th>Communities</th>
        <th>Community IDs</th>
    </tr>
    <?php

        $queryString = "SELECT chss, chssID, cha, chaID, community, communityID, healthFacility FROM lastmile_cha.temp_view_chss_cha_report where county='Rivercess' ORDER BY healthFacility, chssID, chaID;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $chssPlusID = $chss!="" ? $chss . " (" . $chssID . ")" : "unassigned";
            $tableRow = "<tr>";
            $tableRow .= "<td>$healthFacility</td>";
            $tableRow .= "<td>$chssPlusID</td>";
            $tableRow .= "<td>$cha ($chaID)</td>";
            $tableRow .= "<td>$community</td>";
            $tableRow .= "<td>$communityID</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
