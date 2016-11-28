<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>ID Numbers</h1>
<ul>
    <li><h3><a href="#commIDs">Community IDs</a></h3></li>
    <li><h3><a href="#chaIDs">CHA IDs</a></h3></li>
    <li><h3><a href="#supIDs">Supervisor IDs</a></h3></li>
</ul>
<br>

<h1 id="commIDs">Community IDs</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>County</th>
        <th>Health district</th>
        <th>Community name</th>
        <th>Community ID</th>
    </tr>
    <?php

        $queryString = "
            SELECT county, healthDistrict, community, communityID
            FROM lastmile_chwdb.view_territoryCommunity
            WHERE community IS NOT NULL
            ORDER BY county, healthDistrict, community;
        ";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$county</td>";
            $tableRow .= "<td>$healthDistrict</td>";
            $tableRow .= "<td>$community</td>";
            $tableRow .= "<td>$communityID</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
<br>

<h1 id="chaIDs">CHA IDs</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>CHA name</th>
        <th>CHA ID</th>
        <th>Date position began</th>
        <th>Date position ended</th>
    </tr>
    <?php

        $queryString = "
            SELECT staffID, staffName, datePositionBegan, datePositionEnded FROM lastmile_chwdb.view_staffPosition
            WHERE title='CHW'
            ORDER BY staffName;
        ";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$staffName</td>";
            $tableRow .= "<td>$staffID</td>";
            $tableRow .= "<td>$datePositionBegan</td>";
            $tableRow .= "<td>$datePositionEnded</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
<br>

<h1 id="supIDs">Supervisor IDs</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>Supervisor Name</th>
        <th>Supervisor ID#</th>
        <th>Date position began</th>
        <th>Date position ended</th>
        <th>Supervisor Type</th>
    </tr>
    <?php

        $queryString = "
            SELECT staffID, staffName, datePositionBegan, datePositionEnded, title FROM lastmile_chwdb.view_staffPosition
            WHERE title IN ('CHWL','CCS')
            ORDER BY title, staffName;
        ";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$staffName</td>";
            $tableRow .= "<td>$staffID</td>";
            $tableRow .= "<td>$datePositionBegan</td>";
            $tableRow .= "<td>$datePositionEnded</td>";
            $tableRow .= "<td>$title</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
