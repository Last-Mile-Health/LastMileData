$(document).ready(function(){
    
    // !!!!! Add ajaxButton usage throughout; test on slow connection !!!!!
    // !!!!! Add labels_table and labels_chart; ensure that # of IDs matches; write a note to user that short names can't contain commas !!!!!

    // Add "Select report..." to beginning of array; initialize knockout.js; bind model to DIV ("top" model - add/edit/delete reports)
    // Note: `reports` comes from PHP CURL
    reports.unshift({report_id:"0", report_name:"Select report..."});
    
    // Get indicator list (for indicators_table and indicators_chart selects)
    // !!!!! New REST route to get only indicator names and ind_ids !!!!!
    $.ajax({
        type: "GET",
        url: "/LastMileData/php/scripts/LMD_REST.php/indicators/0/",
        dataType: "json",
        success: function(data) {

            // Initialize knockout.js; bind model to DIV; use DataTable()
            var moModel = {
                indicators: data,
                actions: {
                    submit: function() {
                        
                        // Get ind_ids
                        var ind_id_string = '';
                        $('input.indCheckbox').each(function(){
                            if ($(this).prop('checked') === true) {
                                ind_id_string += $(this).attr('data-ind_id') + ',';
                            }
                        });
                        ind_id_string = ind_id_string.slice(0,-1);
                        console.log(ind_id_string);
                        
                        
                        // Set input value
                        erModel.selectIndicators.current['indicators_' + erModel.selectIndicators.type](ind_id_string);
                        
                        // Close modal
                        $('#selectIndicatorsModal').modal('hide');
                        
                        // Update metadata
                        var first_ind_id = ind_id_string.split(',')[0];
                        erModel.actions.loadIndicatorMetadata(first_ind_id);
                        
                    }
                }
            }
            ko.applyBindings(moModel, $('#selectIndicatorsModal')[0]);
            var DT = $('.table').DataTable({
                scrollY: '35vh',
                paging: false
            });
            $('body').on('shown.bs.modal',function(){
                DT.draw();
            });

        },
        error: ajaxError
    });

    


    // Declare main model for editing sets of report objects
    var erModel = {
        
        // Holds list of reports
        reports: reports,
        
        // Holds list of "report objects"
        reportObjects: ko.observableArray(),
        
        // Holds report_id of current report
        currentReportID: null,
        
        // Holds information used by "select indicators" buttons
        selectIndicators: {
            type: '',
            current: {}
        },
        
        actions: {
            
            // Click handler: Add a new report
            addReport: function() {
                
                // First, check to see if report name is already taken (case insensitive)
                // !!!!! test this code !!!!!
                var newReportName = $('#addReport_input').val();
                var report_names = [];
                for(var key in erModel.reports) {
                    report_names.push(erModel.reports[key].report_name.toLowerCase());
                }
                var isTaken = report_names.indexOf(newReportName.toLowerCase())===-1 ? false : true;

                // If report name is not already taken, proceed
                if (isTaken) {
                    alert('This report name is already taken. Please choose another name.');
                } else {

                    // Send database request to add new report
                    $.ajax({
                        method: "POST",
                        url: "/LastMileData/php/scripts/LMD_REST.php/reports/",
                        data: {
                            report_name: newReportName
                        },
                        dataType: "json",
                        success: function(data) {
                            // Clear report objects; display report name; set report_id; add one blank RO; unhide DIV
                            erModel.reportObjects.removeAll();
                            $('#currentReport').text($('#addReport_input').val());
                            erModel.currentReportID = data;
                            erModel.actions.addNewObj();
                            $('#editReports_bottom').removeClass('hide');
                            
                        },
                        error: ajaxError
                    });

                }

            },
            
            // Click handler: Edit a report
            editReport: function() {
                
                var report_id = $('#editReport_input').val();
                
                // Load report objects
                erModel.actions.loadReportObjects(report_id);
                
                // Display report name and set currentReportID
                $('#currentReport').text($('#editReport_input option[value=' + report_id + ']')[0].outerText);
                erModel.currentReportID = report_id;
                
                // Unhide DIV
                $('#editReports_bottom').removeClass('hide');
                
            },
            
            // Click handler: Delete a report
            // Note: report is actually "archived", not deleted, and can be restored by a DBA
            deleteReport: function() {

                // Prompt user with dialog box to confirm deletion
                var confirm = window.confirm("Are you sure you want to archive this report?");

                // Proceed with "deletion" (archiving)
                if (confirm) {

                    // !!!!! Integrate "AJAX button" utility function ?????

                    // Send database request to add new report
                    $.ajax({
                        method: "DELETE",
                        url: "/LastMileData/php/scripts/LMD_REST.php/reports/" + $('#deleteReport_input').val(),
                        success: function() {
                            $("#deleteReport_input option[value=" + $('#deleteReport_input').val() + "]").remove();
                        },
                        error: ajaxError
                    });

                }
                
            },
            
            // Load report objects for a given report into editor
            loadReportObjects: function(report_id) {
                
                // Send AJAX request to retrieve report objects associated with that report
                $.ajax({
                    type: "GET",
                    url: "/LastMileData/php/scripts/LMD_REST.php/reportObjects/1/" + report_id,
                    dataType: "json",
                    success: function(data) {

                        // !!!!! Be sure to handle cases with reports with zero/one objects !!!!!

                        // Sort `reportObjects` array by display_order attribute
                        data.sort(function(a,b){
                            if (Number(a.display_order) < Number(b.display_order)) { return -1; }
                            else if (Number(a.display_order) > Number(b.display_order)) { return 1; }
                            else {
                                return 0;
                            }
                        });

                        // Clear reportObjects array; refill with new objects
                        erModel.reportObjects.removeAll();
                        for (var key in data) {
                            erModel.reportObjects.push(ko.mapping.fromJS(data[key]));
                        }

                        // Load indicator metadata
                        var ind_id_string = '';
                        for (var key in erModel.reportObjects()) {
                            var first_ind_id = erModel.reportObjects()[key].indicators_table().split(',')[0];
                            ind_id_string += first_ind_id + ','
                        }
                        ind_id_string = ind_id_string.slice(0,-1);
                        erModel.actions.loadIndicatorMetadata(ind_id_string);
                
                    },
                    error: ajaxError
                });
                
            },
            
            // Load indicator metadata (for "use metadata from (first) indicator" functionality)
            loadIndicatorMetadata: function(ind_id_string) {

                // Get metadata for first instance ID
                $.ajax({
                    type: "GET",
                    url: "/LastMileData/php/scripts/LMD_REST.php/indicators/0/" + ind_id_string,
                    dataType: "json",
                    success: function(data) {
                        
                        // Populate metadata object, which assigns each object in the response array to an ind_id key
                        var metadata = {};
                        if (data.ind_id) {
                            // Handle cases with one object in response
                            metadata[data.ind_id] = data;
                        } else {
                            // Handle cases with an array of object in response
                            for (var key in data) {
                                metadata[data[key].ind_id] = data[key];
                            }
                        }
                        
                        // Populate fields (these are for display only; values are not stored in the database)
                        $('input[data-ind_id]').each(function(){
                            var first_ind_id = $(this).attr('data-ind_id').split(',')[0];
                            var field = $(this).attr('data-field');
                            if (metadata[first_ind_id]) {
                                $(this).val(metadata[first_ind_id][field]);
                            }
                        });
                        
                    },
                    error: ajaxError
                });
                
            },
            
            // Move report object up
            moveObjUp: function(data,event) {
                var index = Number($(event.currentTarget).closest('.roContainer').attr('index'));
                if(index!==0) {
                    var item = erModel.reportObjects.splice(index,1)[0];
                    erModel.reportObjects.splice(index-1, 0, item);
                }
                
            },

            // Move report object down
            moveObjDown: function(data,event) {
                var index = Number($(event.currentTarget).closest('.roContainer').attr('index'));
                var item = erModel.reportObjects.splice(index,1)[0];
                erModel.reportObjects.splice(index+1, 0, item);
            },

            // Delete report object
            deleteObj: function() {
                
                // Display "confirm" dialog box
                var confirm = window.confirm("Are you sure you want to delete this object?");
                if (confirm) {
                    var index = Number($(event.currentTarget).closest('.roContainer').attr('index'));
                    erModel.reportObjects.splice(index,1);
                }
                
            },

            // Add a new report object
            addNewObj: function() {
                
                // Create new report object; load defaults
                erModel.reportObjects.push(ko.mapping.fromJS({
                    report_id: erModel.currentReportID,
                    inst_ids: '',
                    ro_name: '',
                    ro_description: '',
                    chart_type: 'line',
                    chart_size_x: 0,
                    chart_size_y: 0,
                    indicators_chart: '',
                    use_metadata_from_indicator: true,
                    // !!!!! add other properties (ind_source, etc.)
                    labels_table: '',
                    labels_chart: '',
                    archived: 0
                }));
                
            },
            
            // Load metadata from first indicator_id
            loadMetadata: function() {

//                if (this.use_metadata_from_indicator() === true) {
//
//                    // Create reference to current report object; get first indicator ID
//                    var self = this;
//                    var first_ind_id = this.indicators_table().split(',')[0];
//
//                    // Get metadata for first instance ID
//                    $.ajax({
//                        type: "GET",
//                        url: "/LastMileData/php/scripts/LMD_REST.php/indicators/0/" + first_ind_id,
//                        dataType: "json",
//                        success: function(data) {
//                            // Load metadata into corresponding fields
//                            self.ro_name(data.ind_name);
//                            self.ro_description(data.ind_definition);
//                        },
//                        error: ajaxError
//                    });
//
//                } else {
//                    
//                }
//
//                // Return true to enable checkbox to work properly
//                return true;

            },

            // Archive or unarchive the report object (value is toggled based on current value)
            selectIndicators: function(type, data, event) {
                
                console.log(this);
                
                erModel.selectIndicators.type = type;
                erModel.selectIndicators.current = data;
                
                if (type==='table') {
                    // !!!!!
                } else if (type==='chart') {
                    // !!!!!
                }
                
                var indArray = data.indicators_table().split(',');
                
                $('#selectIndicatorsModal').modal();
                
                // Check the checkboxes corresponding to the selected indicators
                $('input.indCheckbox').each(function(){
                    if (indArray.indexOf($(this).attr('data-ind_id')) !== -1) {
                        $(this).prop('checked',true);
                    } else {
                        $(this).prop('checked',false);
                    }
                });
                
            },
            
            // Archive or unarchive the report object (value is toggled based on current value)
            archiveToggle: function() {
                var index = Number($(event.currentTarget).closest('.roContainer').attr('index'));
                var archived = Number(erModel.reportObjects()[index].archived());
                erModel.reportObjects()[index].archived(1-archived);
            },
            
            // Archive or unarchive the report object (value is toggled based on current value)
            showAdvancedOptions: function() {
                $(event.currentTarget).closest('.roContainer').find('.advancedOptions').removeClass('hide');
            },
            
            checkIndIDsTable: function() {
                var string1 = $(event.currentTarget).val();
                console.log('table');
                console.log($(event.currentTarget).val());
                // !!!!! build this out !!!!!
            },
            
            checkIndIDsChart: function() {
                var string1 = $(event.currentTarget).val();
                console.log('chart');
                console.log($(event.currentTarget).val());
                // !!!!! build this out !!!!!
            },
            
            // Archive or unarchive the report object (value is toggled based on current value)
            changeReportName: function() {
                
                // If this is the first time the button has been clicked, change the span to an input
                if ($('#currentReport_input').length===0) {
                    var currentReportName = $('#currentReport').text();
                    $('#currentReport').html("<input id='currentReport_input'></input>");
                    $('#currentReport_input').val(currentReportName);
                }
                
            },
            
            // Save current set of report objects
            // For simplicity of code, this deletes all report objects in the database for the current report and then inserts all of the new ones
            saveChanges: function() {
                
                // Begin query string
                var queryString = "DELETE FROM lastmile_dataportal.tbl_report_objects WHERE report_id=" + erModel.currentReportID + ";";
                
                // Parse data back into regular JS array
                var roData = ko.mapping.toJS(erModel.reportObjects);
                
                // Change 'true/false' to '1/null' (for checkboxes)
                for (var key in roData) {
                    for (var key2 in roData[key]) {
                        if (roData[key][key2] === true) {
                            roData[key][key2] = 1;
                        } else if (roData[key][key2] === false) {
                            roData[key][key2] = null;
                        }
                    }
                }
                
                // Reset display order based on current array order
                var i = 1;
                for (var key in roData) {
                    roData[key].display_order = i++;
                    queryString += LMD_utilities.parseJSONIntoSQL(roData[key], "lastmile_dataportal", "tbl_report_objects", ['id']);
                }

                // If the report name has been changed, add an additional query to the queryString
                if ($('#currentReport_input').length===1) {
                    var newReportName = $('#currentReport_input').val();
                    queryString += "UPDATE lastmile_dataportal.tbl_reports SET report_name='" + LMD_utilities.addSlashes(newReportName) + "' WHERE report_id=" + erModel.currentReportID + ";";
                }
                
                // Send changes to database via AJAX; manipulate DOM on success
                var myData = { 'queryString': queryString, 'transaction': 1 } ;
                $.ajax({
                    type: "POST",
                    url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                    data: myData,
                    dataType: "json",
                    success: function() {
                        LMD_utilities.ajaxButton($('#btn_save'), 'alertSuccess', 'Save changes');
                    },
                    error: ajaxError
                });
                
            }
            
        }
    };


    // Initialize knockout.js; bind model to DIV
    ko.applyBindings(erModel, $('#editReports_container')[0]);


    // !!!!! TEMP; FOR DEVELOPMENT !!!!!
    $('#editReport_input').val('4');
    $('#editReport').click();
    // !!!!! TEMP; FOR DEVELOPMENT !!!!!
    
    
});


// Handle AJAX errors
function ajaxError(response) {
    
    // Error message; reset DOM
    alert('Error. Could not reach the database. Please check your internet connection and try again.');
//    $('#btn_showThree').prop('disabled','');
    LMD_utilities.ajaxButton($('#btn_save'), 'alertError', 'Save changes');
    LMD_utilities.ajaxButton($('#btn_save'), 'enable');
    
    // Log error to console
    console.log('ajax error :/');
    console.log(response);
}