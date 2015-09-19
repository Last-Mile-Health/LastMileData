<link rel="stylesheet" href="../css/admin_editIndicators.css" type="text/css" />

<script>
<?php
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // Echo JSON (indicator METADATA)
    $url = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/indicators/";
    curl_setopt($ch,CURLOPT_URL,$url);
    $json = curl_exec($ch);

    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var indicatorList = $json;". "\n\n";
?>
    
// Load main script
$.getScript('../js/admin_editIndicators.js');

</script>

<div id="outerDiv">

    <h2>Edit indicators</h2>

    <div class="tableContainer">
        <table>
            <thead id="adminHeader">
                <tr>
                    <th class="pad">Category</th>
                    <th class="pad">Indicator</th>
                    <th class="pad">Cut</th>
                    <th class="pad">Target</th>
                    <th class="pad">Narrative</th>
                    <th class="pad">X</th>
                </tr>
            </thead>
            <tbody id="scrollContent">
                <tr class="filterRow" rv-data-cid="indicator.cid" rv-each-indicator="indicators">
                    <td><input class="admin_input pad filterCategory" rv-value="indicator:indCategory" rv-on-change="actions.change" rv-on-click="actions.click"></td>
                    <td><input class="admin_input pad" rv-value="indicator:indName" rv-on-change="actions.change" rv-on-click="actions.click"></td>
                    <td><input class="admin_input pad filterCut" rv-value="indicator:indCut" rv-on-change="actions.change" rv-on-click="actions.click"></td>
                    <td><input class="admin_input pad" rv-value="indicator:indTarget" rv-on-change="actions.change" rv-on-click="actions.click"></td>
                    <td><input class="admin_input pad" rv-value="indicator:indNarrative" rv-on-change="actions.change" rv-on-click="actions.click"></td>
                    <td><button rv-data-cid="indicator.cid" rv-on-click="actions.delete" class="btn btn-xs btn-danger btn_remove">X</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin:5px; font-size:150%">
        Filter:&nbsp;
        <select class="dataFilter" id="filter_category" style="width:150px">
            <option rv-each-option="selectLists.category">{{option}}</option>
        </select>
        <select class="dataFilter" id="filter_cut" style="width:150px">
            <option rv-each-option="selectLists.cut">{{option}}</option>
        </select>

        <button id="btn_add" class="btn btn-primary">Add a new indicator</button>
        <button id="btn_submit" class="btn btn-primary">Submit changes</button>
        <!--<button id="btn_revert" class="btn btn-danger">Revert changes</button>  !!!!! Create "Revert changes" button !!!!!--> 
    </div>

</div>
