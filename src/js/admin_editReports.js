$(document).ready(function(){
    
    // !!!!! Add ajaxButton usage throughout; test on slow connection !!!!!
    // !!!!! Add instIDs_shortNames and chart_instIDs_shortNames; ensure that # of IDs matches; write a note to user that short names can't contain commas !!!!!

    // Add "Select report..." to beginning of array; initialize knockout.js; bind model to DIV ("top" model - add/edit/delete reports)
    // Note: `reports` comes from PHP CURL
    reports.unshift({reportID:"0", reportName:"Select report..."});


    // Declare main model for editing sets of report objects
    var erModel = {
        
        // Object to hold list of reports
        reports: reports,
        
        // Object to hold list of "report objects"
        reportObjects: ko.observableArray(),
        
        // Holds reportID of current report
        currentReportID: null,
        
        actions: {
            
            // Click handler: Add a new report
            addReport: function() {
                
                // First, check to see if report name is already taken (case insensitive)
                var newReportName = $('#addReport_input').val();
                var reportNames = [];
                for(var key in erModel.reports) {
                    reportNames.push(erModel.reports[key].reportName.toLowerCase());
                }
                var isTaken = reportNames.indexOf(newReportName.toLowerCase())===-1 ? false : true;

                // If report name is not already taken, proceed
                if (isTaken) {
                    alert('This report name is already taken. Please choose another name.');
                } else {

                    // Send database request to add new report
                    $.ajax({
                        type: "POST",
                        url: "/LastMileData/php/scripts/LMD_REST.php/reports/",
                        data: {
                            reportName: newReportName
                        },
                        dataType: "json",
                        success: function(data) {
                            // Clear report objects; display report name; set reportID; add one blank RO
                            erModel.reportObjects.removeAll();
                            $('#currentReport').text($('#addReport_input').val());
                            erModel.currentReportID = data;
                            erModel.actions.addNewObj();
                        },
                        error: ajaxError
                    });

                }

            },
            
            // Click handler: Edit a report
            editReport: function() {
                
                var reportID = $('#editReport_input').val();
                
                // Load report objects
                erModel.actions.loadReportObjects(reportID);
                
                // Display report name and set currentReportID
                $('#currentReport').text($('#editReport_input option[value=' + reportID + ']')[0].outerText);
                erModel.currentReportID = reportID;
                
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
            loadReportObjects: function(reportID) {
                
                // Send AJAX request to retrieve report objects associated with that report
                $.ajax({
                    type: "GET",
                    url: "/LastMileData/php/scripts/LMD_REST.php/reportObjects/1/" + reportID,
                    dataType: "json",
                    success: function(data) {

                        // !!!!! Be sure to handle cases with reports with zero/one objects !!!!!

                        // Sort `reportObjects` array by displayOrder attribute
                        data.sort(function(a,b){
                            if (Number(a.displayOrder) < Number(b.displayOrder)) { return -1; }
                            else if (Number(a.displayOrder) > Number(b.displayOrder)) { return 1; }
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
                // !!!!! check defaults !!!!!
                erModel.reportObjects.push(ko.mapping.fromJS({
                    reportID: erModel.currentReportID,
                    instIDs: '',
                    ro_name: '',
                    ro_description: '',
                    chart_type: 'line',
                    chart_instIDs: '',
                    archived: 0
                }));
                
            },
            
            // Load metadata from first instance ID
            loadMetadata: function() {
                
                // Reference to current RO
                var self = this;
                var firstInstID = this.instIDs().split(',')[0];

                // Get metadata for first instance ID
                $.ajax({
                    type: "GET",
                    url: "/LastMileData/php/scripts/LMD_REST.php/indicatorInstances/0/" + firstInstID,
                    dataType: "json",
                    success: function(data) {
                        if (data.indName === undefined) {
                            alert('This instance ID is not defined.');
                        } else {
                            // Load metadata into corresponding fields
                            self.ro_name(data.indName);
                            self.ro_description(data.indDefinition);
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
                var queryString = "DELETE FROM lastmile_dataportal.tbl_reportobjects WHERE reportID=" + erModel.currentReportID + ";";
                
                // Parse data back into regular JS array
                var roData = ko.mapping.toJS(erModel.reportObjects);
                
                // Reset display order based on current array order
                var i = 1;
                for (var key in roData) {
                    roData[key].displayOrder = i++;
                    queryString += LMD_utilities.parseJSONIntoSQL(roData[key], "lastmile_dataportal", "tbl_reportobjects", ['id']);
                }

                // If the report name has been changed, add an additional query to the queryString
                if ($('#currentReport_input').length===1) {
                    var newReportName = $('#currentReport_input').val();
                    queryString += "UPDATE lastmile_dataportal.tbl_reports SET reportName='" + LMD_utilities.addSlashes(newReportName) + "' WHERE reportID=" + erModel.currentReportID + ";";
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


    // Click handler: View instructions
    $('#instructions_click').click(function() {
        // Slide down instructions paragraph; change header
        $('#instructions_text').slideDown();
        $('#instructions_click').text('Instructions');
    });
    
    
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


// !!!!! Toggle 
function slidePanels() {
    
}

    // !!!!!!!!!! NEW CODE: END !!!!!!!!!!


    // Initialize dpObjects object (mechanism for assigning unique IDs to sidebar components)
//    var dpObjects = {
//        idList: [],
//        getNewID: function(){
//            var newID = "id_1";
//            while (this.idList.indexOf(newID)!==-1) {
//                var random = Math.floor(Math.random()*(10000))+1;
//                newID = "id_" + random;
//            }
//            this.idList.push(newID);
//            return newID;
//        }
//    };
    
    
    // Populate dpObjects.idList
//    for(var i=0; i<sidebar_model_edit.length; i++){
//        dpObjects.idList.push(sidebar_model_edit[i].id);
//        for(var j=0; j<sidebar_model_edit[i].tabs.length; j++){
//            dpObjects.idList.push(sidebar_model_edit[i].tabs[j].id);
//        }
//    }


    // Create observable model from "raw" model
//    var sidebar_model_obs = ko.mapping.fromJS(sidebar_model_edit);


    // Set click handlers for ADD / DELETE / MOVE buttons
//    var actions = {
//        
//        // Delete OUTER tab
//        deleteOuter: function(){
//            // Get ID of item and corresponding indexes
//            var id = $(event.currentTarget).parent().parent().attr('id');
//            var index = getIndex(id, sidebar_model_obs);
//
//            // Remove the item
//            sidebar_model_obs.splice(index,1);
//        },
//        
//        // Delete INNER tab
//        deleteInner: function(){
//            // Get ID of item and corresponding indexes
//            var id = $(event.currentTarget).parent().parent().attr('id');
//            var index = getIndex(id, sidebar_model_obs);
//
//            // Remove the item
//            sidebar_model_obs()[index.outer].tabs.splice(index.inner,1);
//        },
//        
//        // Add a new OUTER tab
//        addOuter: function(){
//            // Push new outer tab
//            sidebar_model_obs.push(ko.mapping.fromJS({
//                id: dpObjects.getNewID(),
//                name: 'New Outer Tab',
//                tabs: [{
//                    id: dpObjects.getNewID(),
//                    type: 'dp_frag',
//                    name: 'New page',
//                    link: 'Insert link here',
//                    permissions: 'superadmin'
//                }]
//            }));
//            
//        },
//        
//        // Add a new INNER tab
//        addInner: function(data,event){
//            // Get ID of containing DIV and corresponding index
//            var id = $(event.currentTarget).parent().parent().parent().attr('id');
//            var index = getIndex(id, sidebar_model_obs);
//
//            // Push new inner tab to proper outer tab
//            sidebar_model_obs()[index].tabs.push(ko.mapping.fromJS({
//                id: dpObjects.getNewID(),
//                type: 'dp_frag',
//                name: 'New page',
//                link: 'Insert link here',
//                permissions: 'superadmin'
//            }));
//        },
//        
//        // Move OUTER tab up
//        moveOuterUp: function(data,event){
//            // Get ID of item and corresponding indexes
//            var id = $(event.currentTarget).parent().parent().attr('id');
//            var index = getIndex(id, sidebar_model_obs);
//
//            // Move item UP one place
//            if(index!==0) {
//                var item = sidebar_model_obs.splice(index,1)[0];
//                sidebar_model_obs.splice(index-1, 0, item);
//            }
//        },
//        
//        // Move OUTER tab down
//        moveOuterDown: function(data,event){
//            // Get ID of item and corresponding indexes
//            var id = $(event.currentTarget).parent().parent().attr('id');
//            var index = getIndex(id, sidebar_model_obs);
//
//            // Move item DOWN one place
//            var item = sidebar_model_obs.splice(index,1)[0];
//            sidebar_model_obs.splice(index+1, 0, item);
//        },
//        
//        // Move INNER tab up
//        moveInnerUp: function(data,event){
//            // Get ID of item and corresponding indexes
//            var id = $(event.currentTarget).parent().parent().attr('id');
//            var index = getIndex(id, sidebar_model_obs);
//
//            // Move item UP one place
//            if(index.inner!==0) {
//                var item = sidebar_model_obs()[index.outer].tabs.splice(index.inner,1)[0];
//                sidebar_model_obs()[index.outer].tabs.splice(index.inner-1, 0, item);
//            }
//        },
//        
//        // Move INNER tab down
//        moveInnerDown: function(data,event){
//            // Get ID of item and corresponding indexes
//            var id = $(event.currentTarget).parent().parent().attr('id');
//            var index = getIndex(id, sidebar_model_obs);
//
//            // Move item DOWN one place
//            var item = sidebar_model_obs()[index.outer].tabs.splice(index.inner,1)[0];
//            sidebar_model_obs()[index.outer].tabs.splice(index.inner+1, 0, item);
//        }
//        
//    };


    // Initialize knockout.js; bind model to DIV
//    ko.applyBindings({
//        sidebar: sidebar_model_obs,
//        actions: actions
//    }, $('#sidebarDIV_edit')[0]);


    // Serialize the model and send it to the server
//    $('#btn_save').click(function(){
//        
//        var $self = $(this);
//        
//        // Manipulate DOM
//        LMD_utilities.ajaxButton($self, 'ajaxLoader');
//        
//        var objectData = ko.mapping.toJSON(sidebar_model_obs);
//        var queryString = "UPDATE lastmile_dataportal.tbl_json_objects SET objectData='" + LMD_utilities.addSlashes(objectData) + "' WHERE objectName='Data Portal sidebar'";
//        var myData = {'queryString': queryString} ;
//        $.ajax({
//                type: "POST",
//                url: "/LastMileData/php/scripts/ajaxSendQuery.php",
//                data: myData,
//                dataType: "json",
//                success: function(data) {
//                    // Manipulate DOM
//                    LMD_utilities.ajaxButton($self, 'alertSuccess', 'Save changes');
//                    LMD_utilities.ajaxButton($self, 'enable');
//                },
//                error: function() {
//                    // Error message; reset DOM
//                    alert('Error. Could not reach the database. Please try again.');
//                    LMD_utilities.ajaxButton($self, 'alertError', 'Save changes');
//                    LMD_utilities.ajaxButton($self, 'enable');
//                }
//        });
//    });
    
    


        
// Given the ID of an inner/outer tab, return the current index(es) representing its position
// Assumes that all IDs are unique, regardless of whether the tab is an "inner" or "outer" tab
//function getIndex(id, sidebar_model) {
//
//    var match = 'not found';
//
//    // Test outer tabs
//    for(var i=0; i<sidebar_model().length; i++) {
//        if(sidebar_model()[i].id() === id) {
//            match = i;
//        }
//    }
//
//    // Test inner tabs
//    for(var i=0; i<sidebar_model().length; i++) {
//        for(var j=0; j<sidebar_model()[i].tabs().length; j++) {
//            console.log(sidebar_model()[i].tabs()[j].id());
//            if(sidebar_model()[i].tabs()[j].id() === id) {
//                match = {
//                    outer: i,
//                    inner: j
//                };
//            }
//        }
//    }
//
//    return match;
//}
