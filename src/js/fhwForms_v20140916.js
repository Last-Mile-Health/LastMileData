
// If user has not logged into DEQA section, redirect to DEQA home page
if (!sessionStorage.username) {
    window.location.assign('/LastMileData/src/pages/page_deqa.html');
}

$(document).ready(function() {
    
    // Insert checkbox image after each (for print media)
    $(":checkbox").each(function() {
        $(this).after("<img class='chk_print' src='/LastMileData/res/chk_print_v20140916.png'>")
    });
    
    // Append #de_date and #de_init boxes (for printing)
    $("#de_date").after("<input id='de_print_date'>");
    $("#de_init").after("<input id='de_print_init'>");
    
    // Set de_init and de_date; set fields to readonly
    $('#de_date').val(mysql_date());
    $('#de_date').attr('readonly','readonly');
    $('#de_init').val(sessionStorage.username);
    $('#de_init').attr('readonly','readonly');
    
    // Apply jQueryUI datepicker (MySQL date format)
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
    });
    
    // Only allow valid MySQL date format if user types into .datepicker input
    // !!!!! Functionize this code; also used in deqa.js !!!!!
    $(".datepicker").blur(function() {
        myDate = $(this).val();
        dateRegExp = /[12]\d\d\d-[0-1]\d-[0-3]\d/;
        if ( !dateRegExp.test(myDate) && myDate!="" ) {
            mySel = $(this);
            mySel.val( "" );
            mySel.attr( "title", "Dates must be in yyyy-mm-dd format." );
            mySel.tooltip( "show" );
            setTimeout(function(){
                mySel.tooltip( "destroy" );
            }, 2000);
            mySel.focus();
        }
    });
    
    
    // Apply jQueryUI autocomplete (MySQL date format)
    $(".autocomplete").each(function() {
        
        // Get source from data attribute
        mySource = $(this).attr('data-lmd-valid-autoC');
        
        if (mySource.substring(0,1)=="[") {
            // Parse "static" (inline) arrays
            myList = JSON.parse(mySource);
        }
        else {
            // Parse "dynamic" (localStorage) arrays
            myList = JSON.parse(localStorage[mySource]);
        }
        if ($(this).attr('data-lmd-valid-sortList')=='yes') {
            // Sort list alphabetically
            myList.sort();
        }
        
        // Apply jQueryUI autocomplete
        $(this).autocomplete({
            source: myList,
            autoFocus: true,
            delay: 100
        });
    });
    
    // Apply dynamic select lists
    $("input[type!='checkbox']").each(function() {
        
        if($(this).attr('data-lmd-valid-select')) {
            
            // Get source from data attribute
            mySource = $(this).attr('data-lmd-valid-select');
            
            if (mySource.substring(0,1)=="[") {
                // Parse "static" (inline) arrays
                myList = JSON.parse(mySource);
            }
            else {
                // Parse "dynamic" (localStorage) arrays
                myList = JSON.parse(localStorage[mySource]);
            }
            if ($(this).attr('data-lmd-valid-sortList')=='yes') {
                // Sort list alphabetically
                myList.sort();
            }
            
            // Create select element
            myNewInput = "<select class='dynamicSelect " + $(this).attr('class') + "' id='" + $(this).attr('id') + "' style='" + $(this).attr('style') + "'>";
            myNewInput += "<option value=''></option>";
            for (i=0; i<myList.length; i++) {
                myNewInput += "<option value='" + myList[i] + "'>" + myList[i] + "</option>";
            }
            myNewInput += "</select>";
            
            // Insert select element after textbox; remove textbox
            $(this).after(myNewInput);
            $(this).remove();
        }
        
    });
    
    // If using "QA mode" (i.e. GET parameter with key 'QA' is not undefined), populate field values
    if ( getParameterByName('QA') ) {
        
        // Set array of currentRecord properties that are not stored
        var notStored = ['table', 'type'];
        
        // Use FileSystem API; request persistent storage
        window.webkitStorageInfo.requestQuota(PERSISTENT, 50*1024*1024, function(grantedBytes) {
            window.requestFileSystem = window.requestFileSystem || window.webkitRequestFileSystem;
            window.requestFileSystem(PERSISTENT, grantedBytes,
                // Success handler
                function(fs) {
                    // Read in file
                    fs.root.getFile('data.lmd', {}, function(fileEntry) {
                        // Get a File object representing the file, then use FileReader to read its contents.
                        fileEntry.file(function(file) {
                            var reader = new FileReader();
                            reader.onloadend = function(e) {
                                
                                // Read in myRecordset
                                myRecordset = JSON.parse(this.result);
                                
                                // Assign record object to currentRecord
                                currentRecord = JSON.parse(myRecordset[getParameterByName('QA')]);
                                
                                // Populate fields from key/value pairs
                                for(var key in currentRecord) {
                                    
                                    // if key isn't in "notStored"array, add it to query string
                                    if ( notStored.indexOf(key) == -1) {
                                        
                                        // Set checkboxes
                                        if ( $('#' + key).prop('type') == 'checkbox' ) {
                                            
                                            if (currentRecord[key]) {
                                                $('#' + key).prop('checked', true);
                                            }
                                        }
                                        
                                        // Set text inputs
                                        else {
                                            $('#' + key).val(currentRecord[key]);
                                        }
                                    }
                                }
                                
                                // Set qa_date; set field to readonly
                                $('#qa_date').val(mysql_date());
                                $('#qa_date').attr('readonly','readonly');
                                
                            };
                            reader.readAsText(file);
                        }, logError);
                    }, logError);
                }, logError);
        });
        
    }
    
    
    
    // Submit form; store data to LocalStorage object
    $('#lmd_submit').click(function() {
        
        // Reset errorMessages and errorFields; set disallowed characters RegExp
        var errorMessages = [];
        var errorFields = [];
        var disallowed = new RegExp(/[`~#\$%\^&\*\+;\\\|<>]+/);
        
        // Reset field background colors
        $('.stored[type!="checkbox"]').each(function() {
            $(this).css('background-color','');
        });
        
        // Perform data validation (for each field with class='stored')
        $('.stored').each(function() {
            
            // Get key/value pair
            myField = $(this).attr('id');
            myValue = $(this).val();
            
            // Test: disallowed characters: # & + ; ^ * |
            if ( (disallowed.test(myValue))===true ) {
                errorFields.push(myField);
                errorMessages.push('Field "' + myField + '" cannot contain the following characters: `~#$%^&*+;\|<>');
            }
            
            // Test: field is required (data-lmd-valid-required="yes")
            if ( $(this).attr('data-lmd-valid-required')=="yes" && myValue=="" ) {
                errorFields.push(myField);
                errorMessages.push('Field "' + myField + '" is required.');
            }
            
            // Test: is a (decimal) number
            if ( $(this).hasClass('decimal') && isNaN(myValue) ) {
                errorFields.push(myField);
                errorMessages.push('Field "' + myField + '" must be a number');
            }
            
            // Test: is an integer
            if ( $(this).hasClass('integer') && ( isNaN(myValue) || myValue!=Math.floor(myValue) ) ) {
                errorFields.push(myField);
                errorMessages.push('Field "' + myField + '" must be an integer');
            }
            
            // Test: is a value-restricted (decimal) number
            myMin = Number($(this).attr('data-lmd-valid-decMin'));
            myMax = Number($(this).attr('data-lmd-valid-decMax'));
            if ( myMin && myValue!="" ) {
                
                if ( myMax && ( myValue<myMin || myValue>myMax || isNaN(myValue) ) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be a number between ' + myMin + ' and ' + myMax);
                }
                else if ( myValue<myMin || isNaN(myValue) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be a number greater than or equal to ' + myMin);
                }
            }
            else if ( myMax && myValue!="" ) {
                if ( myValue>myMax || isNaN(myValue) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be a number less than or equal to ' + myMax);
                }
            }
            
            // Test: is a value-restricted integer
            myMin = Number($(this).attr('data-lmd-valid-intMin'));
            myMax = Number($(this).attr('data-lmd-valid-intMax'));
            if ( myMin && myValue!="" ) {
                if ( myMax && ( myValue<myMin || myValue>myMax || isNaN(myValue) || myValue!=Math.floor(myValue) ) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be an integer between ' + myMin + ' and ' + myMax);
                }
                else if ( myValue<myMin || isNaN(myValue) || myValue!=Math.floor(myValue) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be an integer greater than or equal to ' + myMin);
                }
            }
            else if ( myMax && myValue!="" ) {
                if ( myValue>myMax || isNaN(myValue) || myValue!=Math.floor(myValue) ) {
                    errorFields.push(myField);
                    errorMessages.push('Field "' + myField + '" must be an integer less than or equal to ' + myMax);
                }
            }
            
            // Test third condition !!!!! build character limit here !!!!!
            if (1==0) {
                errorFields.push(myField);
                errorMessages.push('Field "' + myField + '" EM');
            }
            
        });
        
        // If the form DOES NOT passes the validation step, display error messages
        if (errorMessages.length > 0) {
            
            // Clear validationBox
            $('#validationBox').html('');
            
            // Write error messages to validationBox div
            for(i=0;i<errorMessages.length;i++) {
                $('#validationBox').append('&bull;&nbsp;' + errorMessages[i] + '<br>');
            }
            
            // Highlight invalid fields in red
            for(i=0;i<errorFields.length;i++) {
                $('#' + errorFields[i]).css('background-color','#FFCCCC');
            }
            
            // Scroll to top; show validationBox
            $("html, body").animate({ scrollTop: 0 }, "slow", function() {
                $('#validationBox').slideDown(1000);
            });
        }
        
        // If the form DOES pass the validation step, proceed
        else {
            
            // If counter doesn't exist, set it
            if (localStorage.counter === undefined) {
                localStorage.counter = 1;
            }
            
            // Create record object; set localKey (localKey is the unique identifier for each record)
            var myRecord = {};
            localKey = localStorage.counter;
            
            // Increment counter
            localStorage.counter++;
            
            // Set record "type"
            myRecord.type = 'form';
            
            // Add key/value pair to myRecord (for each field with class='stored')
            // For each control (with class='stored'), add property/value pair to myRecord
            $('.stored').each(function() {
                if ($(this).attr('type') == 'checkbox') {
                    
                    // Handle checkboxes
                    if ($(this).is(':checked')) {
                        myRecord[$(this).attr('id')] = 1;
                    }
                    else {
                        myRecord[$(this).attr('id')] = 0;
                    }
                }
                else {
                    // Handle textboxes
                    myRecord[$(this).attr('id')] = $(this).val();
                }
            });
            
            // Use FileSystem API; request persistent storage
            window.webkitStorageInfo.requestQuota(PERSISTENT, 50*1024*1024, function(grantedBytes) {
                window.requestFileSystem = window.requestFileSystem || window.webkitRequestFileSystem;
                window.requestFileSystem(PERSISTENT, grantedBytes,
                    // Success handler
                    function(fs) {
                        // Read in file
                        fs.root.getFile('data.lmd', {create:true}, function(fileEntry) {
                            // Get a File object representing the file, then use FileReader to read its contents.
                            fileEntry.file(function(file) {
                                var reader = new FileReader();
                                reader.onloadend = function(e) {
                                    if (this.result == "") {
                                        // If myRecordset is empty, create an empty object
                                        myRecordset = {};
                                    }
                                    else {
                                        // Otherwise, parse myRecordset into object
                                        myRecordset = JSON.parse(this.result);
                                    }
                                    
                                    // If in QA mode, delete current record
                                    if ( getParameterByName('QA') ) {
                                        delete myRecordset[getParameterByName('QA')];
                                    }

                                    // Add myRecord to myRecordset
                                    myRecordset[localKey] = JSON.stringify(myRecord);
                                    
                                    // Creates (or overwrite) the "data.lmd" file with stringified myRecordset object
                                    fs.root.getFile('data.lmd', {}, function(fileEntry) {
                                        // Create a FileWriter object for our FileEntry (data.lmd)
                                        fileEntry.createWriter(function(fileWriter) {
                                            fileWriter.onwriteend = function(e) {
                                                // When file writing is complete, redirect back to DEQA
                                                window.location.assign('/LastMileData/src/pages/page_deqa.html');
                                            };
                                            fileWriter.onerror = logError;
                                            // Create a new Blob and write it to data.lmd
                                            var blob = new Blob([JSON.stringify(myRecordset)], {type: 'text/plain'});
                                            fileWriter.write(blob);
                                        }, logError);
                                    }, logError);
                                };
                                reader.readAsText(file);
                            }, logError);
                        }, logError);
                    }, logError);
            });
            
        }
        
    });
    
    
    
    // Cancel form submission
    $('#lmd_cancel').click(function() {
        // Redirect back to main page
        window.location.assign('/LastMileData/src/pages/page_deqa.html');
    });
    
    
    
});

// Return MySQL-formatted "DATETIME" string of current date/time
function mysql_date() {
    var now = new Date();
    return ( now.getUTCFullYear() + "-" + twoDigits(1 + now.getUTCMonth()) + "-" + twoDigits(now.getUTCDate()) );
}

// Pad numbers to two digits ( helper function for mysql_datetime() )
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}

// Return GET parameter
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function logError(e) {
    // modify to catch errors where access to filesystem is not granted
    console.log('fhwForms - logError');
    console.log(e);
}

function addRecordToObject() {
    JSON.stringify(myRecord);
}