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
        <th># CHSS MSRs</th>
        <th># CHA MSRs</th>
    </tr>
    <?php

        $queryString = "SELECT chssID, chssName, SUM(numSupervisionVisitLogs) as numSupervisionVisitLogs, "
                . "SUM(numVaccineTrackers) as numVaccineTrackers, SUM(numCHSSMSRs) as numCHSSMSRs, SUM(numCHAMSRs) as numCHAMSRs "
                . "FROM lastmile_chwdb.view_chss_tool_completion_2 "
                . "WHERE (month(now())+(year(now())*12))-(`month`+(`year`*12))<=6 "
                . "GROUP BY `chssID` ORDER BY `chssName`;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$chssName</td>";
            $tableRow .= "<td>$chssID</td>";
            $tableRow .= "<td>$numSupervisionVisitLogs</td>";
            $tableRow .= "<td>$numVaccineTrackers</td>";
            $tableRow .= "<td>$numCHSSMSRs</td>";
            $tableRow .= "<td>$numCHAMSRs</td>";
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
        <th># CHSS MSRs</th>
        <th># CHA MSRs</th>
    </tr>
    <?php

        $queryString = "SELECT * FROM lastmile_chwdb.view_chss_tool_completion_2 ORDER BY `year` DESC, `month` DESC, `chssName`;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$chssName</td>";
            $tableRow .= "<td>$chssID</td>";
            $tableRow .= "<td>$year</td>";
            $tableRow .= "<td>$month</td>";
            $tableRow .= "<td>$numSupervisionVisitLogs</td>";
            $tableRow .= "<td>$numVaccineTrackers</td>";
            $tableRow .= "<td>$numCHSSMSRs</td>";
            $tableRow .= "<td>$numCHAMSRs</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
