<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>Death Listing</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>Year</th>
        <th>Month</th>
        <th>County</th>
        <th>Health Facility</th>
        <th>CHWL</th>
        <th>CHW</th>
        <th>Community</th>
        <th>Deaths</th>
    </tr>
    <?php

        $queryString = "

            SELECT yearReported, monthReported, county, healthFacility, CONCAT(chwl_current,' (',chwlID_current,')') as CHWL, CONCAT(chwName,' (',chwID,')') as CHW, CONCAT(community,' (',communityID,')') as Community ,
            TRIM(TRAILING ', ' FROM (
                    CONCAT(
                            IF(routineVisitsPopulationStillBirthsTotal,CONCAT('stillbirth: ',routineVisitsPopulationStillBirthsTotal,', '),''),
                            IF(routineVisitsPopulationNeonatalDeathsTotal,CONCAT('neonatal: ',routineVisitsPopulationNeonatalDeathsTotal,', '),''),
                            IF(routineVisitsPopulationPostNeonatalDeathsTotal,CONCAT('post-neonatal: ',routineVisitsPopulationPostNeonatalDeathsTotal,', '),''),
                            IF(routineVisitsPopulationChildDeathsTotal,CONCAT('child: ',routineVisitsPopulationChildDeathsTotal,', '),''),
                            IF(routineVisitsPopulationMaternalDeathsTotal,CONCAT('maternal: ',routineVisitsPopulationMaternalDeathsTotal,', '),'')
                    )
            )) as `deaths`
            FROM lastmile_chwdb.view_msr
            WHERE (routineVisitsPopulationStillBirthsTotal OR routineVisitsPopulationNeonatalDeathsTotal OR routineVisitsPopulationPostNeonatalDeathsTotal OR routineVisitsPopulationChildDeathsTotal OR routineVisitsPopulationMaternalDeathsTotal)
            AND yearReported IS NOT NULL AND monthReported IS NOT NULL
            ORDER BY yearReported DESC, monthReported DESC, county DESC, healthFacility DESC, community DESC;

        ";


        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$yearReported</td>";
            $tableRow .= "<td>$monthReported</td>";
            $tableRow .= "<td>$county</td>";
            $tableRow .= "<td>$healthFacility</td>";
            $tableRow .= "<td>$CHWL</td>";
            $tableRow .= "<td>$CHW</td>";
            $tableRow .= "<td>$Community</td>";
            $tableRow .= "<td>$deaths</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
