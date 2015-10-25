$(document).ready(function(){
    
    
    // !!!!! There is an error if you add a markdown page, submit it, and then try to edit it and submit it again !!!!!


    // Set actions object
    var actions = {
        delete: function(data,event) {
            // !!!!! "Beautify" the confirm dialog !!!!!
            var confirmed = confirm("Do you really want to delete this markdown file?");
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
        },
        blur: function(data,event) {
            var dupes = -1;
            for(var x in myViewModel.vm()) {
                if (data.mdName() === myViewModel.vm()[x].mdName()) {
                    dupes++;
                }
            }
            if (dupes > 0) {
                alert("Name must be unique.");
               $(event.currentTarget).select();
            }
        }
    };


    // Create new ViewModel
    var myViewModel = LMD_koREST.newViewModel({
        url: '/LastMileData/php/scripts/LMD_REST.php/markdown/',
        element: $('#outerDiv')[0],
        idAttribute: 'mdName',
        other: {
            actions: actions
        }
    });


    // Bind data to DOM
    myViewModel.reset(markdownList);


    // Add a new indicator; scroll down
    // !!!!! Define Modle Defaults has to be called with every add (instead of once); refactor LMD_koREST.js accordingly !!!!!
    $('#btn_add').click(function(){

        DataPortal_GLOBALS.anyChanges = true;
        
        // Define model default parameters
        // !!!!! Need to be able to infer fields from the server
        myViewModel.defineModelDefauls({
            mdName: "Enter a unique name here",
            mdText: "Copy and paste markdown text here"
        });

        myViewModel.addNew();
        $submit.prop('disabled','');
        
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


});
