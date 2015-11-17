<link rel="stylesheet" href="../css/admin_editData.css" type="text/css" />

<script>
<?php
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (indicator METADATA)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicators/";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);

    // Echo JSON (indicator DATA)
    $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicatorvalues/";
    curl_setopt($ch,CURLOPT_URL,$url2);
    $json2 = curl_exec($ch);

    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var indicatorList = $json1;". "\n\n";
    echo "var indicatorValues = $json2;". "\n\n";
?>

// Load main script
$.getScript('../js/admin_editData.js');

</script>    

<div id="outerDiv">
        
    <h2>Edit data</h2>
    
    <div class="tableContainer">
        <table>
            <thead id="adminHeader">
                <tr>
                    <th class="pad darkBlue">Category</th>
                    <th class="pad darkBlue">Indicator name</th>
                    <th class="pad darkBlue">Cut</th>
                    <!-- ko foreach:monthList.months -->
                    <th class="pad darkBlue" data-bind="text:string">hey</th>
                    <!-- /ko -->
                </tr>
            </thead>
            <tbody id="scrollContent" data-bind="foreach:indicators">
                <tr class="filterRow">
                    <td class="pad filterCategory" data-bind="text:indCategory"></td>
                    <td class="pad" data-bind="text:indName"></td>
                    <td class="pad filterCut" data-bind="text:indCut"></td>
                    <!-- ko foreach: $root.monthList.months -->
                    <td>
                        <input class="admin_input" data-bind="event: {click:$root.actions.aiClick, change:$root.actions.aiChange}, attr: {'data-indid':$parent.indID, 'data-month':month, 'data-year':year}">
                    </td>
                    <!-- /ko -->
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin:5px; font-size:150%">
        Filter:&nbsp;
        <select class="dataFilter" id="filter_category" data-bind="foreach:selects.category" style="width:150px">
            <option data-bind="text:$data"></option>
        </select>
        <select class="dataFilter" id="filter_cut" data-bind="foreach:selects.cut" style="width:150px">
            <option data-bind="text:$data"></option>
        </select>

        <button id="btn_showThree" class="btn btn-primary">Show 3 more months</button>
        <button id="btn_submit" class="btn btn-primary">Submit changes</button>
        <!--<button id="btn_revert" class="btn btn-danger">Revert changes</button>--> <!-- !!!!! Create "Revert changes" button !!!!! -->
    </div>

</div>
