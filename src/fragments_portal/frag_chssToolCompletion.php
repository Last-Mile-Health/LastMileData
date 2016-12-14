<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>CHSS Tool Completion</h1>

<p>Note: Only active CHSSs are displayed. Data is shown for the most recent 6 months.</p>

<ul>
    <li><h3><a href="#table_overview">Overview (last 6 months, aggregated)</a></h3></li>
    <li><h3><a href="#table_detailed">Detailed (monthly)</a></h3></li>
</ul>
<br>

<h1 id="table_overview">Overview (last 6 months, aggregated)</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>CHSS name</th>
        <th>CHSS ID#</th>
        <th># supervision visit logs</th>
        <th># vaccine trackers</th>
    </tr>
    <?php

        $queryString = "SELECT ccsID, ccsName, SUM(numSupervisionVisitLogs) as numSupervisionVisitLogs, "
                . "SUM(numVaccineTrackers) as numVaccineTrackers FROM lastmile_chwdb.view_chss_tool_completion_2 "
                . "GROUP BY `ccsID` ORDER BY `ccsName`;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$ccsName</td>";
            $tableRow .= "<td>$ccsID</td>";
            $tableRow .= "<td>$numSupervisionVisitLogs</td>";
            $tableRow .= "<td>$numVaccineTrackers</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
<br>

<h1 id="table_detailed">Detailed (monthly)</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>CHSS name</th>
        <th>CHSS ID#</th>
        <th>Year</th>
        <th>Month</th>
        <th># supervision visit logs</th>
        <th># vaccine trackers</th>
    </tr>
    <?php

        $queryString = "SELECT * FROM lastmile_chwdb.view_chss_tool_completion_2 ORDER BY `year` DESC, `month` DESC, `ccsName`;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$ccsName</td>";
            $tableRow .= "<td>$ccsID</td>";
            $tableRow .= "<td>$year</td>";
            $tableRow .= "<td>$month</td>";
            $tableRow .= "<td>$numSupervisionVisitLogs</td>";
            $tableRow .= "<td>$numVaccineTrackers</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
