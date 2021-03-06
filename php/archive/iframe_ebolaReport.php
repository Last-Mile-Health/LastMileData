<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>RM&E - Ebola Report</title>
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
                    var myLocation = "/LastMileData/php/other/iframe_ebolaReport.php";
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
                $queryString = "SELECT SUM(if((screening_A || screening_B || screening_C || screening_D || screening_E) && sex='M',1,0)) AS sumScreenedMale, SUM(if((screening_A || screening_B || screening_C || screening_D || screening_E) && sex='F',1,0)) AS sumScreenedFemale, SUM(if(education_A || education_B || education_C || education_D || education_E,1,0)) AS sumEducated FROM lastmile_db.view_ebolaeducationscreening LEFT JOIN lastmile_db.`view_reg_current-population_old` ON view_ebolaeducationscreening.memberID=`view_reg_current-population_old`.memberID WHERE visitDate>='$startDate' && visitDate<='$endDate';";
                $result = mysqli_query($cxn, $queryString);
                $row = mysqli_fetch_assoc($result);
                extract($row);
                
                // Calculate total
                $sumScreenedTotal = $sumScreenedMale + $sumScreenedFemale;
                
                // Parse into results
                echo "From <b>$startDate</b> to <b>$endDate</b>:<br><br>";
                echo "Number screened for Ebola: <b>$sumScreenedTotal</b><br><br>";
                echo "Number educated about Ebola: <b>$sumEducated</b><br>";
                
            }
            
        ?>
    </body>
</html>
