<!DOCTYPE html>
<!--
    !!!!! This is just a rough draft; build this out into a more streamlined reporting system/interface !!!!!
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>View sent records</title>
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
                dateFormat: 'yy-mm-dd',
            });
            
            $('#runReport').click(function(){
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                
                if (startDate!='' & endDate!='') {
                    var myLocation = "/LastMileData/php/other/iframe_sickChildReport.php";
                    myLocation += "?startDate=" + startDate;
                    myLocation += "&endDate=" + endDate;
                    location.assign(myLocation)
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
        End date: <input id='endDate'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button id='runReport' class='btn btn-primary' style='width:200px'>Run Report</button><hr>
        
        <?php

            if (isset($_GET['startDate'])) {
                
                // Set include path; require connection strings
                set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
                require_once("cxn.php");
                
                // Get startDate and endDate
                $startDate = $_GET['startDate'];
                $endDate = $_GET['endDate'];
                
                // Parse queryString; run query; extract data (ALL AGES)
                $queryString = "SELECT SUM(C_diarrhea_giveORS) AS refer_diarrhea, SUM(C_fever_giveACT) AS refer_malaria, SUM(C_ari_giveAmox) AS refer_ARI, SUM(D_diarrhea_giveORS) AS treat_diarrhea, SUM(D_fever_giveACT) AS treat_malaria, SUM(D_ari_giveAmox) AS treat_ARI FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE visitDate>='$startDate' && visitDate<='$endDate';";
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
                
                // Parse queryString; run query; extract data (Ages 0-1)
                $queryString = "SELECT SUM(C_diarrhea_giveORS) AS refer_diarrhea, SUM(C_fever_giveACT) AS refer_malaria, SUM(C_ari_giveAmox) AS refer_ARI, SUM(D_diarrhea_giveORS) AS treat_diarrhea, SUM(D_fever_giveACT) AS treat_malaria, SUM(D_ari_giveAmox) AS treat_ARI FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE visitDate>='$startDate' && visitDate<='$endDate' && ((IFNULL(childAge_years,0)*12)+IFNULL(childAge_months,0)) < 12;";
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
                
                // Parse queryString; run query; extract data (Ages 1-5)
                $queryString = "SELECT SUM(C_diarrhea_giveORS) AS refer_diarrhea, SUM(C_fever_giveACT) AS refer_malaria, SUM(C_ari_giveAmox) AS refer_ARI, SUM(D_diarrhea_giveORS) AS treat_diarrhea, SUM(D_fever_giveACT) AS treat_malaria, SUM(D_ari_giveAmox) AS treat_ARI FROM lastmile_db.tbl_data_fhw_sch_sickchild WHERE visitDate>='$startDate' && visitDate<='$endDate' && ((IFNULL(childAge_years,0)*12)+IFNULL(childAge_months,0)) >= 12 && ((IFNULL(childAge_years,0)*12)+IFNULL(childAge_months,0)) < 72;";
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
                
            }
            
        ?>
    </body>
</html>
