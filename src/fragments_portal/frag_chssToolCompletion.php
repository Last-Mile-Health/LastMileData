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

        $queryString = "SELECT chss_id, chss, SUM(num_supervision_visit_logs) as num_supervision_visit_logs, "
                . "SUM(num_vaccine_trackers) as num_vaccine_trackers, SUM(num_chss_msrs) as num_chss_msrs, SUM(num_cha_msrs) as num_cha_msrs "
                . "FROM lastmile_cha.view_chss_tool_completion_report "
                . "WHERE (month(now())+(year(now())*12))-(`month`+(`year`*12))<=6 "
                . "GROUP BY `chss_id` ORDER BY `chss`;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$chss</td>";
            $tableRow .= "<td>$chss_id</td>";
            $tableRow .= "<td>$num_supervision_visit_logs</td>";
            $tableRow .= "<td>$num_vaccine_trackers</td>";
            $tableRow .= "<td>$num_chss_msrs</td>";
            $tableRow .= "<td>$num_cha_msrs</td>";
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

        $queryString = "SELECT * FROM lastmile_cha.view_chss_tool_completion_report ORDER BY `year` DESC, `month` DESC, `chss`;";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$chss</td>";
            $tableRow .= "<td>$chss_id</td>";
            $tableRow .= "<td>$year</td>";
            $tableRow .= "<td>$month</td>";
            $tableRow .= "<td>$num_supervision_visit_logs</td>";
            $tableRow .= "<td>$num_vaccine_trackers</td>";
            $tableRow .= "<td>$num_chss_msrs</td>";
            $tableRow .= "<td>$num_cha_msrs</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
