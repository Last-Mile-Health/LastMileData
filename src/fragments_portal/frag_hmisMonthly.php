<style>
    .myTable { border-collapse:collapse; }
    .myTable th { border: 1px solid black; padding:5px; }
    .myTable td { border: 1px solid black; padding:5px; }
</style>

<script>
$(document).ready(function(){

    $('#reportMonth').val(<?php if (isset($_GET['reportMonth'])) echo $_GET['reportMonth'] ?>);
    $('#reportYear').val(<?php if (isset($_GET['reportYear'])) echo $_GET['reportYear'] ?>);
    $('#county').val(<?php if (isset($_GET['county'])) echo "'" . $_GET['county'] . "'" ?>);

    $('#runReport').click(function(){
        
        LMD_utilities.ajaxButton($('#runReport'),'ajaxLoader');
        
        var reportMonth = $('#reportMonth').val();
        var reportYear = $('#reportYear').val();
        var county = $('#county').val();
        var myLocation = "../fragments_portal/frag_hmisMonthly.php";
        myLocation += "?reportMonth=" + reportMonth;
        myLocation += "&reportYear=" + reportYear;
        myLocation += "&county=" + encodeURI(county);
        
        $('#mainContainer').load(myLocation, function(responseText, textStatus, jqXHR){
            if (textStatus === "error") {
                // Display error message
                $('#mainContainer').html("<h1>Error.</h1><h3>Please check your internet connection and try again later.</h3>");
            }
        });
        
    });

});
</script>

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

        // Set queryString; run query; extract data
        $queryString = "SELECT healthFacility, nReports, nARI, nMalaria_2_11_months, nMalaria_1_5_years, nDiarrhea,
            nBirths_home, nBirths_facility, nStillBirths, nDeathsNeonatal, nDeathsPostneonatal, nDeathsChild, nDeathsMaternal
            FROM lastmile_dataportal._temp_view_msr_facility WHERE $whereClause
            AND monthReported=$reportMonth AND yearReported=$reportYear";
        $result = mysqli_query($cxn, $queryString);

        echo "<h2><b>HMIS Monthly Report</b>: $reportMonth-$reportYear ($county)</h2><br><br>";
        
        
        // Table #2
        echo "<table class='myTable'><tr>";
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

        // Loop through results again to output first table
        while ( $row = mysqli_fetch_assoc($result) )
        {
            extract($row);

            $nARI_chi = round($nARI*.83,0);
            $nARI_inf = $nARI - $nARI_chi;
            $nMalaria = $nMalaria_2_11_months + $nMalaria_1_5_years;
            $nDiarrhea_chi = round($nDiarrhea*.83,0);
            $nDiarrhea_inf = $nDiarrhea - $nDiarrhea_chi;

            echo "<tr>";
            echo "<td>$healthFacility</td>";
            echo "<td>$nReports</td>";
            echo "<td>$nMalaria</td>";
            echo "<td>$nMalaria_2_11_months</td>";
            echo "<td>$nMalaria_1_5_years</td>";
            echo "<td>$nDiarrhea</td>";
            echo "<td>$nDiarrhea_inf</td>";
            echo "<td>$nDiarrhea_chi</td>";
            echo "<td>$nARI</td>";
            echo "<td>$nARI_inf</td>";
            echo "<td>$nARI_chi</td>";
            echo "</tr>";
        }

        echo "</table><br><br>";

        // Table #2
        echo "<table class='myTable'><tr>";
        echo "<th>Health Facility</th>";
        echo "<th># Births</th>";
        echo "<th># Neonatal deaths</th>";
        echo "<th># Under-five deaths</th>";
        echo "<th># Maternal deaths</th>";
        echo "<th>Total # deaths</th>";
        echo "</tr>";

        // Reset pointer to beginning of array; loop through results again to output second table
        mysqli_data_seek($result, 0);
        while ( $row = mysqli_fetch_assoc($result) )
        {
            extract($row);

            $nBirths = $nBirths_home + $nBirths_facility;
            $nU5Deaths = $nStillBirths + $nDeathsNeonatal + $nDeathsPostneonatal + $nDeathsChild;
            $totalDeaths = $nU5Deaths + $nDeathsMaternal;

            echo "<tr>";
            echo "<td>$healthFacility</td>";
            echo "<td>$nBirths</td>";
            echo "<td>$nDeathsNeonatal</td>";
            echo "<td>$nU5Deaths</td>";
            echo "<td>$nDeathsMaternal</td>";
            echo "<td>$totalDeaths</td>";
            echo "</tr>";
        }

        echo "</table><br>";

    }

?>
