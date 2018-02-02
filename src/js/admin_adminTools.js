$(document).ready(function(){
    
    var adminModel = {
        
        // Get configuration object; load into a KO observable object
        // configuration object (portal_config) is loaded from server via CURL in page_dataPortal.php
        config: ko.mapping.fromJS(portal_config),
        
        actions: {
            
            // Click handler: Data peek
            peek: function() {
                this.config.peek(!this.config.peek());
                // Sync with server
            },
            
            // Click handler: Suppress data
            suppress: function() {
                this.config.suppress(!this.config.suppress());
                // Sync with server
            },


            // Click handler: Down for maintenance
            maintenance: function() {
                this.config.maintenance(!this.config.maintenance());
                // Sync with server
            },
            
            // Click handler: Save configuration changes
            saveChanges: function() {
                
                var $saveButton = $('#saveButton');
                
                // Manipulate DOM
                LMD_utilities.ajaxButton($saveButton, 'ajaxLoader');
                
                var object_data = ko.mapping.toJSON(adminModel.config);
                var queryString = "UPDATE lastmile_dataportal.tbl_json_objects SET object_data='" + LMD_utilities.addSlashes(object_data) + "' WHERE id=3";
                var myData = {'queryString': queryString} ;
                $.ajax({
                        type: "POST",
                        url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                        data: myData,
                        dataType: "json",
                        success: function(data) {
                            // Manipulate DOM
                            LMD_utilities.ajaxButton($saveButton, 'alertSuccess', 'Save configuration changes');
                            LMD_utilities.ajaxButton($saveButton, 'enable');
                        },
                        error: function() {
                            // Error message; reset DOM
                            alert('Error. Could not reach the database. Please try again.');
                            LMD_utilities.ajaxButton($saveButton, 'alertError', 'Save configuration changes');
                            LMD_utilities.ajaxButton($saveButton, 'enable');
                        }
                });
                
                
            }

        }
    }
    
    // Initialize knockout.js; bind model to DIV
    ko.applyBindings(adminModel, $('#outerDiv')[0]);
    
});