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
    <div data-bind="foreach: {data:model_report, as:'ro'}">
        <div class='row'>
            <hr style="margin:15px; border:1px solid #eee;">
            <div class='col-md-4'>
                <h3 data-bind="html: '<b>' + ($index()+1) + '</b>. ' + roMetadata_name"></h3>
                <p><b>Definition</b>: <span data-bind="text:roMetadata_description"></span></p>
                <p data-bind="if:roMetadata_target"><b>FY16 Target</b>: <span data-bind="text: LMD_utilities.format_number(roMetadata_target,roMetadata_format)"></span></p>
                <table class='ptg_data'>
                    
                    <tr>
                        <!-- ko if:multiple -->
                        <th>&nbsp;</th>
                        <!-- /ko -->
                        
                        <!-- Month names -->
                        <!-- ko foreach:$parents[0].lastFourMonths -->
                        <th data-bind="text:shortMonth"></th>
                        <!-- /ko -->
                    </tr>
                    
                    <!-- ko foreach:indicators -->
                    <tr>
                        <!-- Indicator shortnames will be dynamically placed here -->
                        <!-- ko if:ro.multiple -->
                        <td class="indShortName" data-bind="attr: {'data-indid':$data}"></td>
                        <!-- /ko -->
                        
                        <!-- Indicator values will be dynamically placed here -->
                        <!-- ko foreach: $parents[1].lastFourMonths -->
                        <td class="indValue" data-bind="attr: {'data-yearmonth':yearMonth, 'data-indid':$parentContext.$data, 'data-format':ro.roMetadata_format}"></td>
                        <!-- /ko -->
                    </tr>
                    <!-- /ko -->
                    
                </table>
                <hr class='smallHR'>
                <!-- ko if: roMetadata_narrative -->
                <p><b>Narrative</b>: <span data-bind="text:roMetadata_narrative"></span></p>
                <!-- /ko -->
            </div>
            <div class='col-md-7'>
                <div data-bind="attr: {id:chart_div}"></div>
            </div>
        </div>
    </div>
</div>