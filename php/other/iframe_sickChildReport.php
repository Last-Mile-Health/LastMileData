<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>RM&E - Sick Child Report</title>
        <style>
            #myTable { border-collapse:collapse; }
            #myTable th { border: 1px solid black; padding:5px; }
            #myTable td { border: 1px solid black; padding:5px; }
        </style>
        <script src="../../lib/jquery.min.js"></script>
        <script src="../../lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="../../lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
        <link rel="stylesheet" href="../../lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="../../lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <script>
        $(document).ready(function(){
            
            // Apply jQueryUI datepicker (MySQL date format)
            $("#startDate, #endDate").datepicker({
                dateFormat: 'yy-mm-dd'
            });
            
            $('#runReport').click(function(){
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var district = $('#district').val();
                
                if (startDate!='' & endDate!='') {
                    var myLocation = "/LastMileData/php/other/iframe_sickChildReport.php";
                    myLocation += "?startDate=" + startDate;
                    myLocation += "&endDate=" + endDate;
                    myLocation += "&district=" + district;
                    location.assign(myLocation);
                }
                else {
                    alert("Please select a start date and end date.");
                }
            });
            
        });
        </script>
    </head>
    <body>
        <hr>Start date: <input id='startDate'>&nbsp;&nbsp;&nbsp;
        End date: <input id='endDate'>&nbsp;&nbsp;&nbsp;
        District: <select id="district">
            <option value="All">All districts</option>
            <option value="Konobo">Konobo</option>
            <option value="Gboe-Ploe">Gboe-Ploe</option>
        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button id='runReport' class='btn btn-primary' style='width:200px'>Run Report</button><hr>
        
        <?php

            if (isset($_GET['startDate'])) {
                
                // Set include path; require connection strings
                set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
                require_once("cxn.php");
                
                // Get startDate and endDate
                $startDate = $_GET['startDate'];
                $endDate = $_GET['endDate'];
                $district = $_GET['district'];
                
                // Parse districtSubquery
                switch ($district) {
                    case 'All':
                        $districtSubquery = 1;
                        break;
                    case 'Konobo':
                        $districtSubquery = "fhwID IN (SELECT pk_staff FROM lastmile_db.tbl_data_staff INNER JOIN lastmile_db.tbl_assc_staff_village ON pk_staff=fk_staff INNER JOIN lastmile_db.tbl_data_village ON fk_village=pk_village INNER JOIN lastmile_db.tbl_data_district ON fk_district=pk_district "
                            . "WHERE districtName IN ('Konobo','Glio-Twarbo'))";
                        break;
                    case 'Gboe-Ploe':
                        $districtSubquery = "fhwID IN (SELECT pk_staff FROM lastmile_db.tbl_data_staff INNER JOIN lastmile_db.tbl_assc_staff_village ON pk_staff=fk_staff INNER JOIN lastmile_db.tbl_data_village ON fk_village=pk_village INNER JOIN lastmile_db.tbl_data_district ON fk_district=pk_district "
                            . "WHERE districtName IN ('Gboe-Ploe'))";
                        break;
                }
                
                // Set queryString; run query; extract data (ALL AGES)
                $queryString = "SELECT SUM(C_diarrhea_giveORS) AS refer_diarrhea, SUM(C_fever_giveACT) AS refer_malaria, SUM(C_ari_giveAmox) AS refer_ARI, SUM(D_diarrhea_giveORS) AS treat_diarrhea, SUM(D_fever_giveACT) AS treat_malaria, SUM(D_ari_giveAmox) AS treat_ARI FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE visitDate>='$startDate' && visitDate<='$endDate' && $districtSubquery;";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Parse into results
                echo "From <b>$startDate</b> to <b>$endDate</b>:<br><br>";
                echo "<b>All ages:</b><br>";
                echo "Referred (malaria): <b>$refer_malaria</b><br>";
                echo "Referred (diarrhea): <b>$refer_diarrhea</b><br>";
                echo "Referred (ARI): <b>$refer_ARI</b><br>";
                echo "Treated at home (malaria): <b>$treat_malaria</b><br>";
                echo "Treated at home (diarrhea): <b>$treat_diarrhea</b><br>";
                echo "Treated at home (ARI): <b>$treat_ARI</b><br><br>";
                
                // Set queryString; run query; extract data (Ages 0-1)
                $queryString = "SELECT SUM(C_diarrhea_giveORS) AS refer_diarrhea, SUM(C_fever_giveACT) AS refer_malaria, SUM(C_ari_giveAmox) AS refer_ARI, SUM(D_diarrhea_giveORS) AS treat_diarrhea, SUM(D_fever_giveACT) AS treat_malaria, SUM(D_ari_giveAmox) AS treat_ARI FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE visitDate>='$startDate' && visitDate<='$endDate' && $districtSubquery && ((IFNULL(childAge_years,0)*12)+IFNULL(childAge_months,0)) < 12;";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Parse into results
                echo "<b>Children 0-1 years:</b><br>";
                echo "Referred (malaria): <b>$refer_malaria</b><br>";
                echo "Referred (diarrhea): <b>$refer_diarrhea</b><br>";
                echo "Referred (ARI): <b>$refer_ARI</b><br>";
                echo "Treated at home (malaria): <b>$treat_malaria</b><br>";
                echo "Treated at home (diarrhea): <b>$treat_diarrhea</b><br>";
                echo "Treated at home (ARI): <b>$treat_ARI</b><br><br>";
                
                // Set queryString; run query; extract data (Ages 1-5)
                $queryString = "SELECT SUM(C_diarrhea_giveORS) AS refer_diarrhea, SUM(C_fever_giveACT) AS refer_malaria, SUM(C_ari_giveAmox) AS refer_ARI, SUM(D_diarrhea_giveORS) AS treat_diarrhea, SUM(D_fever_giveACT) AS treat_malaria, SUM(D_ari_giveAmox) AS treat_ARI FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE visitDate>='$startDate' && visitDate<='$endDate' && $districtSubquery && ((IFNULL(childAge_years,0)*12)+IFNULL(childAge_months,0)) >= 12 && ((IFNULL(childAge_years,0)*12)+IFNULL(childAge_months,0)) < 72;";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Parse into results
                echo "<b>Children 1-5 years:</b><br>";
                echo "Referred (malaria): <b>$refer_malaria</b><br>";
                echo "Referred (diarrhea): <b>$refer_diarrhea</b><br>";
                echo "Referred (ARI): <b>$refer_ARI</b><br>";
                echo "Treated at home (malaria): <b>$treat_malaria</b><br>";
                echo "Treated at home (diarrhea): <b>$treat_diarrhea</b><br>";
                echo "Treated at home (ARI): <b>$treat_ARI</b><br><br>";
                
                // Set queryString; run query; extract data (sick child visit source)
                $queryString = "SELECT visitSource, sum(if(visitSource='noticed child was sick',1,0)) AS 'noticed_child_was_sick', sum(if(visitSource='other',1,0)) AS 'other', sum(if(visitSource='parent came to me',1,0)) AS 'parent_came_to_me', sum(if(visitSource='well child visit',1,0)) AS 'well_child_visit' FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE visitDate>='$startDate' && visitDate<='$endDate' && $districtSubquery;";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Parse into results
                echo "<b>Visit Source:</b><br>";
                echo "Noticed child was sick: <b>$noticed_child_was_sick</b><br>";
                echo "Parent came to me: <b>$parent_came_to_me</b><br>";
                echo "Well child visit: <b>$well_child_visit</b><br>";
                echo "Other: <b>$other</b><br><br>";
                
                // Set queryString; run query; extract data (sick child visit source)
                $queryString = "SELECT sum(B_treat) as totalTreated, sum(if(F_followup_date_1,1,0)*if(F_followup_date_2,1,0)*if(F_followup_date_3,1,0)) as threeDaysfollowUp, sum(if(F_followup_date_1,1,0)*if(F_followup_date_2,1,0)*if(F_followup_date_3,1,0))/sum(B_treat) as fuRate FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE B_treat=1 && visitDate>='$startDate' && visitDate<='$endDate' && $districtSubquery;";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Parse into results
                $fuRateFormatted = number_format(100*$fuRate,1);
                echo "<b>Follow-up rates:</b><br>";
                echo "Total treated: <b>$totalTreated</b><br>";
                echo "Total who received 3 days of follow-up: <b>$threeDaysfollowUp</b><br>";
                echo "Follow-up rate: <b>$fuRateFormatted%</b><br><br>";
                
                // Set queryString; run query; extract data (sick child visit source)
                $queryString = "SELECT sum(if(A_cough=1 && A_cough_howLong<=3 && (C_ari_giveAmox || D_ari_giveAmox),1,0))/(sum(if(A_cough=1 && A_cough_howLong<=3 && (C_ari_giveAmox || D_ari_giveAmox),1,0))+sum(if(A_cough=1 && A_cough_howLong>3 && (C_ari_giveAmox || D_ari_giveAmox),1,0))) as ari_pctTreatedWithinThree, sum(if(A_fever=1 && A_fever_howLong<=3 && (C_fever_giveACT || D_fever_giveACT),1,0))/(sum(if(A_fever=1 && A_fever_howLong<=3 && (C_fever_giveACT || D_fever_giveACT),1,0))+sum(if(A_fever=1 && A_fever_howLong>3 && (C_fever_giveACT || D_fever_giveACT),1,0))) as malaria_pctTreatedWithinThree, sum(if(A_diarrhea=1 && A_diarrhea_howLong<=3 && (C_diarrhea_giveORS || D_diarrhea_giveORS),1,0))/(sum(if(A_diarrhea=1 && A_diarrhea_howLong<=3 && (C_diarrhea_giveORS || D_diarrhea_giveORS),1,0))+sum(if(A_diarrhea=1 && A_diarrhea_howLong>3 && (C_diarrhea_giveORS || D_diarrhea_giveORS),1,0))) as diarrhea_pctTreatedWithinThree FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE B_treat=1 && visitDate>='$startDate' && visitDate<='$endDate' && $districtSubquery;";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Parse into results
                $malaria_pctFormatted = number_format(100*$malaria_pctTreatedWithinThree,1);
                $diarrhea_pctFormatted = number_format(100*$diarrhea_pctTreatedWithinThree,1);
                $ari_pctFormatted = number_format(100*$ari_pctTreatedWithinThree,1);
                echo "<b>Percentage treated within three days:</b><br>";
                echo "Percentage treated within 3 days (malaria): <b>$malaria_pctFormatted%</b><br>";
                echo "Percentage treated within 3 days (diarrhea): <b>$diarrhea_pctFormatted%</b><br>";
                echo "Percentage treated within 3 days (ARI): <b>$ari_pctFormatted%</b><br><br>";
                
            }
            
        ?>
    </body>
</html>
