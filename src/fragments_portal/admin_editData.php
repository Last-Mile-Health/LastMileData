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
                    <th class="pad darkBlue">Geo-cut</th>
                    <!-- ko foreach:monthList.months -->
                    <th class="pad darkBlue" data-bind="text:string"></th>
                    <!-- /ko -->
                </tr>
            </thead>
            <tbody id="scrollContent" data-bind="foreach:indicators">
                <tr class="filterRow">
                    <td class="pad filterCategory" data-bind="text:indCategory"></td>
                    <td class="pad" data-bind="text:indName"></td>
                    <td class="pad filterCut" data-bind="text:geoName"></td>
                    <!-- ko foreach: $root.monthList.months -->
                    <td>
                        <input class="admin_input" data-bind="event: {click:$root.actions.aiClick, change:$root.actions.aiChange}, attr: {'data-instid':$parent.instID, 'data-month':month, 'data-year':year}">
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
