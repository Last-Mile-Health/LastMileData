<script>
<?php

    // Extract 'report_id' and 'report_title' parameters
    // report_id uniquely identifies a report in lastmile_dataportal.tbl_reports table.
    $report_id = $_GET['report_id'];
    
    // Initiate/configure CURL session for json1
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // Echo JSON (report model)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reportObjects/0/$report_id";

    curl_setopt( $ch, CURLOPT_URL, $url1 );

    $json1 = curl_exec( $ch );
    
    // This may be a bug, having to close the curl session after every instance of curl_setopt.
    curl_close($ch);

    // Get instance IDs of all indicator instances used in the report (in either data tables or charts)
    if (substr($json1,0,1)!=="[") {
        $json1 = "[" . $json1 . "]";
    }
    $indicatorString = "";
    $territoryString = "";
    $indicatorArray = [];
    $territoryArray = [];
    foreach (json_decode($json1) as $value) {
        
        // Create array of indicators
        $indicators = explode(",",$value->indicators_table);
        $indicators = array_merge($indicators,explode(",",$value->indicators_chart));
        foreach ($indicators as $value2) {
            if (!in_array($value2,$indicatorArray) && $value2!=="") {
                array_push($indicatorArray,$value2);
            }
        }
        
        // Create array of territories
        $territories = explode(",",$value->territories_table);
        $territories = array_merge($territories,explode(",",$value->territories_chart));
        foreach ($territories as $value2) {
            if (!in_array($value2,$territoryArray) && $value2!=="") {
                array_push($territoryArray,$value2);
            }
        }
        
    }
    
    // Parse indicator string
    $indicatorString = "";
    foreach ($indicatorArray as $value) {
        $indicatorString .= $value . ",";
    }
    $indicatorString = trim($indicatorString, ",");
    
    // Parse territory string
    $territoryString = "";
    foreach ($territoryArray as $value) {
        $territoryString .= $value . ",";
    }
    $territoryString = trim($territoryString, ",");

    // Initiate/configure CURL session for json2
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // Echo JSON (indicator metadata)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicators/1/$indicatorString";

    curl_setopt($ch,CURLOPT_URL,$url2);

    $json2 = curl_exec($ch);

    curl_close($ch);

    if (substr($json2,0,1)!=="[") {
        $json2 = "[" . $json2 . "]";
    }


    // Initiate/configure CURL session for json3
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // Echo JSON (indicator values)
    $url3 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorValues/$indicatorString/$territoryString";
    
    curl_setopt($ch,CURLOPT_URL,$url3);

    $json3 = curl_exec($ch);

    curl_close($ch);

    if (substr($json3,0,1)!=="[") {
        $json3 = "[" . $json3 . "]";
    }
    

    // Initiate/configure CURL session for json4
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // Echo JSON (indicator values)
    $url4 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/territories/$territoryString";

    curl_setopt($ch,CURLOPT_URL,$url4);

    $json4 = curl_exec($ch);

    curl_close($ch);

    if (substr($json4,0,1)!=="[") {
        $json4 = "[" . $json4 . "]";
    }
    
    // Initiate/configure CURL session for report_name and header_note
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );

    // Echo report title
    $url5 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reports/0/$report_id";
    curl_setopt($ch,CURLOPT_URL,$url5);
    $report_name = JSON_decode(curl_exec($ch))->report_name;
    $header_note = JSON_decode(curl_exec($ch))->header_note;

    // Close CURL session and echo JSON (to be used by LMD_dataPortal.js)
    curl_close($ch);

    echo "var arg_reportObjects = $json1;". "\n\n";
    echo "var arg_indicatorMetadata = $json2;". "\n\n";
    echo "var arg_indicatorValues = $json3;". "\n\n";
    echo "var arg_territoryNames = $json4;". "\n\n";
    
?>

    // Bootstrap the page
    LMD_dataPortal.bootstrap(arg_reportObjects, arg_indicatorMetadata, arg_indicatorValues, arg_territoryNames);

</script>

