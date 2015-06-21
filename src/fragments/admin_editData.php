<script>
$(document).ready(function(){
    
    <?php

        // Initiate/configure CURL session
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        // Echo JSON (indicator METADATA)
        $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/src/php/LMD_REST.php/indicators/";
        curl_setopt($ch,CURLOPT_URL,$url1);
        $json1 = curl_exec($ch);

        // Echo JSON (indicator DATA)
        $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/src/php/LMD_REST.php/indicatorvalues/";
        curl_setopt($ch,CURLOPT_URL,$url2);
        $json2 = curl_exec($ch);

        // Close CURL session and echo JSON
        curl_close($ch);
        echo "var indicatorList = $json1;". "\n\n";
        echo "var indicatorValues = $json2;". "\n\n";

    ?>

    // !!!!! Right now, all indicator data is being loaded (very inefficient) !!!!!
    // !!!!! Incorporate Rivets.js two-way formatters !!!!!

    DataPortal_GLOBALS.anyChanges = false;
    var $submit = $('#btn_submit');
    var $threeMore = $('#btn_showThree');

    // Submit button disabled by default
    $submit.prop('disabled','disabled');

    // Sort indicatorList
    indicatorList.sort(function(a,b){
        // Sort 1: "Category"
        if (a.indCategory < b.indCategory) { return -1; }
        else if (a.indCategory > b.indCategory) { return 1; } 
        else {
            // Sort 2: "Indicator name"
            if (a.indName < b.indName) { return -1; }
            else if (a.indName > b.indName) { return 1; } 
            else {
                // Sort 3: "Cut"
                if (a.indCut < b.indCut || a.indCut===null) { return -1; }
                else if (a.indCut > b.indCut || b.indCut===null) { return 1; } 
                else {
                    return 0;
                }
            }
        }
    });

    // Generate "monthList" object
//    var today = new Date();
    var today = moment();
    var monthList = {
        months: [],
        next: {
//            year: today.getFullYear(),
            year: today.format("YYYY"),
//            month: today.getMonth()+1,
            month: today.format("M"),
//            string: today.toString().substring(4,7) + " '" + today.getFullYear()%100
            string: today.format("M 'YY")
        },
        add: function(reps) {
            for(var i=0; i<reps; i++) {
                this.months.push(this.next);
                var nextYear = (this.next.month-1 > 0) ? this.next.year : this.next.year-1
                var nextMonth = (this.next.month-1 > 0) ? this.next.month-1 : 12;
                var nextDate = new Date(nextYear + "-" + nextMonth + "-01");
                var nextString = nextDate.toString().substring(4,7) + " '" + nextDate.getFullYear()%100;
                var nextObj = { year: nextYear, month: nextMonth, string: nextString };
                this.next = nextObj;
            }
        }
    };

    // Add four initial months
    monthList.add(4);
    
    // Generate "selectLists" object
    var selectLists = {
        category: ["Category..."],
        cut: ["Cut..."]
    };
    for (var key in indicatorList) {
        var category = indicatorList[key].indCategory;
        var cut = indicatorList[key].indCut;
        if (selectLists.category.indexOf(category)===-1) {
            selectLists.category.push(category);
        }
        if (selectLists.cut.indexOf(cut)===-1) {
            selectLists.cut.push(cut);
        }
    }

    // Create adminModel (primary model for Rivets)
    var adminModel = {
        indicators: indicatorList,
        monthList: monthList,
        selects: selectLists
    };

    // Bind adminModel
    rivets.bind($('#outerDiv'), {adminModel: adminModel});

    // Create "changedData" object to hold changed values
    var changedData = {
        changed: {},
        submitted: {},
        add: function(month, year, indID, value){
            // Add data point to "data" object
            this.changed[month + '-' + year + '-' + indID] = {month: month, year: year, indID: indID, value: value};
            // When data is changed, switch "DataPortal_GLOBALS.anyChanges" variable and activate submit button
            DataPortal_GLOBALS.anyChanges = true;
            $submit.prop('disabled','');
        },
        reset: function() {
            DataPortal_GLOBALS.anyChanges = false;
            for (var key in this.changed) {
                this.submitted[key] = this.changed[key];
            }
            this.changed = {};
        }
    };

    // Populate data
    function populateData() {

        // Set stored data
        for(var key in indicatorValues) {
            var month = indicatorValues[key].month;
            var year = indicatorValues[key].year;
            var indID = indicatorValues[key].indID;
            var indValue = indicatorValues[key].indValue;
            $("input[data-indid=" + indID + "][data-month=" + month + "][data-year=" + year + "]").val(indValue);
        }

        // Reset submitted data
        for (var key in changedData.submitted) {
            var month = changedData.submitted[key].month;
            var year = changedData.submitted[key].year;
            var indID = changedData.submitted[key].indID;
            var indValue = changedData.submitted[key].value;
            $("input[data-indid=" + indID + "][data-month=" + month + "][data-year=" + year + "]").val(indValue);
        }

        // Reset changed data
        for (var key in changedData.changed) {
            var month = changedData.changed[key].month;
            var year = changedData.changed[key].year;
            var indID = changedData.changed[key].indID;
            var indValue = changedData.changed[key].value;
            $("input[data-indid=" + indID + "][data-month=" + month + "][data-year=" + year + "]").val(indValue);
        }

    }

    // Initially populate all data
    populateData();

    // Handle value changes
    $('input.admin_input').change(function(){
        var month = $(this).attr('data-month');
        var year = $(this).attr('data-year');
        var indID = $(this).attr('data-indid');
        var value = $(this).val();
        changedData.add(month, year, indID, value);
    });

    // Click handler: SHOW 3 more months
    $('#btn_showThree').click(function(){
        monthList.add(3);
        populateData();
    });

    // Select text on input click
    $(".admin_input").click(function() {
        $(this).select();
    });

    // Click handerl: SUBMIT
    $('#btn_submit').click(function(){

        // Manipulate DOM
        $submit.prop('disabled','disabled');
        $threeMore.prop('disabled','disabled');
        $submit.html("<img src='/LastMileData/res/ajax-loader_v20140916.gif'>")

        // Parse queryString
        var queryString = "";
        for(key in changedData.changed) {
            var x = changedData.changed[key];
            queryString += "REPLACE INTO lastmile_dataportal.tbl_values (`month`,`year`,`indID`,`indValue`) VALUES ";
            queryString += "('" + x.month + "','" + x.year + "','" + x.indID + "','" + x.value + "'" + ");";
        }

        var myData = {'queryString': queryString, 'rKey': 1, 'transaction': 1} ; // !!!!! rKey is not used functionally here; can ajaxSendQuery work without it ?????

        // Send AJAX request
        $.ajax({
            type: "POST",
            url: "/LastMileData/src/php/ajaxSendQuery.php",
            data: myData,
            dataType: "json",
            success: function() {
                
                // Reset changedData object
                changedData.reset();
                
                // Manipulate DOM
                $submit.html("Success!");
                $threeMore.prop('disabled','');
                var color = "white";
                var interval = setInterval(function() {
                    color = (color==="white") ? "yellow" : "white";
                    $submit.css('color',color);
                },100);
                setTimeout(function() {
                    $submit.css('color',"white");
                    $submit.html("Submit");
                    clearInterval(interval);
                },2000);
            },
            error: function() {
                // Error message; reset DOM
                alert('Error. Could not reach the database. Please try again.');
                $submit.prop('disabled','');
                $threeMore.prop('disabled','');
                $submit.html("Submit");
            }
        });
    });

    // Change handler: FILTER TABLE BASED ON CUT
    $('.dataFilter').change(function() {

        var filterCategory = $('#filter_category').val();
        var filterCut = $('#filter_cut').val();

        $('.filterRow').each(function() {

            $(this).removeClass('hide');

            var rowCategory = $(this).find('.filterCategory')[0].textContent;
            var rowCut = $(this).find('.filterCut')[0].textContent;

            if (filterCategory!=='Category...' && filterCategory !== rowCategory) {
                $(this).addClass('hide');
            }

            if (filterCut!=='Cut...' && filterCut !== rowCut) {
                $(this).addClass('hide');
            }

        });

    });

});


