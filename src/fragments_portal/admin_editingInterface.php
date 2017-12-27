<link rel="stylesheet" href="../css/admin_editingInterface.css" type="text/css" />

<script>
<?php

    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

    // $_GET['switch'] variable corrseponds to the name of the corresponding REST service (LMD_REST.php/$switch/)
    // Echo out as JS variable; used by admin_editingInterface.js
    $sw = $_GET['switch'];
    echo "var sw = '$sw';". "\n\n";

    // Echo JSON (indicator METADATA)
    $url = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/$sw/0/";
    curl_setopt($ch,CURLOPT_URL,$url);
    $json = curl_exec($ch);

    // Close CURL session and echo data (JSON)
    curl_close($ch);
    echo "var eData = $json;". "\n\n";
?>

// Load main script
$.getScript('../js/admin_editingInterface.js');

</script>

<div id="outerDiv">

    <h2 data-bind="text:other.stringH2"></h2>

    <table id="table_editing" class="table compact">
        <thead id="adminHeader">
            <tr>
                <!-- ko foreach:other.tableHeaders -->
                <th data-bind="text:$data"></th>
                <!-- /ko -->
                <th id="deleteTH">&nbsp;Archive&nbsp;</th>
            </tr>
        </thead>
        <tbody id="scrollContent" data-bind="foreach:vmData">
            <tr class="eiTR filterRow">
                <!-- <td> elements are dynamically populated here by admin_editingInterface.js -->
                <td data-bind="ifnot:_add"><button data-bind="click:$root.other.actions.delete, attr:{'data-cid':_cid}" class="btn btn-xs btn-danger btn_remove">Archive</button></td>
            </tr>
        </tbody>
    </table>

    <div id="table_tools" style="margin-top:5px; font-size:150%">
        
        <!-- ko if: other.selectLists -->
        Filter:&nbsp;
        <select class="dataFilter" id="filter_category" data-bind="foreach:other.selectLists.category" style="width:150px">
            <option data-bind="text:$data"></option>
        </select>
        &nbsp;&nbsp;&nbsp;
        <!-- /ko -->
        
        <button id="btn_add" class="btn btn-primary" data-bind="text:other.stringAdd"></button>
        <button id="btn_submit" class="btn btn-primary">Submit changes</button>
    </div>

</div>
