<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <title>RM&E - HMIS Monthly Report</title>
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
            
            $('#reportMonth').val(<?php if (isset($_GET['reportMonth'])) echo $_GET['reportMonth'] ?>);
            $('#reportYear').val(<?php if (isset($_GET['reportYear'])) echo $_GET['reportYear'] ?>);
            $('#county').val(<?php if (isset($_GET['county'])) echo "'" . $_GET['county'] . "'" ?>);
            
            $('#runReport').click(function(){
                var reportMonth = $('#reportMonth').val();
                var reportYear = $('#reportYear').val();
                var county = $('#county').val();
                var myLocation = "/LastMileData/php/other/iframe_hmisMonthly.php";
                myLocation += "?reportMonth=" + reportMonth;
                myLocation += "&reportYear=" + reportYear;
                myLocation += "&county=" + county;
                location.assign(myLocation);
            });
            
        });
        </script>
    </head>
    <body>
        <hr>Month: 
        <select id='reportMonth'>
            <option value='1'>January</option>
            <option value='2'>February</option>
            <option value='3'>March</option>
            <option value='4'>April</option>
            <option value='5'>May</option>
            <option value='6'>June</option>
            <option value='7'>July</option>
            <option value='8'>August</option>
            <option value='9'>September</option>
            <option value='10'>October</option>
            <option value='11'>November</option>
            <option value='12'>December</option>
        </select>&nbsp;&nbsp;&nbsp;
        Year: 
        <select id='reportYear'>
            <option>2016</option>
            <option>2017</option>
            <option>2018</option>
        </select>&nbsp;&nbsp;&nbsp;
        County: <select id="county">
            <option value="All">All counties</option>
            <option value="Grand Gedeh">Grand Gedeh</option>
            <option value="Rivercess">Rivercess</option>
        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <button id='runReport' class='btn btn-primary' style='width:200px'>Run Report</button><hr>
        
        <?php

            if (isset($_GET['reportMonth'])) {
                
                // Set include path; require connection strings
                set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
                require_once("cxn.php");
                
                // Get variables
                $reportMonth = $_GET['reportMonth'];
                $reportYear = $_GET['reportYear'];
                $county = $_GET['county'];
                
                // Parse $whereClause
                switch ($county) {
                    case 'All':
                        $whereClause = 1;
                        break;
                    case 'Grand Gedeh':
                        $whereClause = "countyID=6";
                        break;
                    case 'Rivercess':
                        $whereClause = "countyID=14";
                        break;
                }
                
                // Set queryString; run query; extract data (ALL AGES)
                $queryString = "SELECT healthFacilityID, healthFacility, nReports, nARI, nMalaria, nDiarrhea
                    FROM lastmile_chwdb.view_msr_healthFacility WHERE $whereClause
                    AND monthReported=$reportMonth AND yearReported=$reportYear
                    GROUP BY healthFacilityID, monthReported, yearReported;";
                $result = mysqli_query($cxn, $queryString);
                
                echo "<h2><b>HMIS Monthly Report</b>: $reportMonth-$reportYear ($county)</h2><br><br>";
                echo "<table id='myTable'><tr>";
                echo "<th>Health Facility</th>";
                echo "<th># Reports</th>";
                echo "<th># Malaria<br>(total)</th>";
                echo "<th># Malaria<br>(2-11 mths)</th>";
                echo "<th># Malaria<br>(1-5 yrs)</th>";
                echo "<th># Diarrhea<br>(total)</th>";
                echo "<th># Diarrhea<br>(2-11 mths)</th>";
                echo "<th># Diarrhea<br>(1-5 yrs)</th>";
                echo "<th># ARI<br>(total)</th>";
                echo "<th># ARI<br>(2-11 mths)</th>";
                echo "<th># ARI<br>(1-5 yrs)</th>";
                echo "</tr>";
                
                while ( $row = mysqli_fetch_assoc($result) )
                {
                    extract($row);
                    
                    $nARI_chi = round($nARI*.83,0);
                    $nARI_inf = $nARI - $nARI_chi;
                    $nMalaria_chi = round($nMalaria*.83,0);
                    $nMalaria_inf = $nMalaria - $nMalaria_chi;
                    $nDiarrhea_chi = round($nDiarrhea*.83,0);
                    $nDiarrhea_inf = $nDiarrhea - $nDiarrhea_chi;
                    
                    echo "<tr>";
                    echo "<td>$healthFacility</td>";
                    echo "<td>$nReports</td>";
                    echo "<td>$nMalaria</td>";
                    echo "<td>$nMalaria_inf</td>";
                    echo "<td>$nMalaria_chi</td>";
                    echo "<td>$nDiarrhea</td>";
                    echo "<td>$nDiarrhea_inf</td>";
                    echo "<td>$nDiarrhea_chi</td>";
                    echo "<td>$nARI</td>";
                    echo "<td>$nARI_inf</td>";
                    echo "<td>$nARI_chi</td>";
                    echo "</tr>";
                }
                
                echo "</table><br>";
                
            }
            
        ?>
    </body>
</html>
