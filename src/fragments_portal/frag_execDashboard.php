<script>
<?php
    // Set $indicatorIDs manually (!!!!! for now !!!!!)
    $indIDString = "1,2,3,4,7,8,56,57,58,59,60,14,15";
    echo "var indIDString = '$indIDString';". "\n\n";

    // Include file that interacts with LMD_REST.php
    // This file assigns two javascript objects: "data_rawValues" and "data_indicators"
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
    require_once("echoIndicatorsAndValues.php");

?>

// Load main script
$.getScript('../js/frag_execDashboard.js');
    
</script>

<h1>Executive Dashboard</h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_execDashboard">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.indicatorMetadata.indName}}</h3>
            <p><b>Definition</b>: {{report_object.indicatorMetadata.indDefinition}}</p>
            <p rv-if="report_object.indicatorMetadata.indTarget"><b>FY16 Target</b>: {{report_object.indicatorMetadata.indTarget | format report_object.indicatorMetadata.indFormat}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-each-rdata="report_object.indicatorMetadata.recentData">{{rdata.date | shortDate}}</th>
                </tr>
                <tr>
                    <td rv-each-rdata="report_object.indicatorMetadata.recentData">{{rdata.value | format report_object.indicatorMetadata.indFormat}}</td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p rv-if="report_object.indicatorMetadata.indNarrative"><b>Narrative</b>: {{report_object.indicatorMetadata.indNarrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.indicatorMetadata.divID"></div>
        </div>
    </div>
</div>