<link rel="stylesheet" href="../css/admin_editData.css" type="text/css" />

<script>
    // Load main script
    $.getScript('../js/admin_editData.js');
</script>    

<div id="outerDiv">
        
    <h2>
        Edit data
        <img id="ajax_loader" src='/LastMileData/build/images/ajax_loader.gif' style="padding-left:5px">
    </h2>
    
    <div class="tableContainer">
        
        <table>
            <thead id="adminHeader">
                <tr>
                    <th class="pad darkBlue">Category</th>
                    <th class="pad darkBlue">Indicator name</th>
                    <th class="pad darkBlue">Territory</th>
                    <!-- ko foreach:monthList.months -->
                    <th class="pad darkBlue" data-bind="text:string"></th>
                    <!-- /ko -->
                </tr>
            </thead>
            <tbody id="scrollContent" data-bind="foreach:indicators">
                <tr class="filterRow">
                    <td class="pad filterCategory" data-bind="text:ind_category"></td>
                    <td class="pad" data-bind="text:ind_name"></td>
                    <td class="pad filterTerritory" data-bind="text:territory_name"></td>
                    <!-- ko foreach: $root.monthList.months -->
                    <td>
                        <input class="admin_input" data-bind="event: {click:$root.actions.aiClick, change:$root.actions.aiChange}, attr: {'data-inst_id':$parent.inst_id, 'data-month':month, 'data-year':year}">
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
        <select class="dataFilter" id="filter_territory" data-bind="foreach:selects.territory" style="width:150px">
            <option data-bind="text:$data.territory_name"></option>
        </select>

        <button id="btn_showThree" class="btn btn-primary">Show 3 more months</button>
        <button id="btn_submit" class="btn btn-primary">Submit changes</button>
        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_addInstance" title="Add a new instance">Add a new instance</a>
    </div>


    <!-- MODAL START: "ADD A NEW INSTANCE" -->
    <div id="modal_addInstance" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center">Add a new indicator instance</h1>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div id="modal_addInstance_formContent">
                            <form id="modal_addInstance_form">
                                <div class="form-group">
                                    <div style="margin:10px">
                                        <select id="addInstance_indicator" class="form-control input-lg" data-bind="foreach:selects.indicator">
                                            <option data-bind="text:$data.ind_name, value:$data.ind_id"></option>
                                        </select>
                                    </div>
                                    <div style="margin:10px">
                                        <select id="addInstance_territory" class="form-control input-lg" data-bind="foreach:selects.territory">
                                            <option data-bind="text:$data.territory_name, value:$data.territory_id"></option>
                                        </select>
                                    </div>
                                    <div class="text-center">
                                        <a id="modal_addInstance_submit" class="btn btn-primary btn-lg" style="margin:10px">Submit</a>
                                        <br>
                                        <button class="btn" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL END: "ADD A NEW INSTANCE" -->

</div>



