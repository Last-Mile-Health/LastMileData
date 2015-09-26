<script>
<?php
    // Set $indicatorIDs manually (!!!!! for now !!!!!)
    $indIDString = "16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45";
    echo "var indIDString = '$indIDString';". "\n\n";

    // Include file that interacts with LMD_REST.php
    // This file assigns two javascript objects: "data_rawValues" and "data_indicators"
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
    $reportObjectString = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16";
    require_once("echoIndicatorsAndValues.php");
?>

// Load main script
$.getScript('../js/frag_konobo.js');
    
</script>

<h1>Konobo CHW Report</h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_konobo">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.roMetadata_name}}</h3>
            <p><b>Definition</b>: {{report_object.roMetadata_description}}</p>
            <p rv-if="report_object.roMetadata_target"><b>FY15 Target</b>: {{report_object.roMetadata_target | format report_object.roMetadata_format}}</p>
            
            
            <!-- !!!!! NEW !!!!! -->
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
            <!-- !!!!! NEW !!!!! -->
            
            
            <!-- !!!!! OLD !!!!! -->
<!--            <table class='ptg_data'>
                <tr>
                    <th rv-if="report_object.data.multiple">&nbsp;</th>
                    <th rv-each-date="report_object.data.dates">{{date | shortDate}}</th>
                </tr>
                <tr rv-each-values="report_object.data.values">
                    <td rv-if="report_object.data.multiple">{{values.name}}</td>
                    <td rv-each-value="values.values">{{value | format report_object.roMetadata_format}}</td>
                </tr>
            </table>-->
            <!-- !!!!! OLD !!!!! -->
            
            
            <hr class='smallHR'>
            <p rv-if="report_object.roMetadata_narrative"><b>Progress-to-goal</b>: {{report_object.roMetadata_narrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.chart_div"></div>
        </div>
    </div>
</div>