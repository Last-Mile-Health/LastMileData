$(document).ready(function(){


    // Sort indicatorInstances
    indicators.sort(function(a,b){
        // Sort 1: "Category"
        if (a.indCategory < b.indCategory) { return -1; }
        else if (a.indCategory > b.indCategory) { return 1; } 
        else {
            // Sort 2: "Indicator name"
            if (a.indName < b.indName) { return -1; }
            else if (a.indName > b.indName) { return 1; } 
            else {
                return 0;
            }
        }
    });


    // Set actions object
    var actions = {
        click: function(data,event) {
            $(event.currentTarget).select();
        },
        delete: function(data,event) {
            // !!!!! "Beautify" the confirm dialog !!!!!
            var confirmed = confirm("Do you really want to delete this indicator?");
            if (confirmed) {
                myViewModel.delete(data._cid);
                DataPortal_GLOBALS.anyChanges = true;
                $submit.prop('disabled','');
            }
        },
        change: function(data,event) {
            myViewModel.markAsChanged(data._cid);
            DataPortal_GLOBALS.anyChanges = true;
            $submit.prop('disabled','');
        }
    };


    // Generate "selectLists" object
    // !!!!! WET with admin_editData !!!!!
    var selectLists = {
        category: ["Category..."]
    };
    for (var key in indicators) {
        var category = indicators[key].indCategory;
        if (selectLists.category.indexOf(category)===-1) {
            selectLists.category.push(category);
        }
    }


    // Create new ViewModel
    var myViewModel = LMD_koREST.newViewModel({
        url: '/LastMileData/php/scripts/LMD_REST.php/indicators/',
        element: $('#outerDiv')[0],
        idAttribute: 'indID',
        other: {
            actions: actions,
            selectLists: selectLists
        }
    });


    // Bind data to DOM
    myViewModel.reset(indicators);


    // Add a new indicator; scroll down
    // !!!!! Define Modle Defaults has to be called with every add (instead of once); refactor LMD_koREST.js accordingly !!!!!
    $('#btn_add').click(function(){

        // Define model default parameters
        // !!!!! Need to be able to infer fields from the server
        myViewModel.defineModelDefauls({
            indCategory: "",
            indName:"New indicator",
            indTarget:"",
            indNarrative:"",
            indDefinition:""
        });

        myViewModel.addNew();
        DataPortal_GLOBALS.anyChanges = true;
        $submit.prop('disabled','');
        // !!!!! Need to find a way to get height of DIV and scroll more reliably !!!!!
        $("#scrollContent").animate({ scrollTop: 20000 }, 2000);
        
    });


    // Save changes to server
    $('#btn_submit').click(function(){
        
        // Manipulate DOM
        LMD_utilities.ajaxButton($submit, 'ajaxLoader');

        myViewModel.sync({
            successCallback: function() {
                // Reset anyChanges flag; manipulate DOM
                DataPortal_GLOBALS.anyChanges = false;
                LMD_utilities.ajaxButton($submit, 'alertSuccess', 'Submit');
            },
            errorCallback: function() {
                // Error message; reset DOM
                alert('Error. Could not reach the database. Please try again.');
                LMD_utilities.ajaxButton($submit, 'alertError', 'Submit');
                LMD_utilities.ajaxButton($submit, 'enable');
            }
        });
    });


    // Submit button disabled by default; reset anyChanges flag
    var $submit = $('#btn_submit');
    LMD_utilities.ajaxButton($submit, 'disable');
    DataPortal_GLOBALS.anyChanges = false;

    
    
    

//      !!!!! Refactor the code below into LMD_koREST !!!!!
//    $('#!!!!! OLD btn_submit !!!!!').click(function(){
//
//        // Create object to handle ajax info
//        var submitResults = {
//            pendingChanges: changedData.changes.length,
//            pendingDeletes: changedData.deletions.length,
//            numErrors: 0
//        };
//
//        // Initialize rep counter; set up loop
//        var reps = 1;
//        var myTimer = setInterval(function(){
//
//            // (1) Save model additions / changes
//            myRows.each(function(data){
//                // Only proceed if item is in changedData.changes array
//                var index = changedData.changes.indexOf(data.cid);
//                if(index!==-1) {
//                    // Remove CID from changedData array
//                    changedData.changes.splice(index,1);
//
//                    // Send additions / changes to server
//                    data.save({},{
//                        success: function(c,r,o) {
//                            // Decrement "pendingChanges" counter
//                            submitResults.pendingChanges--;
//                        },
//                        error: function(c,r,o) {
//                            // If change failed, re-insert the CID back into the array
//                            changedData.addChange(c.cid);
//                        }
//                    });
//                }
//            });
//
//            // (2) Save model deletions
//            for (var key in changedData.deletions) {
//                var myModel = changedData.deletions[key];
//                myModel.destroy({
//                    success: function() {
//                        submitResults.pendingDeletes--;
//                    }
//                    // !!!!! errors are not currently being handled; they should be handled as above !!!!!
//                });
//            }
//
//            // If all are done, clear timer and run success "callback"
//            if (submitResults.pendingChanges + submitResults.pendingDeletes === 0) {
//                // !!!!! WET with admin_editData !!!!!
//                // Clear timer; reset anyChanges flag; reset DOM
//                clearInterval(myTimer);
//                DataPortal_GLOBALS.anyChanges = false;
//                $submit.html("Success!");
//                var color = "white";
//                var interval = setInterval(function() {
//                    color = (color==="white") ? "yellow" : "white";
//                    $submit.css('color',color);
//                },100);
//                setTimeout(function() {
//                    $submit.css('color',"white");
//                    $submit.html("Submit");
//                    clearInterval(interval);
//                },2000);
//                $submit.prop('disabled','');
//            }
//
//            // Increment counter
//            reps++;
//
//            // If timeout of 20 seconds has been reached, display error message, reset DOM, and end loop
//            if(reps===20) {
//                alert('Error. Could not reach the database. Please try again.');
//                $submit.prop('disabled','');
//                $submit.html("Submit");
//                clearInterval(myTimer);
//            }
//
//        }, 1000);
//
//    });


    // Change handler: FILTER TABLE BASED ON CATEGORY
    // !!!!! (mostly) WET with admin_editData !!!!!
    $('.dataFilter').change(function() {

        var filterCategory = $('#filter_category').val();

        $('.filterRow').each(function() {
            $(this).removeClass('hide');
            var rowCategory = $(this).find('.filterCategory').val();
            if (filterCategory!=='Category...' && filterCategory !== rowCategory) {
                $(this).addClass('hide');
            }
        });
        
    });
    
});
