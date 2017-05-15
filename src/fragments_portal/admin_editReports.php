<link rel="stylesheet" href="../css/admin_editReports.css">

<script>
<?php
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    
    // Echo JSON (reports)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reports/0/";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);
    
    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var reports = $json1;";
    
?>

// Load main script
$.getScript('../js/admin_editReports.js');

</script>

<h2>Edit Reports</h2>
<hr>

<div id="editReports_container">

    <div id="editReports_top">

        <table>

            <tr>
                <td>Add report: </td>
                <td><input id="addReport_input" placeholder="Report name..." class="form-control"></td>
                <td><button id="addReport" data-bind="click:actions.addReport" class="repButton btn btn-s btn-success">Go</button></td>
            </tr>
            <tr>
                <td>Edit report: </td>
                <td>
                    <select id="editReport_input" data-bind="foreach:reports" class="form-control">
                        <option data-bind="{text:reportName, value:reportID}"></option>
                    </select>
                </td>
                <td><button id="editReport" data-bind="click:actions.editReport" class="repButton btn btn-s btn-warning">Go</button></td>
            </tr>
            <tr>
                <td>Delete report: </td>
                <td>
                    <select id="deleteReport_input" data-bind="foreach:reports" class="form-control">
                        <option data-bind="{text:reportName, value:reportID}"></option>
                    </select>
                </td>
                <td><button id="deleteReport" data-bind="click:actions.deleteReport" class="repButton btn btn-s btn-danger">Go</button></td>
            </tr>
        </table>

        <br><hr>

    </div>
    
    <div id="editReports_bottom" class="hide">

        <div>
            <h3>
                Currently editing: <span id="currentReport"></span>
                &nbsp;&nbsp;&nbsp;
                <button id="btn_changeReportName" class="btn btn-xs btn-success" data-bind="click:actions.changeReportName">Change report name</button>
            </h3>
        </div>

        <div>
            <button id="btn_save" class="btn btn-success" data-bind="click:actions.saveChanges">Save changes</button>
        </div>

        <div>
            <div data-bind="foreach:reportObjects">
                <div data-bind="attr:{index:$index, class:'roContainer archived_'+archived()}">

                    <div style="width:45%; float:left">
                        <table>
                            <tr>
                                <td class="roHeader">METADATA</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button class="btn btn-xs btn-success" data-bind="click:$root.actions.loadMetadata" style="top:0px;">Load metadata from first instance ID</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Instance IDs (table):&nbsp;</td>
                                <td><input placeholder="Type instance IDs..." class="ui-state-default ui-corner-all" data-bind="value:instIDs, event:{blur:$root.actions.checkInstIDsTable}"></td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td><input placeholder="Type object name..." class="ui-state-default ui-corner-all" data-bind="value:ro_name"></td>
                            </tr>
                            <tr>
                                <td>Description:</td>
                                <td><input placeholder="Type object description..." class="ui-state-default ui-corner-all" data-bind="value:ro_description"></td>
                            </tr>
                        </table>
                    </div>

                    <div style="width:45%; float:left">
                        <table>
                            <tr>
                                <td class="roHeader">CHART</td>
                            </tr>
                            <tr>
                                <td>Type:</td>
                                <td>
                                    <select class="ui-state-default ui-corner-all" data-bind="value:chart_type">
                                        <option value="line">Line</option>
                                        <option value="pie">Pie</option>
                                        <option value="bar">Bar</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Instance IDs (chart):&nbsp;</td>
                                <td><input placeholder="Type instance IDs..." class="ui-state-default ui-corner-all" data-bind="value:chart_instIDs, event:{blur:$root.actions.checkInstIDsChart}"></td>
                            </tr>
                            <tr>
                                <td><button class="btn btn-xs btn-success btn_archive" data-bind="click:$root.actions.archiveToggle">Archive</button></td>
                            </tr>
                            <tr>
                                <td><button class="btn btn-xs btn-success btn_advanced" data-bind="click:$root.actions.showAdvancedOptions">Show advanced options</button></td>
                            </tr>
                        </table>
                    </div>

                    <div style="width:10%; float:left">
                        <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveObjUp">&#8593;</button>
                        <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveObjDown">&#8595;</button>
                        <button class="deleteOuter btn btn-xs btn-danger" data-bind="click:$root.actions.deleteObj">X</button>
                    </div>

                    <div style="clear:both"></div>
                    
                    <div class="advancedOptions hide">
                        <hr style="margin-bottom:0px">
                        <table>
                            <tr>
                                <td colspan="2" class="roHeader">ADVANCED OPTIONS</td>
                            </tr>
                            <tr>
                                <td>Chart width (px):&nbsp;</td>
                                <td><input class="ui-state-default ui-corner-all" style='width:50px' data-bind="value:chart_size_x"></td>
                                <td style='width:40px'></td>
                                <td>Labels (data table):&nbsp;</td>
                                <td><input class="labels_table ui-state-default ui-corner-all" style='width:400px' data-bind="value:instIDs_shortNames, event:{blur:$root.actions.checkInstIDsTable}" placeholder="Type a comma-separated list of labels..."></td>
                            </tr>
                            <tr>
                                <td>Chart height (px):&nbsp;</td>
                                <td><input class="ui-state-default ui-corner-all" style='width:50px' data-bind="value:chart_size_y"></td>
                                <td style='width:40px'></td>
                                <td>Labels (chart):&nbsp;</td>
                                <td><input class="labels_chart ui-state-default ui-corner-all" style='width:400px' data-bind="value:chart_instIDs_shortNames, event:{blur:$root.actions.checkInstIDsChart}" placeholder="Type a comma-separated list of labels..."></td>
                            </tr>
                        </table>
                    </div>
                    
                </div>

            </div>

        <button id="addNewObj" class="btn btn-lg btn-success" data-bind="click:$root.actions.addNewObj">+</button>
        <br><br>

        </div>
    </div>
    
</div>
