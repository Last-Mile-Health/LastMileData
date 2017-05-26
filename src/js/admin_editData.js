$(document).ready(function(){

    // !!!!! Incorporate two-way data formatting !!!!!

    // This object will warn the user if they try to navigate away from the page or load different data
    DataPortal_GLOBALS.anyChanges = false;


    // Submit button disabled by default
    LMD_utilities.ajaxButton($('#btn_submit'), 'disable');
    
    
    // Generate "monthList" object
    var monthList = {
        months: ko.observableArray(),
        next: moment(),
        // maxDate and minDate hold the maximum and minimum dates in the range, represented as number of months since year 0
        maxDate: '',
        minDate: '',
        add: function(reps) {
            for(var i=0; i<reps; i++) {
                
                // Add object to "months" array, of the following form: { year:2015, month:12, string:"Dec '15" }
                this.months.push({
                    year: this.next.format("YYYY"),
                    month: this.next.format("M"),
                    string: this.next.format("MMM 'YY")
                });
                
                // Set/reset maxDate and minDate
                var totalMonths = (Number(this.next.format("YYYY"))*12)+Number(this.next.format("M"));
                this.maxDate = this.maxDate==='' ? totalMonths : this.maxDate;
                this.minDate = totalMonths;
                
                // Subtract one month
                this.next = this.next.subtract(1,'months');
                
            }
        }
    };


    // Add four initial months
    monthList.add(4);


    // Create adminModel (primary model for Knockout)
    var adminModel = {
        indicators: ko.observableArray(),
        monthList: monthList,
        selects: {
            category: ko.observableArray(),
            cut: ko.observableArray(["Geo-cut..."])
        },
        // "changedData" object holds changed values
        changedData: {
            changed: {},
            add: function(month, year, instID, value){
                // Add data point to "data" object
                this.changed[month + '-' + year + '-' + instID] = {month: month, year: year, instID: instID, value: value};
                // When data is changed, switch "DataPortal_GLOBALS.anyChanges" variable and activate submit button
                DataPortal_GLOBALS.anyChanges = true;
                $('#btn_submit').prop('disabled','');
            },
            reset: function() {
                DataPortal_GLOBALS.anyChanges = false;
                this.changed = {};
            }
        },
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
                var instID = $(event.currentTarget).attr('data-instid');
                var value = $(event.currentTarget).val();
                adminModel.changedData.add(month, year, instID, value); // !!!!! should this be a "this" reference ?????
            }
        },
        loadData: loadData
    };
    
    
    // Initial load from server (metadata+data)
    adminModel.loadData({initialLoad:true});


    // Initialize knockout.js; bind model to DIV
    ko.applyBindings(adminModel, $('#outerDiv')[0]);


    // Click handler: SHOW 3 more months
    $('#btn_showThree').click(function(){
        
        // Warn user to save changes before loading more data
        if (DataPortal_GLOBALS.anyChanges===true) {
            var confirm = window.confirm("You have unsaved changes to data. Click \"Cancel\" and then \"Submit changes\" to save the data. Otherwise, click \"OK\"");
        }
        
        if (confirm || DataPortal_GLOBALS.anyChanges===false) {
            // Show loading GIF; manipulate DOM
            $('#ajax_loader').fadeIn(250);
            $('#btn_submit').prop('disabled','disabled');
            setTimeout(function(){
                // Add three months
                monthList.add(3);
                // Clear current data
                adminModel.indicators.removeAll();
                // Load data
                adminModel.loadData({initialLoad:false});
            },250);
        }
        
    });


    // Click handerl: SUBMIT
    $('#btn_submit').click(function(){

        // Manipulate DOM
        LMD_utilities.ajaxButton($('#btn_submit'), 'ajaxLoader');
        $('#btn_showThree').prop('disabled','disabled');

        // Parse queryString
        var queryString = "";
        for(var key in adminModel.changedData.changed) {
            var x = adminModel.changedData.changed[key];
            queryString += "REPLACE INTO lastmile_dataportal.tbl_values (`month`,`year`,`instID`,`instValue`) VALUES ";
            queryString += "('" + x.month + "','" + x.year + "','" + x.instID + "','" + LMD_utilities.addSlashes(x.value) + "'" + ");";
        }

        var myData = {'queryString': queryString, 'transaction': true} ;

        // Send AJAX request
        $.ajax({
            type: "POST",
            url: "/LastMileData/php/scripts/ajaxSendQuery.php",
            data: myData,
            dataType: "json",
            success: function() {
                // Reset changedData object; manipulate DOM
                adminModel.changedData.reset();
                $('#btn_showThree').prop('disabled','');
                LMD_utilities.ajaxButton($('#btn_submit'), 'alertSuccess', 'Submit changes');
            },
            error: ajaxError
        });
    });

    // Change handler: FILTER TABLE BASED ON CUT
    $('.dataFilter').change(function() {
        
        // Warn user to save changes before loading more data
        if (DataPortal_GLOBALS.anyChanges===true) {
            var confirm = window.confirm("You have unsaved changes to data. Click \"Cancel\" and then \"Submit changes\" to save the data. Otherwise, click \"OK\"");
        }
        
        if (confirm || DataPortal_GLOBALS.anyChanges===false) {
            // Show loading GIF
            $('#ajax_loader').fadeIn(250);
            setTimeout(function(){
                // Clear current data
                adminModel.indicators.removeAll();
                // Load data
                adminModel.loadData({initialLoad:false});
            },250);
        }
        
    });

});


