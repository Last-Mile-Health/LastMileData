<script>
<?php
    // Set $indicatorIDs manually (!!!!! for now !!!!!)
    $indIDString = "23,48,49,50,51,52,53,54,55";
    echo "var indIDString = '$indIDString';". "\n\n";

    // Include file that interacts with LMD_REST.php
    // This file assigns two javascript objects: "data_rawValues" and "data_indicators"
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
    require_once("echoIndicatorsAndValues.php");
?>

// Load main script
$.getScript('../js/frag_konoboSupervision.js');

</script>

<h1>Konobo Supervision Report</h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_konobo">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.roMetadata.indName}}</h3>
            <p><b>Definition</b>: {{report_object.roMetadata.indDefinition}}</p>
            <p rv-if="report_object.roMetadata.indTarget"><b>FY15 Target</b>: {{report_object.roMetadata.indTarget | format report_object.roMetadata.indFormat}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-if="report_object.data.multiple">&nbsp;</th>
                    <th rv-each-date="report_object.data.dates">{{date | shortDate}}</th>
                </tr>
                <tr rv-each-values="report_object.data.values">
                    <td rv-if="report_object.data.multiple">{{values.name}}</td>
                    <td rv-each-value="values.values">{{value | format report_object.roMetadata.indFormat}}</td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p rv-if="report_object.roMetadata.indNarrative"><b>Progress-to-goal</b>: {{report_object.roMetadata.indNarrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.chartSpecs.div"></div>
        </div>
    </div>
</div>