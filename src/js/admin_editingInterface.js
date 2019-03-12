$(document).ready(function(){
    
    // This works in tandem with LMD_REST.php. The switch variable corresponds to the LMD_REST route.


    // !!!!! BUG: There is an error if you add a markdown page, submit it, and then try to edit it and submit it again !!!!!
    // !!!!! BUG: Adding and then deleting a user (without refreshing) causes a bug !!!!!

    // Set variables here that are specific to each page ("edit indicators", "edit markdown", etc.)
    //      The "sw" variable is passed to admin_editingInterface.php as a GET parameter
    //      The "ei" object is a container for configuration variables used on this page (ei = editing interface)
    //      Fields to inlcude should be specified in modelHeaders and modelDefaults
    //      ei.sortVars is an array of up to three fields; eDate is sorted by these three fields
    //      ei.filters is an array of objects that contain info on fields to use to filter the table (!!!!! can only handle one entry right now !!!!!)
    switch (sw) {
        
        case 'indicators':
            var ei = {
                stringH2: 'Edit indicators',
                stringAdd: 'Add a new indicator',
                sortVars: ['ind_category','ind_name'],
                filters: [{
                    header: 'Category...',
                    field: 'ind_category'
                }],
                idAttribute: 'ind_id',
                modelHeaders: ['Category','Indicator','Definition','Source','Format'],
                modelDefaults: {
                    ind_category: '',
                    ind_name: 'New indicator',
                    ind_definition: '',
                    ind_source: '',
                    ind_format: 'integer'
                },
                dropdowns: {
                    ind_format: ['integer','decimal-1','decimal-2','percent','percent-1','percent-2','dollars']
                }
            };
            break;
        
        case 'markdown':
            var ei = {
                stringH2: 'Edit markdown',
                stringAdd: 'Add a new markdown file',
                sortVars: ['md_name'],
                filters: [],
                idAttribute: 'id',
                dupeField: 'md_name',
                modelHeaders: ['Name','Markdown text'],
                modelDefaults: {
                    md_name: "Enter a unique name here",
                    md_text: "Copy and paste markdown text here"
                }
            };
            break;
        
        case 'users':
            var ei = {
                stringH2: 'Edit LMD users',
                stringAdd: 'Add a new user',
                sortVars: ['username'],
                filters: [],
                idAttribute: 'id',
                dupeField: 'username',
                modelHeaders: ['Username','Groups','First_name', 'Last_name', 'Initials', 'Team', 'Email', 'Phone', 'Position', 'Org', 'Location' ],
                
                // These column names need to align with the LMD_get column names in Route 5 LMD users (lastmile_dataportal.tbl_utility_users) in LMD_REST.php
                modelDefaults: {
                    username: "new user",
                    user_groups: "user",
                    first_name: "",
                    last_name: "", 
                    initials: "", 
                    team: "",
                    email: "", 
                    phone: "",
                    position: "",
                    organization: "Last Mile Health", 
                    location: ""
                }
            };
            break;
        
        case 'narratives':
            var ei = {
                stringH2: 'Edit Data Portal narratives',
                hideAddButton: true,
                hideDeleteButtons: true,
                sortVars: ['report_name','display_order'],
                filters: [{
                    header: 'Report...',
                    field: 'report_name'
                }],
                idAttribute: 'id',
                readOnlyFields: ['report_name','display_order','ro_name'],
                mysqlIgnore: ['report_name','ro_name'],
                modelHeaders: ['Report name','#','Indicator name','Narrative'],
                modelDefaults: {
                    report_name: "",
                    display_order: "",
                    ro_name: "",
                    ro_narrative: ""
                }
            };
            break;
        
        default:
            // code
            break;
    }
    

    // Sort eData by variables in ei.sortVars array, defined above
    // !!!!! Refactor this code into LMD_utilities and turn into a generalized sorting function !!!!!
    eData.sort(function(a,b) {
        // Sort 1
        if (ei.sortVars[0] !== undefined) {
            if (
                LMD_utilities.isNumeric(a[ei.sortVars[0]]) && Number(a[ei.sortVars[0]]) < Number(b[ei.sortVars[0]]) || 
                !LMD_utilities.isNumeric(a[ei.sortVars[0]]) && a[ei.sortVars[0]] < b[ei.sortVars[0]]
            ) { return -1; }
            else if (
                LMD_utilities.isNumeric(a[ei.sortVars[0]]) && Number(a[ei.sortVars[0]]) > Number(b[ei.sortVars[0]]) || 
                !LMD_utilities.isNumeric(a[ei.sortVars[0]]) && a[ei.sortVars[0]] > b[ei.sortVars[0]]
            ) { return 1; } 
            else {
                // Sort 2
                if (ei.sortVars[1] !== undefined) {
                    if (
                        LMD_utilities.isNumeric(a[ei.sortVars[1]]) && Number(a[ei.sortVars[1]]) < Number(b[ei.sortVars[1]]) || 
                        !LMD_utilities.isNumeric(a[ei.sortVars[1]]) && a[ei.sortVars[1]] < b[ei.sortVars[1]]
                    ) { return -1; }
                    else if (
                        LMD_utilities.isNumeric(a[ei.sortVars[1]]) && Number(a[ei.sortVars[1]]) > Number(b[ei.sortVars[1]]) || 
                        !LMD_utilities.isNumeric(a[ei.sortVars[1]]) && a[ei.sortVars[1]] > b[ei.sortVars[1]]
                    ) { return 1; } 
                    else {
                        // Sort 3
                        if (ei.sortVars[2] !== undefined) {
                            if (
                                LMD_utilities.isNumeric(a[ei.sortVars[2]]) && Number(a[ei.sortVars[2]]) < Number(b[ei.sortVars[1]]) || 
                                !LMD_utilities.isNumeric(a[ei.sortVars[2]]) && a[ei.sortVars[2]] < b[ei.sortVars[2]]
                            ) { return -1; }
                            else if (
                                LMD_utilities.isNumeric(a[ei.sortVars[2]]) && Number(a[ei.sortVars[2]]) > Number(b[ei.sortVars[1]]) || 
                                !LMD_utilities.isNumeric(a[ei.sortVars[2]]) && a[ei.sortVars[2]] > b[ei.sortVars[2]]
                            ) { return 1; }
                            else { return 0; }
                        }
                    }
                }
            }
        }
    });


    // Set actions object
    var actions = {
        // Highlight the text (if the input/textarea is not read-only)
        click: function(data,event) {
            if (!$(event.currentTarget)[0].readOnly===true) {
                $(event.currentTarget).select();
            }
        },
        delete: function(data,event) {
            // Delete the record
            var confirmed = confirm("Do you really want to archive this record?");
            if (confirmed) {
                myViewModel.delete(data._cid);
                DataPortal_GLOBALS.anyChanges = true;
                $submit.prop('disabled','');
                DT.row( $(event.target).closest('tr') ).remove().draw();
            }
        },
        change: function(data,event) {
            // Mark the record as changed
            myViewModel.markAsChanged(data._cid);
            DataPortal_GLOBALS.anyChanges = true;
            $submit.prop('disabled','');
        },
        blur: function(data,event) {
            // Check for duplicate entires (of ei.dupeField)
            if (ei.dupeField) {
                // Reset dupe counter
                var dupes = -1;
                // Check for dupes
                for(var x in myViewModel.vm()) {
                    if (data[ei.dupeField]() === myViewModel.vm()[x][ei.dupeField]()) {
                        dupes++;
                    }
                }
                // If dupes are found, display visual alert and redirect the user to the field that must be unique
                if (dupes > 0) {
                    if (!DataPortal_GLOBALS.blurFiredRecently) {
                        alert("Value must be unique.");
                        $(event.currentTarget).parent().parent().find('[data-bind]').each(function(){
                            if ( $(this)[0].outerHTML.indexOf('value:' + ei.dupeField) >- 1 ) {
                                $(this).select();
                            }
                        });
                        
                        // Prevents repeated triggering of alert
                        DataPortal_GLOBALS.blurFiredRecently = true;
                        setTimeout(function(){
                            DataPortal_GLOBALS.blurFiredRecently = false;
                        }, 5);
                    }
                }
            }
        }
    };


    // Generate "selectLists" object
    //  !!!!! expand this code to be able to handle multiple filter fields (as in admin_editData.js) !!!!!
    if (ei.filters[0] !== undefined) {
        var selectLists = {
            category: [ei.filters[0].header]
        };
        for (var key in eData) {
            var category = eData[key][ei.filters[0].field];
            if (selectLists.category.indexOf(category)===-1) {
                selectLists.category.push(category);
            }
        }
    }


    // Create new ViewModel
    var myViewModel = LMD_koREST.newViewModel({
        url: '/LastMileData/php/scripts/LMD_REST.php/' + sw + '/',
        element: $('#outerDiv')[0],
        idAttribute: ei.idAttribute,
        mysqlIgnore: ei.mysqlIgnore || [],
        other: {
            actions: actions,
            selectLists: selectLists || false,
            tableHeaders: ei.modelHeaders,
            tableFields: Object.keys(ei.modelDefaults),
            stringH2: ei.stringH2,
            stringAdd: ei.stringAdd
        }
    });


    // Dynamically populate "template" table cell; all table cells is later populated dynamically via Knockout
    var eiFields = Object.keys(ei.modelDefaults).reverse();
    for (var key in eiFields) {
        // Add 'filterCategory' class
        if (ei.filters[0] && eiFields[key] === ei.filters[0].field) {
            var addClass = ' filterCategory';
        } else {
            var addClass = '';
        }
    }
    if (sw === 'markdown') {
        // Populate inputs (for markdown)
        // This requires special code because it leverages a textarea, which can handle special characters (accept-charset='ISO-8859-1') and corresponds with a MySQL LONGTEXT field
        $('.eiTR').prepend("<td><textarea accept-charset='ISO-8859-1' class='admin_input pad filterCut' data-bind='value: md_text, attr:{\"data-field\":\"md_text\"}, event: {change:$root.other.actions.change}'></textarea></td>");
        $('.eiTR').prepend("<td><span style=display:none data-bind='text:" + eiFields[key] + "'></span><input class='admin_input pad' data-bind='value: md_name, attr:{\"data-field\":\"md_text\"}, event: {change:$root.other.actions.change, blur:$root.other.actions.blur}'></td>");
    } else {
        
        // Populate inputs (for everything except markdown)
        for (var key in eiFields) {
            
            // Handle text fields
            if (ei.dropdowns === undefined || ei.dropdowns[eiFields[key]] === undefined) {
                $('.eiTR').prepend("<td><span style=display:none data-bind='text:" + eiFields[key] + "'></span><input class='admin_input pad" + addClass + "' data-bind='value:" + eiFields[key] + ", attr:{\"data-field\":\"" + eiFields[key] + "\"}, event: {click:$root.other.actions.click, change:$root.other.actions.change, blur:$root.other.actions.blur}'></td>");
                
            // Handle dropdowns
            } else {
                var htmlString = "<td><span style=display:none data-bind='text:" + eiFields[key] + "'></span><select class='admin_input pad" + addClass + "' data-bind='value:" + eiFields[key] + ", attr:{\"data-field\":\"" + eiFields[key] + "\"}, event: {click:$root.other.actions.click, change:$root.other.actions.change, blur:$root.other.actions.blur}'>";
                for (var key2 in ei.dropdowns[eiFields[key]]) {
                    htmlString += "<option>" + ei.dropdowns[eiFields[key]][key2] + "</option>";
                }
                htmlString += "</select></td>";
                $('.eiTR').prepend(htmlString);
            }
            
        }
    }


    // Bind data to DOM
    myViewModel.reset(eData);


    // Hide add button
    if (ei.hideAddButton) {
        $('#btn_add').addClass('hide');
    }


    // Hide delete buttons
    if (ei.hideDeleteButtons) {
        $('#deleteTH').addClass('hide');
        $('.btn_remove').each(function(){
            $(this).parent().addClass('hide');
        });
    }


    // Set readonly fields
    if (ei.readOnlyFields && ei.readOnlyFields.length > 0) {
        for (var i=0; i<ei.readOnlyFields.length; i++) {
            $("input[data-field='" + ei.readOnlyFields[i] + "'").prop('readonly',true);
        }
    }


    // Add a new indicator
    // !!!!! DefineModelDefaults has to be called with every add (instead of once); refactor LMD_koREST.js accordingly !!!!!
    $('#btn_add').click(function() {

        // Define model default parameters
        myViewModel.defineModelDefauls(ei.modelDefaults);

        // Add new record to ViewModel
        myViewModel.addNew();
        DataPortal_GLOBALS.anyChanges = true;
        $submit.prop('disabled','');
        
        // Scroll to top of table
        $('.dataTables_scrollBody').animate({ scrollTop: 0 }, 500);
        
    });


    // Save changes to server
    $('#btn_submit').click(function() {
        
        // Check that all required fields are filled
        // !!!!! Add this functionality; use dupeField as a model !!!!!
        
        // Manipulate DOM
        LMD_utilities.ajaxButton($submit, 'ajaxLoader');

        myViewModel.sync({
            successCallback: function() {
                // Reset anyChanges flag; manipulate DOM
                DataPortal_GLOBALS.anyChanges = false;
                LMD_utilities.ajaxButton($submit, 'alertSuccess', 'Submit changes');
            },
            errorCallback: function() {
                // Error message; reset DOM
                alert('Error. Could not reach the database. Please try again.');
                LMD_utilities.ajaxButton($submit, 'alertError', 'Submit changes');
                LMD_utilities.ajaxButton($submit, 'enable');
            }
        });
    });


    // Disable "submit" button by default; reset anyChanges flag
    var $submit = $('#btn_submit');
    LMD_utilities.ajaxButton($submit, 'disable');
    DataPortal_GLOBALS.anyChanges = false;


    // Change handler: filter table based on "category"
    $('.dataFilter').change(function() {

        var filterCategory = $('#filter_category').val();

        $('.filterRow').each(function() {
            $(this).removeClass('hide');
            var rowCategory = $(this).find('.filterCategory').val();
            if (filterCategory!==ei.filters[0].header && filterCategory !== rowCategory) {
                $(this).addClass('hide');
            }
        });
    });
    
    // Apply DataTable function
    var DT = $('.table').DataTable({
        scrollY: '50vh',
//        scrollX: '100%',
        scrollCollapse: true,
        paging: false,
        dom: '<"top">rt<"bottom"flp><"clear">',
        initComplete: function() {
            $("#table_tools").detach().prependTo('div.bottom');
            $("#table_editing_filter").detach().appendTo('#table_tools');
            $("#table_editing_filter").css('font-size','80%');
        }
//        order: [[ 0, 'asc' ], [ 1, 'asc' ]]
    });
    
    // Compensate for a formatting bug
    $(window).on('DP_up',function(){
        DT.draw();
    });
    
//    !!!!! This is currently broken !!!!!
//    LMD_utilities.makeTableColumnsResizable();

});


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
