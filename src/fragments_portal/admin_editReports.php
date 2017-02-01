<link rel="stylesheet" href="../css/admin_editReports.css">

<script>
<?php
    // Initiate/configure CURL session
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    
    // Echo JSON (reports)
    $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/reports/";
    curl_setopt($ch,CURLOPT_URL,$url1);
    $json1 = curl_exec($ch);
    
    // Close CURL session and echo JSON
    curl_close($ch);
    echo "var reports = $json1;";
//    echo "var sidebar_model_edit = JSON.parse($json1.objectData);";
    
?>

// Load main script
$.getScript('../js/admin_editReports.js');

</script>

<h2>Edit Reports</h2>
<hr>

<div id="editReports_top">
    
    <table>
        
        <tr>
            <td>Add report: </td>
            <td><input id="addReport_input" placeholder="Report name..." class="form-control"></td>
            <td><button id="addReport" class="repButton btn btn-s btn-success">Go</button></td>
        </tr>
        <tr>
            <td>Edit report: </td>
            <td>
                <select id="editReport_input" data-bind="foreach:reports" class="form-control">
                    <option data-bind="{text:reportName, value:reportID}"></option>
                </select>
            </td>
            <td><button id="editReport" class="repButton btn btn-s btn-warning">Go</button></td>
        </tr>
        <tr>
            <td>Delete report: </td>
            <td>
                <select id="deleteReport_input" data-bind="foreach:reports" class="form-control">
                    <option data-bind="{text:reportName, value:reportID}"></option>
                </select>
            </td>
            <td><button id="deleteReport" class="repButton btn btn-s btn-danger">Go</button></td>
        </tr>
    </table>
    
    <br><hr>
    
</div>

<div id="editReports_bottom" class="hide2"> <!-- !!!!! change to "hide" when done developing !!!!! -->
    
    <div>
        <button id="btn_save" class="btn btn-success" data-bind="click:actions.saveChanges">Save changes</button>
    </div>
    
    <div>
        <div data-bind="foreach:reportObjects">
            <div class="roContainer" data-bind="attr:{id:id}">
                
                <div style="width:46%; float:left">
                    <table>
                        <tr>
                            <td>METADATA</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                Load metadata from first instance ID:
                                <button class="btn btn-xs btn-success" data-bind="click:$root.actions.loadMetadata">+</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Instance IDs (table):&nbsp;</td>
                            <td><input placeholder="Type instance IDs..." class="ui-state-default ui-corner-all" data-bind="value:instIDs"></td>
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
                
                <div style="width:46%; float:left">
                    <table>
                        <tr>
                            <td>CHART</td>
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
                            <td><input placeholder="Type instance IDs..." class="ui-state-default ui-corner-all" data-bind="value:chart_instIDs"></td>
                        </tr>
                    </table>
                </div>
                
                <div style="width:8%; float:left">
                    <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveObjUp">&#8593;</button>
                    <button class="deleteOuter btn btn-xs btn-warning" data-bind="click:$root.actions.moveObjDown">&#8595;</button>
                    <button class="deleteOuter btn btn-xs btn-danger" data-bind="click:$root.actions.deleteObj">X</button>
                </div>
                
                <div style="clear:both"></div>
                
            </div>
            
        </div>
        
    <button id="addNewRO" class="btn btn-lg btn-success" data-bind="click:$root.actions.addNewRO">+</button>
    <br><br>
    
    </div>
</div>