function handleAJAXErrors(request) {
    // !!!!! Build this out !!!!!
    console.log('ajax error :/'); console.log(request);
}

</script>

<style>

    .pad {
        padding-left:10px;
        padding-right:10px;
    }
    .admin_input {
        width:100%;
        height:100%;
        border:none;
        padding:1px;
        margin-right:1px;
        margin-left:1px;
        background:#eee;
    }
    .admin_input:hover {
        background:#DBFFB7;
        cursor:pointer;
    }
    .admin_input:focus {
        background:lightgreen;
    }

    div.tableContainer {
        border-top: 2px solid #eee;
        border-bottom: 2px solid #eee;
        clear: both;
        width:100%;
        overflow-x: scroll;
        overflow-y: auto;
        padding-top:4px;
    }
    div.tableContainer table {
        width: 100%;
    }
    thead#adminHeader tr {
        display: block
    }
    thead#adminHeader th {
        padding-left:4px;
        padding-right:4px;
        border: 2px solid white;
        color: white;
        background: #3E8F3E;
    }
    #scrollContent {
        display: block;
        height: 60vh;
        overflow-x:hidden;
        overflow-y: auto;
        width: 100%
    }
    #scrollContent td {
        border:2px solid white;
        background:#eee;
    }

    th:nth-child(1), td:nth-child(1) { min-width:120px; width:120px }
    th:nth-child(2), td:nth-child(2) { min-width:300px; width:300px }
    th:nth-child(3), td:nth-child(3) { min-width:120px; width:120px }
    th:nth-child(n+4), td:nth-child(n+4) { min-width:70px; width:70px }

    .btn {
        width:200px;
        margin:2px;
    }

</style>
    
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
                        <input class="admin_input" rv-data-indid="indicator.indID" rv-data-month="month.month" rv-data-year="month.year">
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
        <button id="btn_submit" class="btn btn-success">Submit changes</button>
        <!--<button id="btn_revert" class="btn btn-danger">Revert changes</button>--> <!-- !!!!! Create "Revert changes" button !!!!! -->
    </div>

</div>