<div id='reportContent'>
    <h1><?php echo $report_name; ?></h1>
    <p><?php echo $header_note; ?></p>
    <div data-bind="foreach: {data:reportObjects, as:'ro'}">
        <div class='row'>
            <hr style="margin:15px; border:1px solid #eee;">
            <div class='col-md-5'>
                <h3 data-bind="html: '<b>' + ($index()+1) + '</b>. ' + ro_name"></h3>
                <p><b>Definition</b>: <span data-bind="text:ro_description"></span></p>
                <p data-bind="if:ro_source"><b>Data source</b>: <span data-bind="text:ro_source"></span></p>
                <p data-bind="if:ro_target"><b>Target</b>: <span data-bind="text:ro_target"></span></p>
                
                <!-- If the `only_display_last_month_table` variable is set to 1, display default table (months across the top) -->
                <!-- ko if:only_display_last_month_table==0 -->
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
                        <!-- Table labels -->
                        <!-- ko if:ro.multiple -->
                        <td class="label_table" data-bind="text:label, attr: {'data-inst_id':$data.inst_id}"></td>
                        <!-- /ko -->
                        
                        <!-- Indicator values will be dynamically placed here -->
                        <!-- ko foreach: $parents[1].lastFourMonths -->
                        <td class="value" data-bind="attr: {'data-yearmonth':yearMonth, 'data-inst_id':$parentContext.$data.inst_id}"></td>
                        <!-- /ko -->
                    </tr>
                    <!-- /ko -->
                </table>
                <!-- /ko -->
                
                <!-- If the `only_display_last_month_table` variable is set to 1, display alternate table (secondary cuts across the top) -->
                <!-- ko if:only_display_last_month_table==1 -->
                <table class='ptg_data'>
                    
                    <tr>
                        <th>&nbsp;</th>
                        
                        <!-- Secondary cut labels -->
                        <!-- ko foreach:labels_secondary_table -->
                        <th data-bind="text:$data"></th>
                        <!-- /ko -->
                    </tr>
                    
                    <!-- ko foreach:instances_table -->
                    <tr>
                        <!-- Table labels -->
                        <!-- ko if:ro.multiple -->
                        <td class="label_table" data-bind="text:label, attr: {'data-inst_id':$data.inst_id}"></td>
                        <!-- /ko -->
                        
                        <!-- Indicator values will be dynamically placed here -->
                        <!-- ko foreach:ro.indicators_table -->
                        <td class="value" data-bind="attr: {'data-yearmonth':$parents[2].lastFourMonths[3].yearMonth, 'data-inst_id':($data + '-' + $parentContext.$data.inst_id.split('-')[1] + '-' + $parentContext.$data.inst_id.split('-')[2])}"></td>
                        <!-- /ko -->
                    </tr>
                    <!-- /ko -->
                </table>
                <!-- /ko -->
 
                
                <!-- If the `only_display_last_month_table` variable is set to 7, display last four fiscal years ) -->
                <!-- ko if:only_display_last_month_table==7 -->
                <table class='ptg_data'>
                    
                    <tr>
                        <!-- ko if:multiple -->
                        <th>&nbsp;</th>
                        <!-- /ko -->
                        
                        <!-- Month names -->
                        <!-- ko foreach:$parents[0].last_four_fiscal_year -->
                        <th data-bind="text:shortMonth"></th>
                        <!-- /ko -->
                    </tr>
                    
                    <!-- ko foreach:instances_table -->
                    <tr>
                        <!-- Table labels -->
                        <!-- ko if:ro.multiple -->
                        <td class="label_table" data-bind="text:label, attr: {'data-inst_id':$data.inst_id}"></td>
                        <!-- /ko -->
                        
                        <!-- Indicator values will be dynamically placed here -->
                        <!-- ko foreach: $parents[1].last_four_fiscal_year -->
                        <td class="value" data-bind="attr: {'data-yearmonth':yearMonth, 'data-inst_id':$parentContext.$data.inst_id}"></td>
                        <!-- /ko -->
                    </tr>
                    <!-- /ko -->
                </table>
                <!-- /ko -->
 

                
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