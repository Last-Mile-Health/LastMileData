$(document).ready(function(){

    // !!!!! Right now, all indicator data is being loaded (very inefficient) !!!!!
    // !!!!! Incorporate two-way data formatting !!!!!

    DataPortal_GLOBALS.anyChanges = false;
    var $submit = $('#btn_submit');
    var $threeMore = $('#btn_showThree');

    // Submit button disabled by default
    LMD_utilities.ajaxButton($submit, 'disable');

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
    var monthList = {
        months: ko.observableArray(),
        next: moment(),
        add: function(reps) {
            for(var i=0; i<reps; i++) {
                // Add object to "months" array, of the following form: { year:2015, month:12, string:"Dec '15" }
                this.months.push({
                    year: this.next.format("YYYY"),
                    month: this.next.format("M"),
                    string: this.next.format("MMM 'YY")
                });
                // Subtract one month
                this.next = this.next.subtract(1,'months');
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

    // Create adminModel (primary model for Knockout)
    var adminModel = {
        indicators: indicatorList,
        monthList: monthList,
        selects: selectLists,
        actions: {
            // Parameters are passed in by Knockout.js event binder
            aiClick: function(data,event) {
                // Highlight input when user clicks on a table cell
                $(event.currentTarget).select();
            },
            aiChange: function(data,event) {
                // When user changes a data value, add it to the changedData object
                var month = $(event.currentTarget).attr('data-month');
                var year = $(event.currentTarget).attr('data-year');
                var indID = $(event.currentTarget).attr('data-indid');
                var value = $(event.currentTarget).val();
                changedData.add(month, year, indID, value);
            }
        }
    };

    // Initialize knockout.js; bind model to DIV
    ko.applyBindings(adminModel, $('#outerDiv')[0]);

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

    // Click handler: SHOW 3 more months
    $('#btn_showThree').click(function(){
        monthList.add(3);
        populateData();
    });

    // Click handerl: SUBMIT
    $('#btn_submit').click(function(){

        // Manipulate DOM
        LMD_utilities.ajaxButton($submit, 'ajaxLoader');
        $threeMore.prop('disabled','disabled');

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
            url: "/LastMileData/php/scripts/ajaxSendQuery.php",
            data: myData,
            dataType: "json",
            success: function() {
                // Reset changedData object; manipulate DOM
                changedData.reset();
                $threeMore.prop('disabled','');
                LMD_utilities.ajaxButton($submit, 'alertSuccess', 'Submit');
            },
            error: function() {
                // Error message; reset DOM
                alert('Error. Could not reach the database. Please try again.');
                $threeMore.prop('disabled','');
                LMD_utilities.ajaxButton($submit, 'alertError', 'Submit');
                LMD_utilities.ajaxButton($submit, 'enable');
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
