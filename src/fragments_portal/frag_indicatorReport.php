<script>
<?php

    // Extract 'reportID' and 'reportTitle' parameters
    $reportID = $_GET['reportID'];
    
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (report model)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reportObjects/0/$reportID";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);
    
    // Get instance IDs of all indicator instances used in the report (in either data tables or charts)
    if (substr($json1,0,1)!=="[") {
        $json1 = "[" . $json1 . "]";
    }
    $instIDString = "";
    foreach (json_decode($json1) as $value) {
        $instIDString .= $value->instIDs. ",";
        $instIDString .= $value->chart_instIDs. ",";
        $instIDString .= $value->chart_instIDs_secondary. ",";
    }
    $instIDString = trim($instIDString, ",");

    // Echo JSON (indicator instance metadata)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorInstances/1/$instIDString";
    curl_setopt($ch,CURLOPT_URL,$url2);
    $json2 = curl_exec($ch);

    // Echo JSON (indicator instance data)
    $url3 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/instanceValues/$instIDString";
    curl_setopt($ch,CURLOPT_URL,$url3);
    $json3 = curl_exec($ch);
    
    // !!!!! DEV !!!!!
    $ind_id_string = "28";
    $territory_id_string = "14,16,17,18,26";
    $url4 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/test_values/$ind_id_string/$territory_id_string";
    curl_setopt($ch,CURLOPT_URL,$url4);
    $json4 = curl_exec($ch);
    // !!!!! DEV !!!!!

    // Echo report title
    $url4 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reports/0/$reportID";
    curl_setopt($ch,CURLOPT_URL,$url4);
    $reportName = JSON_decode(curl_exec($ch))->reportName;
    $headerNote = JSON_decode(curl_exec($ch))->headerNote;

    // Close CURL session and echo JSON
    // JSON consists of 3 javascript objects: data_indicators, data_rawValues, [model_report]
    curl_close($ch);
    echo "var reportObjects = $json1;". "\n\n";
    echo "var indicatorInstances = $json2;". "\n\n";
    echo "var instanceValues = $json3;". "\n\n";
    // !!!!! DEV !!!!!
    echo "var instance_values = $json4;". "\n\n";
//    echo "console.log('indicatorInstances');";
//    echo "console.log(indicatorInstances);";
    // !!!!! DEV !!!!!
    
?>

    // Bootstrap the page
    LMD_dataPortal.bootstrap(instance_values, indicatorInstances, reportObjects);

</script>

<div id='reportContent'>
    <h1><?php echo $reportName; ?></h1>
    <p><?php echo $headerNote; ?></p>
    <div data-bind="foreach: {data:reportObjects, as:'ro'}">
        <div class='row'>
            <hr style="margin:15px; border:1px solid #eee;">
            <div class='col-md-5'>
                <h3 data-bind="html: '<b>' + ($index()+1) + '</b>. ' + ro_name"></h3>
                <p><b>Definition</b>: <span data-bind="text:ro_description"></span></p>
                <p data-bind="if:indSource"><b>Data source</b>: <span data-bind="text:indSource"></span></p>
                <p data-bind="if:ro_target"><b>Target</b>: <span data-bind="text:ro_target"></span></p>
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
                    
                    <!-- ko foreach:instances_table -->
                    <tr>
                        <!-- Table labels will be dynamically placed here -->
                        <!-- ko if:ro.multiple -->
                        <td class="label_table" data-bind="attr: {'data-inst_id':$data}"></td>
                        <!-- /ko -->
                        
                        <!-- Indicator values will be dynamically placed here -->
                        <!-- ko foreach: $parents[1].lastFourMonths -->
                        <td class="inst_value" data-bind="attr: {'data-yearmonth':yearMonth, 'data-inst_id':$parentContext.$data}"></td>
                        <!-- /ko -->
                    </tr>
                    <!-- /ko -->
                </table>
                
                <hr class='smallHR'>
                
                <!-- ko if: ro_narrative -->
                <p><b>Narrative</b>: <span data-bind="text:ro_narrative"></span></p>
                <!-- /ko -->
                
                <p>
                    <a download="data.csv" class="downloadData btn btn-info btn-sm" data-bind="attr:{id:'download_'+$index()}">Download data</a>
                    <span class="downloadChart btn btn-info btn-sm">Download chart</span>
                </p>
                
            </div>
            <div class='col-md-7'>
                <div data-bind="attr: {id:chart_div}"></div>
            </div>
        </div>
    </div>
    
    <!-- Hidden elements used to download charts -->
    <div id="svgdataurl" style="display:none"></div>
    <div id="pngdataurl" style="display:none"></div>
    <canvas width="590" height="380" style="display:none"></canvas>
    
</div>