// Loads data (indicator/instance metadata + values) from server, filtered by geoCut and indCategory
function loadData(options) {
    
    var self = this;
    
        // Get filter values
        // Note: default value set manually here
        var filterCategory_default = "Programs (scale)";
        var filterCategory = options.initialLoad ? filterCategory_default : $('#filter_category').val();
        var filterCut = options.initialLoad ? "all" : $('#filter_cut').val();
        filterCut = filterCut==="Geo-cut..." ? "all" : filterCut;
        var minDate = self.monthList.minDate;
        var maxDate = self.monthList.maxDate;

        // Set up WHEN statement such that callback executes when both AJAX requests resolve
        $.when(
                
            // Send AJAX request #1 (indicator/instance metadata)
            $.ajax({
                type: "GET",
                url: "/LastMileData/php/scripts/LMD_REST.php/indicatorInstancesFiltered/0/" + filterCategory + "/" + filterCut,
                dataType: "json",
                error: ajaxError
            }),

            // Send AJAX request #2 (indicator/instance data)
            $.ajax({
                type: "GET",
                url: "/LastMileData/php/scripts/LMD_REST.php/instanceValuesFiltered/" + filterCategory + "/" + filterCut + "/" + minDate + "/" + maxDate,
                dataType: "json",
                error: ajaxError
            }),

            // Send AJAX request #3 (indicator/instance geocuts)
            $.ajax({
                type: "GET",
                url: "/LastMileData/php/scripts/LMD_REST.php/geoCuts/0/",
                dataType: "json",
                error: ajaxError
            }),

            // Send AJAX request #4 (indicator/instance categories)
            $.ajax({
                type: "GET",
                url: "/LastMileData/php/scripts/LMD_REST.php/indCategories/",
                dataType: "json",
                error: ajaxError
            })

        ).done(function(metadata, data, geocuts, categories) {
            
            // Sort `metadata` (result of first AJAX request) by: indCategory, indName, geoName
            try {
                metadata[0].sort(function(a,b){
                    // Sort 1: "Category"
                    if (a.indCategory < b.indCategory) { return -1; }
                    else if (a.indCategory > b.indCategory) { return 1; } 
                    else {
                        // Sort 2: "Indicator name"
                        if (a.indName < b.indName) { return -1; }
                        else if (a.indName > b.indName) { return 1; } 
                        else {
                            // Sort 3: "Cut"
                            if (a.geoName < b.geoName || a.geoName===null) { return -1; }
                            else if (a.geoName > b.geoName || b.geoName===null) { return 1; } 
                            else {
                                return 0;
                            }
                        }
                    }
                });
            } catch(e) {
                // If there is only a single object in the response, stick it in an array
                metadata[0] = [metadata[0]];
            }
            
            // Push metadata into adminModel.indicators array
            for (var key in metadata[0]) {
                self.indicators.push(metadata[0][key]);
            }
            
            // Populate `adminModel.selects.cut` array (holds options for geoCut filter
            for (var key in geocuts[0]) {
                var cut = geocuts[0][key].geoName;
                if (self.selects.cut.indexOf(cut)===-1) {
                    self.selects.cut.push(cut);
                }
            }
            
            // Populate `adminModel.selects.category` array (holds options for indCategory filter
            for (var key in categories[0]) {
                var category = categories[0][key].indCategory;
                if (self.selects.category.indexOf(category)===-1) {
                    self.selects.category.push(category);
                }
            }
            
            // Set default category
            if (options.initialLoad) {
                $('#filter_category').val(filterCategory_default);
            }
            
            // If there is only a single object in the response, stick it in an array
            if (Array.isArray(data[0])===false) {
                data[0] = [data[0]];
            }
            
            // Insert `data` (result of second AJAX request) directly into table cells
            for (var key in data[0]) {
                var month = data[0][key].month;
                var year = data[0][key].year;
                var instID = data[0][key].instID;
                var instValue = data[0][key].instValue;
                $("input[data-instid=" + instID + "][data-month=" + month + "][data-year=" + year + "]").val(instValue);
            }
            
            // Reset changedData object; manipulate DOM
            self.changedData.reset();
            $('#btn_showThree').prop('disabled','');
            
            // Hide loading GIF
            $('#ajax_loader').fadeOut(250);
            
        });
        
}


