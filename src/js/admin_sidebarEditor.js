$(document).ready(function(){

    // Initialize dpObjects object (mechanism for assigning unique IDs to sidebar components)
    var dpObjects = {
        idList: [],
        getNewID: function(){
            var newID = "id_1";
            while (this.idList.indexOf(newID)!==-1) {
                var random = Math.floor(Math.random()*(10000))+1;
                newID = "id_" + random;
            }
            this.idList.push(newID);
            return newID;
        }
    };
    
    
    // Populate dpObjects.idList
    for(var i=0; i<sidebar_model_edit.length; i++){
        dpObjects.idList.push(sidebar_model_edit[i].id);
        for(var j=0; j<sidebar_model_edit[i].tabs.length; j++){
            dpObjects.idList.push(sidebar_model_edit[i].tabs[j].id);
        }
    }


    // Create observable model from "raw" model
    var sidebar_model_obs = ko.mapping.fromJS(sidebar_model_edit);


    // Set click handlers for ADD / DELETE / MOVE buttons
    var actions = {
        
        // Delete OUTER tab
        deleteOuter: function(){
            // Get ID of item and corresponding indexes
            var id = $(event.currentTarget).parent().attr('id');
            var index = getIndex(id, sidebar_model_obs);

            // Remove the item
            sidebar_model_obs.splice(index,1);
        },
        
        // Delete INNER tab
        deleteInner: function(){
            // Get ID of item and corresponding indexes
            var id = $(event.currentTarget).parent().attr('id');
            var index = getIndex(id, sidebar_model_obs);

            // Remove the item
            sidebar_model_obs()[index.outer].tabs.splice(index.inner,1);
        },
        
        // Add a new OUTER tab
        addOuter: function(){
            // Push new outer tab
            sidebar_model_obs.push(ko.mapping.fromJS({
                id: dpObjects.getNewID(),
                name: 'New Outer Tab',
                tabs: [{
                    id: dpObjects.getNewID(),
                    type: 'dp_frag',
                    name: 'New page',
                    link: 'Insert link here',
                    permissions: 'superadmin'
                }]
            }));
            
        },
        
        // Add a new INNER tab
        addInner: function(data,event){
            // Get ID of containing DIV and corresponding index
            var id = $(event.currentTarget).parent().parent().attr('id');
            var index = getIndex(id, sidebar_model_obs);

            // Push new inner tab to proper outer tab
            sidebar_model_obs()[index].tabs.push({
                id: dpObjects.getNewID(),
                type: 'dp_frag',
                name: 'New page',
                link: 'Insert link here',
                permissions: 'superadmin'
            });
        },
        
        // Move OUTER tab up
        moveOuterUp: function(data,event){
            // Get ID of item and corresponding indexes
            var id = $(event.currentTarget).parent().attr('id');
            var index = getIndex(id, sidebar_model_obs);

            // Move item UP one place
            if(index!==0) {
                var item = sidebar_model_obs.splice(index,1)[0];
                sidebar_model_obs.splice(index-1, 0, item);
            }
        },
        
        // Move OUTER tab down
        moveOuterDown: function(data,event){
            // Get ID of item and corresponding indexes
            var id = $(event.currentTarget).parent().attr('id');
            var index = getIndex(id, sidebar_model_obs);

            // Move item DOWN one place
            var item = sidebar_model_obs.splice(index,1)[0];
            sidebar_model_obs.splice(index+1, 0, item);
        },
        
        // Move INNER tab up
        moveInnerUp: function(data,event){
            // Get ID of item and corresponding indexes
            var id = $(event.currentTarget).parent().attr('id');
            var index = getIndex(id, sidebar_model_obs);

            // Move item UP one place
            if(index.inner!==0) {
                var item = sidebar_model_obs()[index.outer].tabs.splice(index.inner,1)[0];
                sidebar_model_obs()[index.outer].tabs.splice(index.inner-1, 0, item);
            }
        },
        
        // Move INNER tab down
        moveInnerDown: function(data,event){
            // Get ID of item and corresponding indexes
            var id = $(event.currentTarget).parent().attr('id');
            var index = getIndex(id, sidebar_model_obs);

            // Move item DOWN one place
            var item = sidebar_model_obs()[index.outer].tabs.splice(index.inner,1)[0];
            sidebar_model_obs()[index.outer].tabs.splice(index.inner+1, 0, item);
        }
        
    };


    // Initialize knockout.js; bind model to DIV
    ko.applyBindings({
        sidebar: sidebar_model_obs,
        actions: actions
    }, $('#sidebarDIV_edit')[0]);


    // Serialize the model and send it to the server
    $('#btn_save').click(function(){
        
        var $self = $(this);
        
        // Manipulate DOM
        LMD_utilities.ajaxButton($self, 'ajaxLoader');
        
        var objectData = ko.mapping.toJSON(sidebar_model_obs);
        var queryString = "UPDATE lastmile_dataportal.tbl_json_objects SET objectData='" + LMD_utilities.addSlashes(objectData) + "' WHERE objectName='Data Portal sidebar'";
        var myData = {'queryString': queryString, 'rKey': 1, 'transaction': 0} ;
        $.ajax({
                type: "POST",
                url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                data: myData,
                dataType: "json",
                success: function(data) {
                    // Manipulate DOM
                    LMD_utilities.ajaxButton($self, 'alertSuccess', 'Save changes');
                    LMD_utilities.ajaxButton($self, 'enable');
                },
                error: function() {
                    // Error message; reset DOM
                    alert('Error. Could not reach the database. Please try again.');
                    LMD_utilities.ajaxButton($self, 'alertError', 'Save changes');
                    LMD_utilities.ajaxButton($self, 'enable');
                }
        });
    });







    // Run "applyJquery" function and changes
//    applyJquery();

    // Create an event loop to apply the "applyJquery" function on model initialization and whenever model undergoes a "configuration change"
    // model "configuration changes" include (1) addition of an outer/inner tab, (2) deletion of an outer/inner tab, or (3) re-ordering of tabs
//    var oldHash = "";
//    setInterval(function(){
//        var newHash = dpObjects.idHash();
//        if(oldHash!==newHash) {
//            applyJquery();
//        }
//        oldHash = newHash;
//    }, 50);


    // These functions are run whenever model "size" changes (i.e. )
//    function applyJquery() {
//        // !!!!! permissions change when inner tab is moved !!!!!
//        $(".permissions").multiselect({
//            selectedList: 3
//        });
//    }


//    $('#sidebarDIV_edit').sortable();

//    activateSortable();
    
    // jQuery UI sortable: outer tabs
//    function activateSortable() {
//        $('#sidebarDIV_edit2').sortable({
//            cursor: "move",
//            start: function(event, ui) {
//                ui.item.moveStartIndex = ui.item.index();
//            },
//            stop: function(event, ui) {
//                ui.item.moveEndIndex = ui.item.index();
//    //            $('#sidebarDIV_edit2').sortable("cancel");
//                $('#sidebarDIV_edit2').sortable("destroy");
//                sidebar_model_obs.splice(0,1);
//                activateSortable();
//    //            var reference = sidebar_model_obs()[ui.item.moveStartIndex];
//    //            var reference = ko.mapping.toJS(sidebar_model_obs()[ui.item.moveStartIndex]);
//    //            var item = JSON.parse(JSON.stringify(reference)); // !!!!! might not be needed !!!!!
//    //            AVI_splice();
//    //            var item = ko.mapping.toJS(sidebar_model_obs()[ui.item.moveStartIndex]);
//    //            sidebar_model_obs.splice(ui.item.moveStartIndex,1);
//    //            sidebar_model_obs.splice(ui.item.moveEndIndex, 0, item);
//            }
//        });
//    }
    
//    function AVI_splice() {
//        setTimeout(function(){
//            sidebar_model_obs.splice(0,1);
//        },3000);
//    }

//    activateSortable();

//    function activateSortable() {
//
//        // jQuery UI sortable: inner tabs
//        $('.outerTab').sortable({
//            cursor: 'move',
//            items: '> .innerTab',
//            connectWith: '.outerTab',
//            start: function(event, ui) {
//                // Get start index of item and start outer DIV
//                ui.item.moveStartIndex = ui.item.index();
//                ui.item.moveStartDiv = $(event.target)[0].id;
//            },
//            over: function(event, ui) {
//                // Get end outer DIV
//                ui.item.moveEndDiv = event.target.id;
//            },
//            beforeStop: function(event, ui){
//                // Get end index
//                ui.item.moveEndIndex = ui.helper.index();
//            },
//            stop: function(event, ui) {
//
//                // Get start/end DIV indexes
//                for(var i=0; i<sidebar_model_edit.length; i++) {
//                    if (sidebar_model_edit[i].id === ui.item.moveStartDiv) {
//                        var modelStartDivIndex = i;
//                    } else if (sidebar_model_edit[i].id === ui.item.moveEndDiv) {
//                        var modelEndDivIndex = i;
//                    }
//                }
//                // If modelEndDivIndex is undefined, then the inner tab didn't move to a different outer DIV
//                if (modelEndDivIndex === undefined) {
//                    var modelEndDivIndex = modelStartDivIndex;
//                }
//
//                // Cancel the jQueryUI DOM manipulation
//                // NOTE: There are many bugs in this behavior associated with different combinations of "moves". The only way I've found to get around these is to reset the sortable, destroy it, and reset the sortable again
//                $('.outerTab').sortable();
//                $('.outerTab').sortable("cancel");
//                $('.outerTab').sortable("destroy");
//
//                // Use rivets to remove the item from its original position and set it in its new position
//                // !!!!! all indexes below are behaving oddly; needed to switch from "-1" to "-2" !!!!!
//                var reference = sidebar_model_edit[modelStartDivIndex].tabs[ui.item.moveStartIndex-2];
////                        var reference = sidebar_model_edit[modelStartDivIndex].tabs[ui.item.moveStartIndex-1];
//                var item = JSON.parse(JSON.stringify(reference));
//                sidebar_model_edit[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-2,1);
////                        sidebar_model_edit[modelStartDivIndex].tabs.splice(ui.item.moveStartIndex-1,1);
//                sidebar_model_edit[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-2, 0, item);
////                        sidebar_model_edit[modelEndDivIndex].tabs.splice(ui.item.moveEndIndex-1, 0, item);
//
//                // Reset the sortable
//                activateSortable();
//            }
//        });
//    }


});


        
// Given the ID of an inner/outer tab, return the current index(es) representing its position
// Assumes that all IDs are unique, regardless of whether the tab is an "inner" or "outer" tab
function getIndex(id, sidebar_model) {

    var match = 'not found';

    // Test outer tabs
    for(var i=0; i<sidebar_model().length; i++) {
        if(sidebar_model()[i].id() === id) {
            match = i;
        }
    }

    // Test inner tabs
    for(var i=0; i<sidebar_model().length; i++) {
        for(var j=0; j<sidebar_model()[i].tabs().length; j++) {
            if(sidebar_model()[i].tabs()[j].id() === id) {
                match = {
                    outer: i,
                    inner: j
                };
            }
        }
    }

    return match;
}
