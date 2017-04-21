
<!-- !!!!! Datepicker CSS needs to be fixed; see page_dataPortal.css !!!!! -->
<!--<link rel="stylesheet" href="../../lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />-->

<style>
    .myTable { border-collapse:collapse; }
    .myTable th { border: 1px solid black; padding:5px; }
    .myTable td { border: 1px solid black; padding:5px; }
</style>

<script>
$(document).ready(function(){

    // Apply jQueryUI datepicker (MySQL date format)
    $("#startDate, #endDate").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('#runReport').click(function(){
        
        LMD_utilities.ajaxButton($('#runReport'),'ajaxLoader');
        
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();

        if (startDate!='' & endDate!='') {
            var myLocation = "../fragments_portal/frag_lmsQA.php";
            myLocation += "?startDate=" + startDate;
            myLocation += "&endDate=" + endDate;
            
            $('#mainContainer').load(myLocation, function(responseText, textStatus, jqXHR){
                if (textStatus === "error") {
                    // Display error message
                    $('#mainContainer').html("<h1>Error.</h1><h3>Please check your internet connection and try again later.</h3>");
                }
            });
            
        }
        else {
            alert("Please select a start date and end date.");
        }
    });

});
</script>

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

        // Set maximum query execution time
        set_time_limit(300);
//                ini_set('MAX_EXECUTION_TIME', 300);

        // Set queryString; run query; extract data (ALL AGES)
        $queryString = "SELECT EnumeratorID, enumeratorName, sum(numHH) as numHH, sum(numFem) as numFem, sum(numGen) as numGen, sum(numChi) as numChi, sum(err_FBD) as err_FBD, sum(err_anyBirths) as err_anyBirths, sum(err_birthsNo5Yes2) as err_birthsNo5Yes2, sum(err_diffDOB) as err_diffDOB, sum(err_childDeath) as err_childDeath, sum(err_ebola1) as err_ebola1, sum(err_ebola2) as err_ebola2, sum(err_chiProvider) as err_chiProvider FROM lastmile_lms.view_qa_master WHERE AutoDate>='$startDate' && AutoDate<='$endDate' GROUP BY EnumeratorID;";
        $result = mysqli_query($cxn, $queryString);

        // Display header and report description
        echo "<h2><b>LMS QA Report</b></h2>";
        echo "<h3>From <b>$startDate</b> to <b>$endDate</b></h3>";
        echo "<ul>";
        echo "<li><b>EnumID</b>. ID number of the enumerator.</li>";
        echo "<li><b># HH Q</b>. The number of household questionnaires that the enumerator completed.</li>";
        echo "<li><b># FEM Q</b>. The number of female questionnaires that the enumerator completed.</li>";
        echo "<li><b># GEN Q</b>. The number of general questionnaires that the enumerator completed.</li>";
        echo "<li><b># CHI Q</b>. The number of child questionnaires that the enumerator completed.</li>";
        echo "<li><b>Err: FBD</b>. Gave different answers for \"was your most recent birth in a health facility?\" question (recent birth section vs. birth history).</li>";
        echo "<li><b>Err: Any births</b>. Gave different answers for \"have you ever given birth?\" question (recent birth section vs. birth history).</li>";
        echo "<li><b>Err: Last birth</b>. Said \"I have not given birth in the last five years\" and \"I have given birth in the last two years\" (recent birth section vs. birth history).</li>";
        echo "<li><b>Err: Different DOB</b>. Gave different answers for \"what was the date of birth of your most recently birthed child?\" question (recent birth section vs. birth history).</li>";
        echo "<li><b>Err: Child death</b>. Gave different answers for \"is your most recently birthed child still alive?\" question (recent birth section vs. birth history).</li>";
        echo "<li><b>Err: Ebola 1</b>. Said both \"refused to respond\" and another answer (Ebola knowledge questions).</li>";
        echo "<li><b>Err: Ebola 2</b>. Skipped a required question (Ebola transmission questions).</li>";
        echo "<li><b>Err: Child Tx</b>. Said that the child received tratment FIRST from a provider that they did not see.</li>";
        echo "</ul><br>";

        // Parse into results
        echo "<table class='myTable'><tr>";
        echo "<th>Enum ID</th>";
        echo "<th>Enum Name</th>";
        echo "<th># HH Q</th>";
        echo "<th># FEM Q</th>";
        echo "<th># GEN Q</th>";
        echo "<th># CHI Q</th>";
        echo "<th>Err: FBD</th>";
        echo "<th>Err: Any births</th>";
        echo "<th>Err: Last birth</th>";
        echo "<th>Err: Different DOB</th>";
        echo "<th>Err: Child death</th>";
        echo "<th>Err: Ebola 1</th>";
        echo "<th>Err: Ebola 2</th>";
        echo "<th>Err: Child Tx</th>";
        echo "</tr>";

        while ( $row = mysqli_fetch_assoc($result) )
        {
            extract($row);

            echo "<tr>";
            echo "<td>$EnumeratorID</td>";
            echo "<td>$enumeratorName</td>";
            echo "<td>$numHH</td>";
            echo "<td>$numFem</td>";
            echo "<td>$numGen</td>";
            echo "<td>$numChi</td>";
            echo "<td>$err_FBD</td>";
            echo "<td>$err_anyBirths</td>";
            echo "<td>$err_birthsNo5Yes2</td>";
            echo "<td>$err_diffDOB</td>";
            echo "<td>$err_childDeath</td>";
            echo "<td>$err_ebola1</td>";
            echo "<td>$err_ebola2</td>";
            echo "<td>$err_chiProvider</td>";
            echo "</tr>";
        }

        echo "</table><br>";

    }

?>
