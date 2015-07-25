// Dependencies: jQuery, jQueryUI, LMD_fileSystemHelpers.js

// If user has not logged into DEQA section, redirect to DEQA home page
if (!sessionStorage.username) {
    window.location.assign('/LastMileData/src/php-html/pages/page_deqa.html');
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
        $(this).after("<img class='chk_print' src='/LastMileData/src/images/chk_print_v20140916.png'>")
    });
    
    // Insert default values for checkboxes
    $('input[type=checkbox]').each(function(){
        if ( $(this).attr('value')===undefined ) {
            $(this).attr('value',1);
        }
    });
    
    // Append #de_date and #de_init boxes (for printing)
    $de_date = $("input[name=meta_DE_date]");
    $de_init = $("input[name=meta_DE_init]");
    $de_date.after("<input id='de_print_date'>");
    $de_init.after("<input id='de_print_init'>");
    
    // Set de_init and de_date; set fields to readonly
    $de_date.val(mysql_date());
    $de_date.attr('readonly','readonly');
    $de_init.val(sessionStorage.username);
    $de_init.attr('readonly','readonly');
    
    // Set timestamps, data source, UUID
    $("#meBox").append("<input name='meta_DE_startTime' class='stored' type='hidden'>");
    $("#meBox").append("<input name='meta_DE_endTime' class='stored' type='hidden'>");
    $("#meBox").append("<input name='meta_dataSource' class='stored' type='hidden' value='paper'>");
    $("#meBox").append("<input name='meta_UUID' class='stored' type='hidden'>");
    $("input[name=meta_DE_startTime]").val(mysql_time());
    $("input[name=meta_UUID]").val(getUUID());
    
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
                        $("input[name=" + key + "]").val(currentRecord[key]);
                    }
                }
            }
            
            // Set qa_date; set to readonly
            $qa_date = $("input[name=qa_date]");
            $qa_date.val(mysql_date());
            $qa_date.attr('readonly','readonly');
            
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
                $('input[name=' + vResult.errorFields[i] + "]").css('background-color','#FFCCCC');
            }
            
            // Scroll to top; show validationBox
            $("body").animate({ scrollTop: 0 }, 500, function() {
                $('#validationBox').slideDown(500);
            });
            
        // If the form DOES pass the validation step, proceed
        } else {
            
            // Set "form end" timestamp
            $("input[name=meta_DE_endTime]").val(mysql_time());
            
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
                        $("body").animate({ scrollTop: 0 }, 500, function(){
                            $("body").fadeOut(500,function(){
                                if(clicked == 'lmd_next') {
                                    // "Next" was clicked; reload page to enter new form; scroll to top
                                    window.location.reload(true);
                                }
                                else {
                                    // "Submit" was clicked; redirect back to DEQA
                                    window.location.assign('/LastMileData/src/php-html/pages/page_deqa.html');
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
            window.location.assign('/LastMileData/src/php-html/pages/page_deqa.html');
        });
    });
    
    
    
});


// Returns MySQL-formatted date
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function mysql_date(inputDate) {
    if (arguments.length === 0) {
        var myDate = new Date();
    } else {
        var myDate = new Date(inputDate);
    }
    return myDate.getUTCFullYear() + "-" + twoDigits(1 + myDate.getUTCMonth()) + "-" + twoDigits(myDate.getUTCDate());
}


// Returns a Universally-unique identifier (UUID)
// !!!!! Refactor into "utility library"
function getUUID() {
    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
        return v.toString(16);
    });
    return uuid;
}

// Returns MySQL-formatted time
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function mysql_time(inputDate) {
    if (arguments.length === 0) {
        var myDate = new Date();
    } else {
        var myDate = new Date(inputDate);
    }
    return myDate.toTimeString().substring(0,8);
}


// Pad numbers to two digits ( helper function for mysql_datetime() )
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}

// Return GET parameter  !!!!! inactive but useful; Refactor into "utility library" !!!!!
//function getParameterByName(name) {
//    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
//    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
//        results = regex.exec(location.search);
//    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
//}

//function logError(e) {
//    // modify to catch errors where access to filesystem is not granted
//    console.log('fhwForms - logError');
//    console.log(e);
//}

//function addRecordToObject() {
//    JSON.stringify(myRecord);
//}