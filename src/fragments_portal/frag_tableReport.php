<script>
<?php

    // Set include path; require connection strings
    set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
    require_once("cxn.php");

    // $_GET['switch'] variable determines which set of queries to use
    $sw = $_GET['switch'];
    echo "var sw = '$sw';";

?>

// Load main script
$.getScript('../js/frag_tableReport.js');

</script>

<div id="ajaxLoader" style="position:relative; top:20px; left:10px;" hidden>
    <img src="../images/ajax_loader.gif"><br>
    <img src="../images/ajax_loader.gif"><br>
    <img src="../images/ajax_loader.gif"><br>
</div>

<div id ="tableReportContainer">
    <div id="tableReport">

        <div style="float:left">
            <h1 id="tableTitle"></h1>
        </div>

        <div style="float:right; font-size:120%; position:relative; top:20px;">
            <select id="tableSelector" data-bind="foreach:options, event:{change:actions.selectChange}">
                <option data-bind="text:selectName, value:selectName"></option>
            </select>
        </div>

        <table class="table table-striped table-hover">
            <thead>
                <tr data-bind="foreach:fields">
                    <th data-bind="text:$data"></th>
                </tr>
            </thead>
            <tbody data-bind="foreach:data">
                <tr data-bind="foreach:$data">
                    <td data-bind="text:$data"></td>
                </tr>
            </tbody>
        </table>

    </div>
</div>
