<?php

// Set include path; require connection strings
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
require_once("cxn.php");

?>

<h1>ODK Data Upload Details</h1>
<table class="table table-striped table-hover">
    <tr>
        <th>Upload date</th>
        <th>Upload time</th>
        <th>Upload user</th>
        <th>Form type</th>
        <th># Records</th>
    </tr>
    <?php

        $queryString = "

            SELECT 'CHW restock' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_chwrestock` GROUP BY `uploadDate`, `uploadTime`
            
            UNION SELECT 'Health survey' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_healthsurvey` GROUP BY `uploadDate`, `uploadTime`
            
            UNION SELECT 'Routine visit' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_routinevisit` GROUP BY `uploadDate`, `uploadTime`
            
            UNION SELECT 'Sick child form' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_sickChildForm` GROUP BY `uploadDate`, `uploadTime`
            
            UNION SELECT 'Supervision visit log' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_supervisionvisitlog` GROUP BY `uploadDate`, `uploadTime`
            
            UNION SELECT 'Vaccine tracker' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_vaccinetracker` GROUP BY `uploadDate`, `uploadTime`
            
            UNION SELECT 'Arrival check log' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_arrivalchecklog` GROUP BY `uploadDate`, `uploadTime`
            
            UNION SELECT 'Departure check log' AS `formType`, DATE(meta_insertDatetime) AS `uploadDate`, 
            DATE_FORMAT(meta_insertDatetime,'%h:%i %p') AS `uploadTime`, count(1) AS `numRecords`, meta_uploadUser AS `uploadUser`
            FROM `lastmile_chwdb`.`staging_odk_departurechecklog` GROUP BY `uploadDate`, `uploadTime`
            
            ORDER BY `uploadDate` DESC, `uploadUser` DESC, `uploadTime` DESC;

        ";


        $result = mysqli_query($cxn, $queryString);
        while ( $row = mysqli_fetch_assoc($result) ) {
            extract($row);
            $tableRow = "<tr>";
            $tableRow .= "<td>$uploadDate</td>";
            $tableRow .= "<td>$uploadTime</td>";
            $tableRow .= "<td>$uploadUser</td>";
            $tableRow .= "<td>$formType</td>";
            $tableRow .= "<td>$numRecords</td>";
            $tableRow .= "</tr>";
            echo $tableRow;
        }

    ?>
</table>
