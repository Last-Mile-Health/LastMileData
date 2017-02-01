$(document).ready(function(){

    // Declare object to hold "report objects"
    var reportObjects = ko.observableArray();


    // Add "Select report..." to beginning of array; initialize knockout.js; bind model to DIV ("top" model - add/edit/delete reports)
    // Note: `reports` comes from PHP CURL
    reports.unshift({reportID:"0", reportName:"Select report..."});
    ko.applyBindings({
        reports: reports
//        ,actions: actions
    }, $('#editReports_top')[0]);


    // Declare main model for editing sets of report objects
    var erModel = {
        reportObjects: reportObjects,
        // Given the ID of an inner/outer tab, return the current index(es) representing its position
        getIndex: function(id) {
            var match = 'not found';
            for(var i=0; i<this.reportObjects().length; i++) {
                if(this.reportObjects()[i].id() === id) {
                    match = i;
                }
            }
            
            return match;
        },
        actions: {
            
            // Delete report object
            deleteObj: function(){
                
                // Display "confirm" dialog box
                var confirm = window.confirm("Are you sure you want to delete this object?");
                if (confirm) {
                    // Get ID of item and corresponding index
                    var id = $(event.currentTarget).parent().parent().attr('id');
                    var index = erModel.getIndex(id); // !!!!! how do I change this to a `this` reference ?????

                    // Remove the item
                    erModel.reportObjects.splice(index,1); // !!!!! how do I change this to a `this` reference ?????
                }
                
            },

            // Move report object up
            moveObjUp: function(data,event){
                
                // Get ID of item and corresponding index
                var id = $(event.currentTarget).parent().parent().attr('id');
                var index = erModel.getIndex(id); // !!!!! how do I change this to a `this` reference ?????

                // Move item UP one place
                if(index!==0) {
                    var item = erModel.reportObjects.splice(index,1)[0]; // !!!!! how do I change this to a `this` reference ?????
                    erModel.reportObjects.splice(index-1, 0, item); // !!!!! how do I change this to a `this` reference ?????
                }
                
            },

            // Move report object down
            moveObjDown: function(data,event){
                
                // Get ID of item and corresponding index
                var id = $(event.currentTarget).parent().parent().attr('id');
                var index = erModel.getIndex(id); // !!!!! how do I change this to a `this` reference ?????

                // Move item DOWN one place
                var item = erModel.reportObjects.splice(index,1)[0]; // !!!!! how do I change this to a `this` reference ?????
                erModel.reportObjects.splice(index+1, 0, item); // !!!!! how do I change this to a `this` reference ?????
            },

            // Add a new report object
            // !!!!! change "outer/inner" language throughout !!!!!
            addNewRO: function(){
                
                // Get maximum ID
                $.ajax({
                    type: "GET",
                    url: "/LastMileData/php/scripts/LMD_REST.php/max/lastmile_dataportal/tbl_reportobjects/id",
                    dataType: "json",
                    success: function(data) {
                        // Create new report object; load defaults
                        erModel.reportObjects.push(ko.mapping.fromJS({ // !!!!! how do I change this to a `this` reference ?????
                            id: data.max,
                            instIDs: '',
                            ro_name: '',
                            ro_description: '',
                            chart_type: 'line',
                            chart_instIDs: ''
                        }));
                    },
                    error: ajaxError
                });
                
            },
            
            // Load metadata from first instance ID
            loadMetadata: function(){
                
                var self = this;
                
                var firstInstID = this.instIDs().split(',')[0];

                // Get metadata for first instance ID
                $.ajax({
                    type: "GET",
                    url: "/LastMileData/php/scripts/LMD_REST.php/indicatorInstances/" + firstInstID,
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
            
            // Save all changes
            saveChanges: function(){
                
                // !!!!! Loop through report objects and parse query string
                
                
                // !!!!! Send all changes to server via AJAX in a single transaction
                
            }
            
        }
    };


    // Initialize knockout.js; bind model to DIV ("bottom" model - edit report objects)
    ko.applyBindings(erModel, $('#editReports_bottom')[0]);


    // Click handler: Add a new report
    $('#addReport').click(function(){
        
        // First, check to see if report name is already taken (case insensitive)
        var newReportName = $('#addReport_input').val();
        var reportNames = [];
        for(var key in reports) {
            reportNames.push(reports[key].reportName.toLowerCase());
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
                    console.log('success!');
                    // !!!!! `data` returned is ID of new report !!!!!
                    console.log(data);
                    // !!!!! Go to editor for new report; functionize so that #editReport click handler uses same code !!!!!
                },
                error: ajaxError
            });
            
        }
        
    });
    
    
    // Click handler: Edit a report
    $('#editReport').click(function(){
        
        // Send AJAX request to retrieve report objects associated with that report
        $.ajax({
            type: "GET",
            url: "/LastMileData/php/scripts/LMD_REST.php/reportObjects/" + $('#editReport_input').val(),
            dataType: "json",
            success: function(data) {
                
                // !!!!! Be sure to handle cases with reports with zero/one objects !!!!!
                
                // Sort `reportObjects` array by displayOrder attribute
                // !!!!! `displayOrder` is going to need to be reset by the application right before changes are sent to the server !!!!!
                data.sort(function(a,b){
                    if (Number(a.displayOrder) < Number(b.displayOrder)) { return -1; }
                    else if (Number(a.displayOrder) > Number(b.displayOrder)) { return 1; }
                    else {
                        return 0;
                    }
                });
                
                
                // Clear reportObjects array; refill with new objects
                reportObjects.removeAll();
                
                for (var key in data) {
                    reportObjects.push(ko.mapping.fromJS(data[key]));
                }
                
                // !!!!! Testing !!!!!
//                console.log(reportObjects()[0].instIDs);
//                setTimeout(function(){
//                    reportObjects()[0].instIDs("12345");
//                    console.log(reportObjects()[0].instIDs());
//                },1000);
                
                
            },
            error: ajaxError
        });
        
        
    });
    
    
    // Click handler: Delete a report
    $('#deleteReport').click(function(){
        
        // Prompt user with dialog box to confirm deletion
        var confirm = window.confirm("Are you sure you want to delete this report?");
        
        // Proceed with deletion
        // Note: in reality, report is "archived", not deleted, and can be restored by a DBA
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
        
        // !!!!! Reload page
        
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
