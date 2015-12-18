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
    echo "var indicators = $json;". "\n\n";
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
                    <th class="pad darkBlue">Category</th>
                    <th class="pad darkBlue">Indicator</th>
                    <th class="pad darkBlue">Target</th>
                    <th class="pad darkBlue">Narrative</th>
                    <th class="pad darkBlue">Definition</th>
                    <th class="pad darkBlue">&nbsp;X&nbsp;&nbsp;&nbsp;</th>
                </tr>
            </thead>
            <tbody id="scrollContent" data-bind="foreach:vmData">
                <tr class="filterRow">  <!-- removed: data-bind="attr: {'data-cid':cid}" -->
                    <td><input class="admin_input pad filterCategory" data-bind="value: indCategory, event: {click:$root.other.actions.click, change:$root.other.actions.change}"></td>
                    <td><input class="admin_input pad" data-bind="value: indName, event: {click:$root.other.actions.click, change:$root.other.actions.change}"></td>
                    <td><input class="admin_input pad" data-bind="value: indTarget, event: {click:$root.other.actions.click, change:$root.other.actions.change}"></td>
                    <td><input class="admin_input pad" data-bind="value: indNarrative, event: {click:$root.other.actions.click, change:$root.other.actions.change}"></td>
                    <td><input class="admin_input pad" data-bind="value: indDefinition, event: {click:$root.other.actions.click, change:$root.other.actions.change}"></td>
                    <td><button data-bind="click:$root.other.actions.delete, attr:{'data-cid':_cid}" class="btn btn-xs btn-danger btn_remove">X</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin:5px; font-size:150%">
        Filter:&nbsp;
        <select class="dataFilter" id="filter_category" data-bind="foreach:other.selectLists.category" style="width:150px">
            <option data-bind="text:$data"></option>
        </select>

        <button id="btn_add" class="btn btn-primary">Add a new indicator</button>
        <button id="btn_submit" class="btn btn-primary">Submit changes</button>
    </div>

</div>
