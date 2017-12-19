// Dependencies: jQuery, jQueryUI, LMD_fileSystemHelpers.js

// If user has not logged into DEQA section, redirect to DEQA home page
if (!sessionStorage.username) {
    window.location.assign('../pages/page_deqa.html');
}

// Check to see if the user is in "QA mode"
if(localStorage.qaRecordID) {
    qaRecordID = localStorage.qaRecordID;
    delete localStorage.qaRecordID;
} else {
    qaRecordID = 0;
}

$(document).ready(function() {
    
    // Datepicker; enforce MySQl date format
    $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
    $(".datepicker").blur(datepickerBlur);
    
    // Insert checkbox image after each (for print media)
    $(":checkbox").each(function() {
        $(this).after("<img class='chk_print' src='../images/chk_print.png'>")
    });
    
    // Insert default values for checkboxes
    $('input[type=checkbox]').each(function(){
        if ( $(this).attr('value')===undefined ) {
            $(this).attr('value',1);
        }
    });
    
    // Append #de_date and #de_init boxes (for printing)
    $de_date = $("input[name=meta_de_date]");
    $de_init = $("input[name=meta_de_init]");
    $de_date.after("<input id='de_print_date'>");
    $de_init.after("<input id='de_print_init'>");
    
    // Set de_init and de_date; set fields to readonly
    $de_date.val(LMD_utilities.mysql_date());
    $de_date.attr('readonly','readonly');
    $de_init.val(sessionStorage.username);
    $de_init.attr('readonly','readonly');
    $qa_init = $("input[name=qa_init]");
    $qa_date = $("input[name=qa_date]");
    $qa_date.attr('readonly','readonly');
    $qa_init.attr('readonly','readonly');
    
    // Set timestamps, data source, UUID
    $("#meBox").append("<input name='meta_de_time_start' class='stored' type='hidden'>");
    $("#meBox").append("<input name='meta_de_time_end' class='stored' type='hidden'>");
    $("#meBox").append("<input name='meta_data_source' class='stored' type='hidden' value='paper'>");
    $("#meBox").append("<input name='meta_uuid' class='stored' type='hidden'>");
    $("input[name=meta_de_time_start]").val(LMD_utilities.mysql_time());
    $("input[name=meta_uuid]").val(LMD_utilities.getUUID());
    
    // If in "QA mode", populate field values
    if (qaRecordID) {
        
        // Hide "Next form" button
        $('#lmd_next').hide();
        
        // Set array of currentRecord properties that are not stored
        var notStored = ['table', 'database'];
        
        // Read in file and run callback
        LMD_fileSystemHelper.readAndUseFile('data.lmd', function(result){
            
            // Read in myRecordset
            myRecordset = JSON.parse(result);
            
            // Assign record object to currentRecord
            currentRecord = JSON.parse(myRecordset[qaRecordID]);
            
            // Populate fields from key/value pairs
            for(var key in currentRecord) {
                // if key isn't in "notStored"array, add it to query string
                if ( notStored.indexOf(key) == -1) {
                    // Set checkboxes
                    if ( $("input[name=" + key + "]").prop('type') == 'checkbox' ) {
                        $("input[name=" + key + "][value='" + currentRecord[key] + "']").prop('checked', true);
                    // Set text inputs
                    } else {
                        $("input[name=" + key + "],select[name=" + key + "]").val(currentRecord[key]);
                    }
                }
            }
            
            // Set qa_init, qa_date; set de_init to readonly
            $qa_init.val(sessionStorage.username);
            $qa_date.val(LMD_utilities.mysql_date());
            $de_init.attr('readonly','readonly');
            
        });
        
    }
    
    
    
    // Submit form; store data to LocalStorage object
    $("#lmd_next, #lmd_submit").click(function() {
        
        var clicked = $(this).attr('id');
        
        // Reset field background colors
        $('.stored[type!="checkbox"]').each(function() {
            $(this).css('background-color','');
        });
        
        // Perform data validation (for each field with class='stored')
        var vResult = LMD_formValidate.validate($('.stored'));
        
        // If the form DOES NOT pass all validation steps, display error messages
        if (vResult.result === "fail") {
            
            // Clear validationBox
            $('#validationBox').html('');
            
            // Write error messages to validationBox div
            for(var i=0; i<vResult.errorMessages.length; i++) {
                $('#validationBox').append('&bull;&nbsp;' + vResult.errorMessages[i] + '<br>');
            }
            
            // Highlight invalid fields in red
            for(var i=0; i<vResult.errorFields.length; i++) {
                $('input[name=' + vResult.errorFields[i] + '],select[name=' + vResult.errorFields[i] + ']').css('background-color','#FFCCCC');
            }
            
            // Scroll to top; show validationBox
            $("html,body").animate({ scrollTop: 0 }, 500, function() {
                $('#validationBox').slideDown(500);
            });
            
        // If the form DOES pass the validation step, proceed
        } else {
            
            // Set "form end" timestamp
            $("input[name=meta_de_time_end]").val(LMD_utilities.mysql_time());
            
            // Initialize nextKey
            var nextKey = 0;

            // Read file into myRecordset, run callback
            LMD_fileSystemHelper.readFileIntoObject('data.lmd', function(myRecordset){

                // Find highest key in myRecordset; assign to nextKey
                for (var key in myRecordset) { // !!!!! test for no data.lmd file ever created !!!!!
                    if (Number(key) > nextKey) { nextKey = Number(key); }
                }
                nextKey++;

                // Create record object; add key/value pairs
                var myRecord = readFieldsIntoObject('.stored');

                // Read file into myRecordset, run callback
                LMD_fileSystemHelper.readFileIntoObject('data.lmd', function(myRecordset){

                    // If in QA mode, delete current record
                    if (qaRecordID) {
                        delete myRecordset[qaRecordID];
                    }

                    // Add myRecord to myRecordset
                    myRecordset[nextKey] = JSON.stringify(myRecord);

                    // Creates (or overwrite) the "data.lmd" file with stringified myRecordset object
                    LMD_fileSystemHelper.createOrOverwriteFile('data.lmd', JSON.stringify(myRecordset),function(){
                        $("html,body").animate({ scrollTop: 0 }, 500, function(){
                            $("body").fadeOut(500,function(){
                                if(clicked == 'lmd_next') {
                                    // "Next" was clicked; reload page to enter new form; scroll to top
                                    window.location.reload(true);
                                }
                                else {
                                    // "Submit" was clicked; redirect back to DEQA
                                    window.location.assign('../pages/page_deqa.html');
                                }
                            })
                        });
                    });
                });
            });
        }
    });
    
    
    // Cancel form submission
    $('#lmd_cancel').click(function() {
        $("body").fadeOut(500,function(){
            // Redirect back to main page
            window.location.assign('../pages/page_deqa.html');
        });
    });
    
});