// Handle AJAX errors
function ajaxError(response) {
    // Error message; reset DOM
    alert('Error. Could not reach the database. Please check your internet connection and try again.');
    $('#btn_showThree').prop('disabled','');
    LMD_utilities.ajaxButton($('#btn_submit'), 'alertError', 'Submit changes');
    LMD_utilities.ajaxButton($('#btn_submit'), 'enable');
    
    // Log error to console
    console.log('ajax error :/');
    console.log(response);
}


// !!!!! ARCHIVE: delete when done !!!!!

//        $('.filterRow').each(function() {
//
//            $(this).removeClass('hide');
//
//            var rowCategory = $(this).find('.filterCategory')[0].textContent;
//            var rowCut = $(this).find('.filterCut')[0].textContent;
//
//            if (filterCategory!=='Category...' && filterCategory !== rowCategory) {
//                $(this).addClass('hide');
//            }
//
//            if (filterCut!=='Geo-cut...' && filterCut !== rowCut) {
//                $(this).addClass('hide');
//            }
//
//        });


// Populate data
//function populateData() {
//
//    // Set stored data
//    // !!!!! Phase out (used in WHEN statement above) ?????
//    for (var key in instanceValues) {
//        var month = instanceValues[key].month;
//        var year = instanceValues[key].year;
//        var instID = instanceValues[key].instID;
//        var instValue = instanceValues[key].instValue;
//        $("input[data-instid=" + instID + "][data-month=" + month + "][data-year=" + year + "]").val(instValue);
//    }        
//
//    // Reset submitted data
//    // !!!!! Phase out (unused) ?????
//    for (var key in changedData.submitted) {
//        var month = changedData.submitted[key].month;
//        var year = changedData.submitted[key].year;
//        var instID = changedData.submitted[key].instID;
//        var instValue = changedData.submitted[key].value;
//        $("input[data-instid=" + instID + "][data-month=" + month + "][data-year=" + year + "]").val(instValue);
//    }
//
//    // Reset changed data
//    // !!!!! Phase out (unused) ?????
//    for (var key in changedData.changed) {
//        var month = changedData.changed[key].month;
//        var year = changedData.changed[key].year;
//        var instID = changedData.changed[key].instID;
//        var instValue = changedData.changed[key].value;
//        $("input[data-instid=" + instID + "][data-month=" + month + "][data-year=" + year + "]").val(instValue);
//    }
//
//}
