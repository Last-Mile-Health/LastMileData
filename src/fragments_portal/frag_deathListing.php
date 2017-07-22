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

<h1>Death Listing</h1>
<table class="table table-striped table-hover">
    <thead>
        <tr>
            <th>Year</th>
            <th>Month</th>
            <th>County</th>
            <th>Health Facility</th>
            <th>CHA</th>
            <th>Community</th>
            <th>Deaths</th>
        </tr>
    </thead>
    <tbody>
        <?php

            $queryString = "

                SELECT year_reported, month_reported, county, health_facility, CONCAT(cha_name,' (',cha_id,')') as cha, CONCAT(community,' (',community_id,')') as community,
                TRIM(TRAILING ', ' FROM (
                    CONCAT(
                        IF(num_stillbirths,CONCAT('stillbirth: ',num_stillbirths,', '),''),
                        IF(num_deaths_neonatal,CONCAT('neonatal: ',num_deaths_neonatal,', '),''),
                        IF(num_deaths_postneonatal,CONCAT('postneonatal: ',num_deaths_postneonatal,', '),''),
                        IF(num_deaths_child,CONCAT('child: ',num_deaths_child,', '),''),
                        IF(num_deaths_maternal,CONCAT('maternal: ',num_deaths_maternal,', '),'')
                    )
                )) as `deaths`
                FROM lastmile_report.view_base_msr
                WHERE (num_stillbirths OR num_deaths_neonatal OR num_deaths_postneonatal OR num_deaths_child OR num_deaths_maternal)
                AND year_reported IS NOT NULL AND month_reported IS NOT NULL
                ORDER BY year_reported DESC, month_reported DESC, county DESC, health_facility DESC, community DESC;

            ";


            $result = mysqli_query($cxn, $queryString);
            while ( $row = mysqli_fetch_assoc($result) ) {
                extract($row);
                $tableRow = "<tr>";
                $tableRow .= "<td>$year_reported</td>";
                $tableRow .= "<td>$month_reported</td>";
                $tableRow .= "<td>$county</td>";
                $tableRow .= "<td>$health_facility</td>";
                $tableRow .= "<td>$cha</td>";
                $tableRow .= "<td>$community</td>";
                $tableRow .= "<td>$deaths</td>";
                $tableRow .= "</tr>";
                echo $tableRow;
            }

        ?>
    </tbody>
</table>
