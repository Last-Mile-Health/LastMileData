$(document).ready(function(){

    // Note that this tool excludes leaflet data. If manual edits are needed to leaflet data, they need to happen within the MySQL table (lastmile_dataportal.tbl_values) directly

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
            cut: ko.observableArray(["Territory..."])
        },
        // "changedData" object holds changed values
        changedData: {
            changed: {},
            add: function(month, year, inst_id, value){
                // Add data point to "data" object
                this.changed[month + '-' + year + '-' + inst_id] = {month: month, year: year, inst_id: inst_id, value: value};
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
                var inst_id = $(event.currentTarget).attr('data-inst_id');
                var value = $(event.currentTarget).val();
                adminModel.changedData.add(month, year, inst_id, value);
            }
        },
        loadData: loadData
    };
    
    
    // Initial load from server (metadata+data)
    adminModel.loadData({initialLoad:true});


    // Initialize knockout.js; bind model to DIV
    ko.applyBindings(adminModel, $('#outerDiv')[0]);


    // Click handler: Show 3 more months
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


    // Click handerl: Submit
    $('#btn_submit').click(function(){

        // Manipulate DOM
        LMD_utilities.ajaxButton($('#btn_submit'), 'ajaxLoader');
        $('#btn_showThree').prop('disabled','disabled');

        // Parse queryString
        var queryString = "";
        for(var key in adminModel.changedData.changed) {
            var x = adminModel.changedData.changed[key];
            var ind_id =        x.inst_id.split('-')[0];
            var territory_id =  x.inst_id.split('-')[1];
            var period_id =     x.inst_id.split('-')[2];
            queryString += "REPLACE INTO lastmile_dataportal.tbl_values (`ind_id`,`territory_id`,`period_id`,`month`,`year`,`value`) VALUES ";
            queryString += "('" + ind_id + "','" + territory_id + "','" + period_id + "','" + x.month + "','" + + x.year + "','" + LMD_utilities.addSlashes(x.value) + "'" + ");";
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
    

    // Click handler: Add new indicator
    $('#btn_addNewIndicator').click(function(){

        // !!!!! Add functionality; use a modal form !!!!!
        // !!!!! Should take paramaters (dropdowns): (1) indicator, (2) territory, (3) period !!!!!

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


// Loads data (indicator/instance metadata + values) from server, filtered by territory and ind_category
function loadData(options) {
    
    var self = this;
    
        // Get filter values
        // Note: default value set manually here
        var filterCategory_default = "Programs (scale)";
        var filterCategory = options.initialLoad ? filterCategory_default : $('#filter_category').val();
        var filterCut = options.initialLoad ? "all" : $('#filter_cut').val();
        filterCut = filterCut==="Territory..." ? "all" : filterCut;
        var minDate = self.monthList.minDate;
        var maxDate = self.monthList.maxDate;

        // Send out AJAX requests; run callback after they all resolve
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
                url: "/LastMileData/php/scripts/LMD_REST.php/indicatorValuesFiltered/" + filterCategory + "/" + filterCut + "/" + minDate + "/" + maxDate,
                dataType: "json",
                error: ajaxError
            }),

            // Send AJAX request #3 (active territories)
            // !!!!! May need to modify the underlying view later on to filter out districts, facilities, etc. !!!!!
            $.ajax({
                type: "GET",
                url: "/LastMileData/php/scripts/LMD_REST.php/territories_active/",
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

        ).done(function(metadata, values, territories, categories) {
            
            // Sort `metadata` (result of first AJAX request) by: ind_category, ind_name, territory_name
            try {
                metadata[0].sort(function(a,b){
                    // Sort 1: "Category"
                    if (a.ind_category < b.ind_category) { return -1; }
                    else if (a.ind_category > b.ind_category) { return 1; } 
                    else {
                        // Sort 2: "Indicator name"
                        if (a.ind_name < b.ind_name) { return -1; }
                        else if (a.ind_name > b.ind_name) { return 1; } 
                        else {
                            // Sort 3: "Cut"
                            if (a.territory_name < b.territory_name || a.territory_name===null) { return -1; }
                            else if (a.territory_name > b.territory_name || b.territory_name===null) { return 1; } 
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
            for (var key in territories[0]) {
                var cut = territories[0][key].territory_name;
                if (self.selects.cut.indexOf(cut)===-1) {
                    self.selects.cut.push(cut);
                }
            }
            
            // Populate `adminModel.selects.category` array (holds options for ind_category filter
            for (var key in categories[0]) {
                var category = categories[0][key].ind_category;
                if (self.selects.category.indexOf(category)===-1) {
                    self.selects.category.push(category);
                }
            }
            
            // Set default category
            if (options.initialLoad) {
                $('#filter_category').val(filterCategory_default);
            }
            
            // If there is only a single object in the response, stick it in an array
            if (Array.isArray(values[0])===false) {
                values[0] = [values[0]];
            }
            
            // Insert `values` (result of second AJAX request) directly into table cells
            for (var key in values[0]) {
                var month = values[0][key].month;
                var year = values[0][key].year;
                var inst_id = values[0][key].inst_id;
                var value = values[0][key].value;
                $("input[data-inst_id=" + inst_id + "][data-month=" + month + "][data-year=" + year + "]").val(value);
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
