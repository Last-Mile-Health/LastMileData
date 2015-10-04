<script>
<?php
    // Include file that fetches data via LMD_REST.php
    extract($_GET);
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
    require_once("echoIndicatorsAndValues.php");
?>

    // Bootstrap the page
    LMD_dataPortal.bootstrap(data_rawValues, data_indicators, model_report);

</script>

<div id='reportContent'>
    <h1><?php echo $reportTitle; ?></h1>
    <div class='row' rv-each-report_object="model_report">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.roMetadata_name}}</h3>
            <p><b>Definition</b>: {{report_object.roMetadata_description}}</p>
            <p rv-if="report_object.roMetadata_target"><b>FY16 Target</b>: {{report_object.roMetadata_target | format report_object.roMetadata_format}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-if="report_object.multiple">&nbsp;</th>
                    <th rv-each-lastfour="lastFourMonths">{{lastfour.shortMonth}}</th>
                </tr>
                <tr rv-each-indid="report_object.indicators">
                    <!-- Indicator shortnames will be dynamically placed here -->
                    <td class="indShortName" rv-if="report_object.multiple" rv-data-indid="indid"></td>
                    <!-- Indicator values will be dynamically placed here -->
                    <td rv-each-lastfour="lastFourMonths" class="indValue" rv-data-yearmonth="lastfour.yearMonth" rv-data-indid="indid" rv-data-format="report_object.roMetadata_format"></td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p rv-if="report_object.roMetadata_narrative"><b>Narrative</b>: {{report_object.roMetadata_narrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.chart_div"></div>
        </div>
    </div>
</div>