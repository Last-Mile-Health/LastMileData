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
                    <th class="pad">Category</th>
                    <th class="pad">Indicator name</th>
                    <th class="pad">Cut</th>
                    <th rv-each-month="adminModel.monthList.months">{{month.string}}</th>
                </tr>
            </thead>
            <tbody id="scrollContent">
                <tr class="filterRow" rv-each-indicator="adminModel.indicators">
                    <td class="pad filterCategory">{{indicator.indCategory}}</td>
                    <td class="pad">{{indicator.indName}}</td>
                    <td class="pad filterCut">{{indicator.indCut}}</td>
                    <td rv-each-month="adminModel.monthList.months">
                        <!-- !!!!! incorporate two-way formatter with indicator.indFormat !!!!! -->
                        <input class="admin_input" rv-on-click="adminModel.actions.aiClick" rv-on-change="adminModel.actions.aiChange" rv-data-indid="indicator.indID" rv-data-month="month.month" rv-data-year="month.year">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin:5px; font-size:150%">
        Filter:&nbsp;
        <select class="dataFilter" id="filter_category" style="width:150px">
            <option rv-each-option="adminModel.selects.category">{{option}}</option>
        </select>
        <select class="dataFilter" id="filter_cut" style="width:150px">
            <option rv-each-option="adminModel.selects.cut">{{option}}</option>
        </select>

        <button id="btn_showThree" class="btn btn-primary">Show 3 more months</button>
        <button id="btn_submit" class="btn btn-primary">Submit changes</button>
        <!--<button id="btn_revert" class="btn btn-danger">Revert changes</button>--> <!-- !!!!! Create "Revert changes" button !!!!! -->
    </div>

</div>
