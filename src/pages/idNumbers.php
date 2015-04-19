<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
require_once("cxn.php");

?>

<h1>Village IDs</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>District</th>
        <th>Village Name</th>
        <th>Village ID</th>
    </tr>
    <?php

        $queryString = "

            SELECT districtName, villageName, pk_village
            FROM tbl_data_village INNER JOIN tbl_data_district ON fk_district=pk_district
            ORDER BY districtName, villageName

        ";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$districtName</td>";
            $tableRow .= "<td>$villageName</td>";
            $tableRow .= "<td>$pk_village</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
<br>

<h1>FHW IDs</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>FHW Name</th>
        <th>FHW ID#</th>
    </tr>
    <?php

        $queryString = "

            SELECT staffName, pk_staff FROM tbl_data_staff
            WHERE staffType='F' && ( quitOrReleased NOT IN ('Q','R') || ISNULL(quitOrReleased) )
            ORDER BY staffName;

        ";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$staffName</td>";
            $tableRow .= "<td>$pk_staff</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
<br>

<h1>Supervisor IDs</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>Supervisor Type</th>
        <th>Supervisor Name</th>
        <th>Supervisor ID#</th>
    </tr>
    <?php

        $queryString = "

            SELECT staffName, IF(staffType='C','Clinical Mentor',IF(staffType='L','FHW Leader','Unknown')) AS staffTypeFull, pk_staff FROM tbl_data_staff
            WHERE staffType IN ('C','L') && ( quitOrReleased NOT IN ('Q','R') || ISNULL(quitOrReleased) )
            ORDER BY staffType, staffName;

        ";

        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$staffTypeFull</td>";
            $tableRow .= "<td>$staffName</td>";
            $tableRow .= "<td>$pk_staff</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
