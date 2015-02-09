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
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
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
                    var myLocation = "/LastMileData/src/pages/report_ebola.php";
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
        <hr>Start date: <input id='startDate'><br><hr>
        End date: <input id='endDate'><hr>
        <button id='runReport' style='width:200px'>Run Report</button><hr>
        
        <?php

            if (isset($_GET['startDate'])) {
                
                // Set include path; require connection strings
                set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
                require_once("cxn.php");
                
                // Get startDate and endDate
                $startDate = $_GET['startDate'];
                $endDate = $_GET['endDate'];
                
                // Parse queryString; run query; extract data (ALL AGES)
                $queryString = "SELECT SUM(if((screening_A || screening_B || screening_C || screening_D || screening_E) && sex='M',1,0)) AS sumScreenedMale, SUM(if((screening_A || screening_B || screening_C || screening_D || screening_E) && sex='F',1,0)) AS sumScreenedFemale, SUM(if(education_A || education_B || education_C || education_D || education_E,1,0)) AS sumEducated FROM lastmile_db.view_ebolaeducationscreening LEFT JOIN lastmile_db.`view_reg_current-population_old` ON view_ebolaeducationscreening.memberID=`view_reg_current-population_old`.memberID WHERE visitDate>='$startDate' && visitDate<='$endDate';";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Calculate total
                $sumScreenedTotal = $sumScreenedMale + $sumScreenedFemale;
                
                // Parse into results
                echo "From <b>$startDate</b> to <b>$endDate</b>:<br><br>";
//                echo "Number screened for Ebola (male): <b>$sumScreenedMale</b><br>";
//                echo "Number screened for Ebola (female): <b>$sumScreenedFemale</b><br>";
                echo "Number screened for Ebola: <b>$sumScreenedTotal</b><br><br>";
                echo "Number educated about Ebola: <b>$sumEducated</b><br>";
                
            }
            
        ?>
        <br>
        <a href="/LastMileData/src/pages/page_deqa.html">Return to DEQA page.</a>
    </body>
</html>
