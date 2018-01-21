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
                        <option data-bind="{text:report_name, value:report_id}"></option>
                    </select>
                </td>
                <td><button id="editReport" data-bind="click:actions.editReport" class="repButton btn btn-s btn-warning">Go</button></td>
            </tr>
            <tr>
                <td>Archive report: </td>
                <td>
                    <select id="deleteReport_input" data-bind="foreach:reports" class="form-control">
                        <option data-bind="{text:report_name, value:report_id}"></option>
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
                                    <input type="checkbox" data-bind="checked:use_metadata_from_indicator">Use metadata from (first) indicator
                                    <!--<input type="checkbox" data-bind="checked:use_metadata_from_indicator, event:{click: $root.actions.loadMetadata}">Use metadata from (first) indicator-->
                                </td>
                            </tr>
                            <tr>
                                <td>Indicators (table):&nbsp;</td>
                                <td>
                                    <button class="btn btn-xs btn-success btn_select_indicators" data-bind="click:$root.actions.selectIndicators.bind($data,'table')">Select</button>
                                    <input disabled placeholder="Select indicators..." class="ui-state-default ui-corner-all" data-bind="value:indicators_table" style="width:141px">
                                </td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td>
                                    <input class="ui-state-default ui-corner-all" data-bind="value:ro_name, visible:(!use_metadata_from_indicator())" placeholder="Type object name...">
                                    <input class="ui-state-default ui-corner-all" data-bind="attr:{'data-ind_id':indicators_table}, visible:use_metadata_from_indicator" data-field="ind_name" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Description:</td>
                                <td>
                                    <input class="ui-state-default ui-corner-all" data-bind="value:ro_description, visible:(!use_metadata_from_indicator())" placeholder="Type object description...">
                                    <input class="ui-state-default ui-corner-all" data-bind="attr:{'data-ind_id':indicators_table}, visible:use_metadata_from_indicator" data-field="ind_definition" disabled>
                                </td>
                            </tr>
                            <tr>
                                <td>Source:</td>
                                <td>
                                    <input class="ui-state-default ui-corner-all" data-bind="value:ro_source, visible:(!use_metadata_from_indicator())" placeholder="Type object source...">
                                    <input class="ui-state-default ui-corner-all" data-bind="attr:{'data-ind_id':indicators_table}, visible:use_metadata_from_indicator" data-field="ind_source" disabled>
                                </td>
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
                                <td>Indicators (chart):&nbsp;</td>
                                <td>
                                    <button class="btn btn-xs btn-success btn_select_indicators" data-bind="click:$root.actions.selectIndicators.bind($data,'chart')">Select</button>
                                    <input disabled placeholder="Select indicators..." class="ui-state-default ui-corner-all" data-bind="value:indicators_chart" style="width:141px">
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <button class="btn btn-xs btn-success btn_archive" data-bind="click:$root.actions.archiveToggle">Archive</button></td>
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
                                <td><input class="labels_table ui-state-default ui-corner-all" style='width:400px' data-bind="value:labels_table, event:{blur:$root.actions.checkIndIDsTable}" placeholder="Type a comma-separated list of labels..."></td>
                            </tr>
                            <tr>
                                <td>Chart height (px):&nbsp;</td>
                                <td><input class="ui-state-default ui-corner-all" style='width:50px' data-bind="value:chart_size_y"></td>
                                <td style='width:40px'></td>
                                <td>Labels (chart):&nbsp;</td>
                                <td><input class="labels_chart ui-state-default ui-corner-all" style='width:400px' data-bind="value:labels_chart, event:{blur:$root.actions.checkIndIDsChart}" placeholder="Type a comma-separated list of labels..."></td>
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


<!-- MODAL START: "Select indicators" -->
<div id="selectIndicatorsModal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Select indicators (table)</h3>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>ID#</th>
                            <th>Indicator Name</th>
                        </tr>
                    </thead>
                    <tbody data-bind="foreach:indicators">
                        <tr>
                            <td><input class="indCheckbox" type="checkbox" data-bind="attr:{'data-ind_id':ind_id}"></td>
                            <td data-bind="text:ind_id"></td>
                            <td data-bind="text:ind_name"></td>
                        </tr>
                    </tbody>
                </table>
<!--                <div data-bind="foreach:indicators">
                    <div>
                        <input type="checkbox">&nbsp;<span data-bind="text:ind_name"></span>
                    </div>
                </div>-->
                <div class="form-group">
                    <button id="selectIndicators_submit" data-bind="click:actions.submit" class="btn btn-success btn-lg btn-block">Submit</button>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>        
<!-- MODAL END: "Select indicators" -->
