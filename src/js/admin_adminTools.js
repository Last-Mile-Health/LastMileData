$(document).ready(function(){
    
    var adminModel = {
        
        // Get configuration object; load into a KO observable object
        // configuration object (portal_config) is loaded from server via CURL in page_dataPortal.php
        config: ko.mapping.fromJS(portal_config),
        
        actions: {
            
            // Click handler: Data peek
            peek: function() {
                this.config.peek(!this.config.peek());
                LMD_utilities.ajaxButton($(window.event.target), 'ajaxLoader');
                syncWithServer($(window.event.target),'peek');
            },
            
            // Click handler: Suppress data
            suppress: function() {
                this.config.suppress(!this.config.suppress());
                LMD_utilities.ajaxButton($(window.event.target), 'ajaxLoader');
                syncWithServer($(window.event.target),'suppress');
            },


            // Click handler: Down for maintenance
            maintenance: function() {
                this.config.maintenance(!this.config.maintenance());
                LMD_utilities.ajaxButton($(window.event.target), 'ajaxLoader');
                syncWithServer($(window.event.target),'maintenance');
            }

        }
    }
    
    // Initialize knockout.js; bind model to DIV
    ko.applyBindings(adminModel, $('#outerDiv')[0]);
    
    
    // Sync configuration changes with server; called above
    function syncWithServer($button,tool) {

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
                    LMD_utilities.ajaxButton($button, 'alertSuccess', 'Toggle');
                    setTimeout(function(){
                        LMD_utilities.ajaxButton($button, 'enable', 'Toggle');
                    },2001);
                },
                error: function() {
                    // Error message; reset DOM
                    adminModel.config[tool](!adminModel.config[tool]());
                    alert('Error. Could not reach the database. Please try again.');
                    LMD_utilities.ajaxButton($button, 'alertError', 'Toggle');
                    setTimeout(function(){
                        LMD_utilities.ajaxButton($button, 'enable', 'Toggle');
                    },2001);
                }
        });

    }    
    
});
