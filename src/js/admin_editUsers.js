$(document).ready(function(){


    // Sort by username
    users.sort(function(a,b){
        if (a.username < b.username) { return -1; }
        else if (a.username > b.username) { return 1; } 
        else { return 0; }
    });


    // Set actions object
    var actions = {
        click: function(data,event) {
            $(event.currentTarget).select();
        },
        delete: function(data,event) {
            // !!!!! "Beautify" the confirm dialog !!!!!
            var confirmed = confirm("Do you really want to delete this user?");
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


    // Create new ViewModel
    var myViewModel = LMD_koREST.newViewModel({
        url: '/LastMileData/php/scripts/LMD_REST.php/users/',
        element: $('#outerDiv')[0],
        idAttribute: 'pk',
        other: {
            actions: actions
        }
    });


    // Bind data to DOM
    myViewModel.reset(users);


    // Add a new user; scroll down
    // !!!!! Define Modle Defaults has to be called with every add (instead of once); refactor LMD_koREST.js accordingly !!!!!
    $('#btn_add').click(function(){

        // Define model default parameters
        // !!!!! Need to be able to infer fields from the server
        myViewModel.defineModelDefauls({
            username: "new user",
            userGroups: "user"
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
    
});
