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
        <th>CHA</th>
        <th>Community</th>
        <th>Deaths</th>
    </tr>
    <?php

        $queryString = "

            SELECT yearReported, monthReported, county, healthFacility, CONCAT(chwName,' (',chwID,')') as CHA, CONCAT(community,' (',communityID,')') as Community ,
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
            UNION SELECT yearReported, monthReported, county, healthFacility, CONCAT(chaName,' (',chaID,')') as CHA, CONCAT(community,' (',communityID,')') as Community ,
            TRIM(TRAILING ', ' FROM (
                CONCAT(
                    IF(nStillBirths,CONCAT('stillbirth: ',nStillBirths,', '),''),
                    IF(nDeathsNeonatal,CONCAT('neonatal: ',nDeathsNeonatal,', '),''),
                    IF(nDeathsPostneonatal,CONCAT('post-neonatal: ',nDeathsPostneonatal,', '),''),
                    IF(nDeathsChild,CONCAT('child: ',nDeathsChild,', '),''),
                    IF(nDeathsMaternal,CONCAT('maternal: ',nDeathsMaternal,', '),'')
                )
            )) as `deaths`
            FROM lastmile_dataportal._temp_view_msr
            WHERE (nStillBirths OR nDeathsNeonatal OR nDeathsPostneonatal OR nDeathsChild OR nDeathsMaternal)
            AND yearReported IS NOT NULL AND monthReported IS NOT NULL
            ORDER BY CAST(yearReported AS UNSIGNED) DESC, CAST(monthReported AS UNSIGNED) DESC, county DESC, healthFacility DESC, community DESC;

        ";


        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$yearReported</td>";
            $tableRow .= "<td>$monthReported</td>";
            $tableRow .= "<td>$county</td>";
            $tableRow .= "<td>$healthFacility</td>";
            $tableRow .= "<td>$CHA</td>";
            $tableRow .= "<td>$Community</td>";
            $tableRow .= "<td>$deaths</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
