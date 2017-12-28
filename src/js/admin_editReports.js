$(document).ready(function(){
    
    // !!!!! Add ajaxButton usage throughout; test on slow connection !!!!!
    // !!!!! Add labels_table and labels_chart; ensure that # of IDs matches; write a note to user that short names can't contain commas !!!!!

    // Add "Select report..." to beginning of array; initialize knockout.js; bind model to DIV ("top" model - add/edit/delete reports)
    // Note: `reports` comes from PHP CURL
    reports.unshift({report_id:"0", report_name:"Select report..."});


    // Declare main model for editing sets of report objects
    var erModel = {
        
        // Object to hold list of reports
        reports: reports,
        
        // Object to hold list of "report objects"
        reportObjects: ko.observableArray(),
        
        // Holds report_id of current report
        currentReportID: null,
        
        actions: {
            
            // Click handler: Add a new report
            addReport: function() {
                
                // First, check to see if report name is already taken (case insensitive)
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
                        type: "POST",
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
                var confirm = window.confirm("Are you sure you want to delete this report?");

                // Proceed with "deletion" (archiving)
                if (confirm) {

                    // !!!!! Integrate "AJAX button" utility function ?????

                    // Send database request to add new report
                    $.ajax({
                        type: "PUT",
                        url: "/LastMileData/php/scripts/LMD_REST.php/reports/" + $('#deleteReport_input').val(),
                        data: { archived: 1 },
                        dataType: "json",
                        success: function() {
                            window.location.reload();
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
                    labels_table: '',
                    labels_chart: '',
                    archived: 0
                }));
                
            },
            
            // Load metadata from first instance ID
            loadMetadata: function() {
                
                // Reference to current RO
                var self = this;
                var first_inst_id = this.inst_ids().split(',')[0];

                // Get metadata for first instance ID
                $.ajax({
                    type: "GET",
                    url: "/LastMileData/php/scripts/LMD_REST.php/indicatorInstances/0/" + first_inst_id,
                    dataType: "json",
                    success: function(data) {
                        if (data.ind_name === undefined) {
                            alert('This instance ID is not defined.');
                        } else {
                            // Load metadata into corresponding fields
                            self.ro_name(data.ind_name);
                            self.ro_description(data.ind_definition);
                        }
                    },
                    error: ajaxError
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
                
                console.log($(event.currentTarget).closest('.roContainer').find('.advancedOptions').removeClass('hide'));
//                var index = Number($(event.currentTarget).closest('.roContainer').attr('index'));
//                var archived = Number(erModel.reportObjects()[index].archived());
//                erModel.reportObjects()[index].archived(1-archived);
                
            },
            
            checkIndIDsTable: function() {
                var length_1 = $(event.currentTarget).val().split(',').length;
                console.log('table');
                console.log(length_1);
            },
            
            checkIndIDsChart: function() {
                var string1 = $(event.currentTarget).val();
                console.log('chart');
                console.log($(event.currentTarget).val());
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