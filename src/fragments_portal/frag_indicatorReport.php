<script>
<?php

    // Extract 'reportID' and 'reportTitle' parameters
    $reportID = $_GET['reportID'];
    
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (report model)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reportObjects/$reportID";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);
    
    // Get indicator IDs of all indicators used in the report (in either data tables or charts)
    if (substr($json1,0,1)!=="[") {
        $json1 = "[" . $json1 . "]";
    }
    $instIDString = "";
    foreach (json_decode($json1) as $value) {
        $instIDString .= $value->instIDs. ",";
        $instIDString .= $value->chart_instIDs. ",";
    }
    $instIDString = trim($instIDString, ",");

    // Echo JSON (indicator metadata)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorInstances/$instIDString";
    curl_setopt($ch,CURLOPT_URL,$url2);
    $json2 = curl_exec($ch);

    // Echo JSON (indicator data)
    $url3 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/instanceValues/$instIDString";
    curl_setopt($ch,CURLOPT_URL,$url3);
    $json3 = curl_exec($ch);

    // Echo report title
    $url4 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reports/$reportID";
    curl_setopt($ch,CURLOPT_URL,$url4);
    $reportTitle = JSON_decode(curl_exec($ch))->reportName;

    // Close CURL session and echo JSON
    // JSON consists of 3 javascript objects: data_indicators, data_rawValues, [model_report]
    // !!!!! Note: this fails if report has only one indicator !!!!!
    curl_close($ch);
    echo "var reportObjects = $json1;". "\n\n";
    echo "var indicatorInstances = $json2;". "\n\n";
    echo "var instanceValues = $json3;". "\n\n";
    
?>

    // Bootstrap the page
    LMD_dataPortal.bootstrap(instanceValues, indicatorInstances, reportObjects);

</script>

<div id='reportContent'>
    <h1><?php echo $reportTitle; ?></h1>
    <div data-bind="foreach: {data:reportObjects, as:'ro'}">
        <div class='row'>
            <hr style="margin:15px; border:1px solid #eee;">
            <div class='col-md-4'>
                <h3 data-bind="html: '<b>' + ($index()+1) + '</b>. ' + roMetadata_name"></h3>
                <p><b>Definition</b>: <span data-bind="text:roMetadata_description"></span></p>
                <p data-bind="if:roMetadata_target"><b>FY16 Target</b>: <span data-bind="text: LMD_utilities.format_number(roMetadata_target,roMetadata_targetFormat)"></span></p>
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
                    
                    <!-- ko foreach:instIDs -->
                    <tr>
                        <!-- Indicator shortnames will be dynamically placed here -->
                        <!-- ko if:ro.multiple -->
                        <td class="instShortName" data-bind="attr: {'data-instid':$data}"></td>
                        <!-- /ko -->
                        
                        <!-- Indicator values will be dynamically placed here -->
                        <!-- ko foreach: $parents[1].lastFourMonths -->
                        <td class="instValue" data-bind="attr: {'data-yearmonth':yearMonth, 'data-instid':$parentContext.$data}"></td>
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