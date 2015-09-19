$(document).ready(function(){
    
    // Create model
    var Row = Backbone.Model.extend({
        idAttribute: "indID",
        defaults: { indName:"New indicator" }
     });

    // Create collection
    var Rows = Backbone.Collection.extend({
        model: Row,
        url: '/LastMileData/php/scripts/LMD_REST.php/indicators/'
    });

    // Instantiate collection
    var myRows = new Rows();

    // Reset collection with data
    myRows.reset(indicatorList);

    // Submit button disabled by default; reset anyChanges flag
    var $submit = $('#btn_submit');
    $submit.prop('disabled','disabled');
    DataPortal_GLOBALS.anyChanges = false;

    // Create "changedData" object to hold changed values
    // !!!!! somewhat WET with admin_editData !!!!!
    var changedData = {
        changes: [],
        deletions: [],
        addChange: function(cid) {
            if (this.changes.indexOf(cid)===-1) {
                this.changes.push(cid);
                DataPortal_GLOBALS.anyChanges = true;
                $submit.prop('disabled','');
            }
        },
        addDeletion: function(model) {
    //                    if (this.changes.indexOf(model.cid)===-1) { // !!!!! need to distinguish between additions and changes ?????
            this.deletions.push(model);
            DataPortal_GLOBALS.anyChanges = true;
            $submit.prop('disabled','');
        }
    };

    // Set on-remove handler
    myRows.on('remove',function(model){
        model.urlRoot = myRows.url;
        changedData.addDeletion(model);
    });

    // Set click handlers for DELETE buttons
    var actions = {
        delete: function() {
            // !!!!! "Beautify" the confirm dialog !!!!!
            var confirmed = confirm("Do you really want to delete this indicator?");
            if (confirmed) {
                var cid = $(this).attr('data-cid');
                myRows.remove(cid);
            }
        },
        change: function(ev) {
            var cid = $(ev.target).parent().parent().attr('data-cid');
            changedData.addChange(cid);
        },
        click: function() {
            $(this).select();
        }
    };

    // Generate "selectLists" object
    // !!!!! WET with admin_editData !!!!!
    var selectLists = {
        category: ["Category..."],
        cut: ["Cut..."]
    };
    for (var key in indicatorList) {
        var category = indicatorList[key].indCategory;
        var cut = indicatorList[key].indCut;
        if (selectLists.category.indexOf(category)===-1) {
            selectLists.category.push(category);
        }
        if (selectLists.cut.indexOf(cut)===-1) {
            selectLists.cut.push(cut);
        }
    }

    // Bind #scrollContent with Rivets.js
    rivets.bind($('#outerDiv'), {indicators: myRows.models, actions: actions, selectLists: selectLists});

    // Add a new indicator; scroll down
    $('#btn_add').click(function(){
        var x = new Row();
        myRows.add(x);
        changedData.addChange(x.cid);
        DataPortal_GLOBALS.anyChanges = true;
        
        // !!!!! Need to find a way to get height of DIV and scroll more reliably !!!!!
        $("#scrollContent").animate({ scrollTop: 20000 }, 2000);
    });

    $('#btn_submit').click(function(){

        // Manipulate DOM
        $submit.prop('disabled','disabled');
        $submit.html("<img src='../images/ajax_loader.gif'>");

        // Create object to handle ajax info 
        var submitResults = {
            pendingChanges: changedData.changes.length,
            pendingDeletes: changedData.deletions.length,
            numErrors: 0
        };

        // Initialize rep counter; set up loop
        var reps = 1;
        var myTimer = setInterval(function(){

            // (1) Save model additions / changes
            myRows.each(function(data){
                // Only proceed if item is in changedData.changes array
                var index = changedData.changes.indexOf(data.cid);
                if(index!==-1) {
                    // Remove CID from changedData array
                    changedData.changes.splice(index,1);

                    // Send additions / changes to server
                    data.save({},{
                        success: function(c,r,o) {
                            // Decrement "pendingChanges" counter
                            submitResults.pendingChanges--;
                        },
                        error: function(c,r,o) {
                            // If change failed, re-insert the CID back into the array
                            changedData.addChange(c.cid);
console.log('error triggered!!!');
                        }
                    });
                }
            });

            // (2) Save model deletions
            for (var key in changedData.deletions) {
                var myModel = changedData.deletions[key];
                myModel.destroy({
                    success: function() {
                        submitResults.pendingDeletes--;
                    }
                    // !!!!! errors are not currently being handled; they should be handled as above !!!!!
                });
            }

            // If all are done, clear timer and run success "callback"
            if (submitResults.pendingChanges + submitResults.pendingDeletes === 0) {
                // !!!!! WET with admin_editData !!!!!
                // Clear timer; reset anyChanges flag; reset DOM
                clearInterval(myTimer);
                DataPortal_GLOBALS.anyChanges = false;
                $submit.html("Success!");
                var color = "white";
                var interval = setInterval(function() {
                    color = (color==="white") ? "yellow" : "white";
                    $submit.css('color',color);
                },100);
                setTimeout(function() {
                    $submit.css('color',"white");
                    $submit.html("Submit");
                    clearInterval(interval);
                },2000);
                $submit.prop('disabled','');
            }

            // Increment counter
            reps++;

            // If timeout of 20 seconds has been reached, display error message, reset DOM, and end loop
            if(reps===20) {
                alert('Error. Could not reach the database. Please try again.');
                $submit.prop('disabled','');
                $submit.html("Submit");
                clearInterval(myTimer);
            }

        }, 1000);

    });

    // Change handler: FILTER TABLE BASED ON CUT
    // !!!!! (mostly) WET with admin_editData !!!!!
    $('.dataFilter').change(function() {

        var filterCategory = $('#filter_category').val();
        var filterCut = $('#filter_cut').val();

        $('.filterRow').each(function() {
            $(this).removeClass('hide');
            var rowCategory = $(this).find('.filterCategory').val();
            var rowCut = $(this).find('.filterCut').val();
            if (filterCategory!=='Category...' && filterCategory !== rowCategory) {
                $(this).addClass('hide');
            }
            if (filterCut!=='Cut...' && filterCut !== rowCut) {
                $(this).addClass('hide');
            }
        });
        
    });
    
});
