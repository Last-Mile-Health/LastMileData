$(document).ready(function(){
    
    
    
    // (1) Send Records
    $('#sendRecords').click(function(){
        
        // Reset values
        var queryString = "";
        var numRecords = 0;
        var numAjax_success = 0;
        var numAjax_fail = 0;
        var ajaxRequests = [];
        var ajaxErrorStatus = ""; // Possible values: "no records sent", "some records sent", or "all records sent"
        
        // Manipulate DOM; show "loading" GIF
        $('#sendRecords,#cancelModal_2').css('display','none');
        $('#sendRecords_text').html("<img src='/LastMileData/res/ajax-loader_v20140916.gif'>");
//        $('#sendRecords,#cancelModal_2').slideUp(750, function(){
//            $('#sendRecords_text').slideUp(400).html("<img src='/LastMileData/res/ajax-loader_v20140916.gif'>").slideDown(400);
//            $('#ajaxContainer').slideDown(800);
//        });
        
        // Use FileSystem API; request persistent storage
        window.webkitStorageInfo.requestQuota(PERSISTENT, 50.03*1024*1024, function(grantedBytes) {
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
                                if (this.result == "" || this.result == "{}") {
                                    noRecordsMessage('one');
                                }
                                else {
                                    // Otherwise, parse myRecordset into object
                                    myRecordset = JSON.parse(this.result);
                                    
                                    // First loop through keys of myRecordset (set numRecords and manipulate DOM)
                                    for (rKey in myRecordset) {
                                        try {
                                            // Assign record object to currentRecord
                                            currentRecord = JSON.parse(myRecordset[rKey]);
                                        }
                                        catch(e) {
                                            currentRecord = 1;  // To avoid JSON.Parse() returning an error if value variable is not valid JSON
                                        }
                                        
                                        // Test to see if current localStorage record is of type "form"
                                        if (currentRecord.type == "form") {
                                            numRecords++;
//                                            $('#ajaxContainer').append('<div class="ajaxBlock">' + numRecords + '</div>');
                                        }
                                    }
                                    
                                    // Second loop through keys of myRecordset (process numRecords)
                                    for (rKey in myRecordset) {
                                        
                                        try {
                                            // Assign record object to currentRecord
                                            currentRecord = JSON.parse(myRecordset[rKey]);
                                        }
                                        catch(e) {
                                            currentRecord = 1;  // To avoid JSON.Parse() returning an error if value variable is not valid JSON
                                        }
                                        
                                        // Test to see if current localStorage record is of type "form"
                                        if (currentRecord.type == "form") {
                                            
                                            // Parse SQL Insert query
                                            queryString = parseRecordIntoSQL(currentRecord);
                                            
                                            // Send record to database via AJAX
                                            var myData = {'queryString': queryString, 'rKey': rKey} ;
                                            
                                            // Create an AJAX request and push to ajaxRequests array
                                            ajaxRequests.push(
                                                $.ajax({
                                                    type: "POST",
                                                    url: "/LastMileData/src/php/ajaxSendQuery.php",
                                                    // !!!!! add a timeout !!!!!
                                                    data: myData,
                                                    dataType: "json",
                                                    success: function(data) {
                                                        
                                                        // !!!!! Update DOM !!!!!
                                                        // !!!!! Fill out the "RYG matrix" code !!!!!
                                                        // !!!!! Test further using a simulated slow network connection !!!!!
                                                        
                                                        // Remove record from myRecordset
                                                        numAjax_success++;
                                                        delete myRecordset[data.rKeyDelete];
                                                        console.log('ajax success!');
                                                        
                                                    },
                                                    error: function(request, status, error) {
                                                        
                                                        // !!!!! Update DOM !!!!!
                                                        
                                                        // Increment AJAX respones counter
                                                        numAjax_fail++;
                                                        console.log('ajax error:');
                                                        console.log(error);
                                                    }
                                                })
                                            );
                                        }
                                        
                                    }
                                    
                                    // Send AJAX requests and apply done function when all are complete
                                    var defer = $.when.apply($, ajaxRequests);
                                    defer.then(function(){
                                        
                                        // The following two lines removes the file data.lmd
                                            fs.root.getFile('data.lmd', {create: false}, function(fileEntry) {
                                                fileEntry.remove(function() {
                                                    
                                                    // Write the "data.lmd" file with stringified myRecordset object (from which inserted records were removed)
                                                    fs.root.getFile('data.lmd', {create:true}, function(fileEntry) {
                                                        // Create a FileWriter object for our FileEntry (data.lmd)
                                                        fileEntry.createWriter(function(fileWriter) {
                                                            fileWriter.onwriteend = function(e) {
                                                                
                                                                if (numRecords == numAjax_success) {
                                                                    // Display success message
                                                                    $('#sendRecords_text').html('Data upload complete.');
                                                                }
                                                                
                                                                else if (numRecords == numAjax_success + numAjax_fail) {
                                                                    // Display "partial error" message
                                                                    $('#sendRecords_text').html('Only some records were sent successfully. Please try again to send the remaining records.');
                                                                }
                                                                
                                                                else if (numRecords == numAjax_fail) {
                                                                    // Display "full error" message
                                                                    $('#sendRecords_text').html('No records were successfully sent. Please try again later.');
                                                                }
                                                                
                                                                else {
                                                                    // Display "full error" message
                                                                    $('#sendRecords_text').html('An unknown error occurred. Please contact the database manager for support');
                                                                }
                                                                
                                                                // Close and reset modal box
                                                                closeAndResetModal();
                                                                
                                                            };
                                                            fileWriter.onerror = logError;
                                                            // Create a new Blob and write it to data.lmd
                                                            var blob = new Blob([JSON.stringify(myRecordset)], {type: 'text/plain'});
                                                            fileWriter.write(blob);
                                                        }, logError);
                                                    }, logError);
                                                    
                                                    // C. if failCounter==0, display success message
                                                    
                                                    // D. Display "no records" message in DOM !!!!! build this out !!!!!
//                                                    $('#sendRecords_text').html('Building this out 2...');
                                                    
                                                    // D. Close modal box
//                                                    closeAndResetModal();
                                                    
                                                }, logError);
                                            }, logError);
                                        
                                    });
                                    
                                }
                            };
                            reader.readAsText(file);
                        }, logError);
                    }, logError);
                }, logError);
        });
        
    });
    

    // (2) QA Buttons !!!!! finish this !!!!!
    
        $('#qa_TST').click(function() {
            targetForm = "/LastMileData/src/forms/0_testDE.html";
            qaFormName = "TEST FORM";
            pKey1_name = "var1";
            pKey2_name = "var6";
            pKey3_name = "";
            pKey1_label = "Var 1";
            pKey2_label = "Var 6";
            pKey3_label = "";
            pKey_date = "pKey2"
            launchQAModal();
        });

        $('#qa_REG').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_reg02_registration.html";
            qaFormName = "FHW: Registration";
            pKey1_name = "memberID_1";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "First Member ID";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_KPI').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_kpi02_kpiassessment.html";
            qaFormName = "FHW: KPI Assessment";
            pKey1_name = "memberID";
            pKey2_name = "visitDate";
            pKey3_name = "";
            pKey1_label = "Woman member ID";
            pKey2_label = "Visit date";
            pKey3_label = "";
            pKey_date = "pKey2"
            launchQAModal();
        });

        $('#qa_REF').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_ref02_referral.html";
            qaFormName = "FHW: Referral";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_BBI').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_bbi02_bigbellyinitial.html";
            qaFormName = "FHW: Big Belly (initial)";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_BBF').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_bbf02_bigbellyfollowup.html";
            qaFormName = "FHW: Big Belly (follow-up)";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_PNI').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_pni02_postnatalinitial.html";
            qaFormName = "FHW: Postnatal (initial)";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_PNF').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_pnf02_postnatalfollowup.html";
            qaFormName = "FHW: Postnatal (follow-up)";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_BDM').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_bdm02_movements.html";
            qaFormName = "FHW: Births, Deaths, Movements";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_SCH').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_sch02_sickchild.html";
            qaFormName = "FHW: Sick Child";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });

        $('#qa_ESC').click(function() {
            targetForm = "/LastMileData/src/forms/fhw_esc03_ebolascreening.html";
            qaFormName = "FHW: Ebola Screening Tool";
            pKey1_name = "";
            pKey2_name = "";
            pKey3_name = "";
            pKey1_label = "";
            pKey2_label = "";
            pKey3_label = "";
            pKey_date = ""
            launchQAModal();
        });
    
    
    
    // (3) QA Modal Submit
    $('#qaModalSubmit').click(function() {

        // Set pKey values
        pKey1_val = $('#pKey1').val();
        pKey2_val = $('#pKey2').val();
        pKey3_val = $('#pKey3').val();
        
        // Initialize "QA Record ID" (i.e. key of myRecordset object where desired record is contained)
        var qaRecordID = false;
        
        // Use FileSystem API; request persistent storage
        window.webkitStorageInfo.requestQuota(PERSISTENT, 50.03*1024*1024, function(grantedBytes) {
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
                                if (this.result == "" || this.result == "{}") {
                                    noMatchFound();
                                }
                                else {
                                    // Otherwise, parse myRecordset into object
                                    myRecordset = JSON.parse(this.result);

                                    // First loop through keys of myRecordset (set numRecords)
                                    for (rKey in myRecordset) {
                                        try {
                                            // Assign record object to currentRecord
                                            currentRecord = JSON.parse(myRecordset[rKey]);
                                        }
                                        catch(e) {
                                            currentRecord = 1;  // To avoid JSON.Parse() returning an error if value variable is not valid JSON
                                        }

                                        // Test to see if current localStorage record is of type "form"
                                        if (currentRecord.type == "form") {
                                            // Test that pKey1 matches
                                            if ( pKey1_val == currentRecord[pKey1_name] ) {

                                                // Test that pKey2 matches (or doesn't exist)
                                                if ( pKey2_val == currentRecord[pKey2_name] || pKey2_name == "" ) {

                                                    // Test that pKey3 matches (or doesn't exist)
                                                    if ( pKey3_val == currentRecord[pKey3_name] || pKey3_name == "" ) {
                                                        // Match found; set qaRecordID
                                                        var qaRecordID = rKey;
                                                    }
                                                }
                                            }
                                        }

                                    }

                                    // Handle: no match found
                                    if (qaRecordID) {
                                        // Deactivate button
                                        $(this).css('pointer-events','none');

                                        // Parse targetForm URL; redirect
                                        targetForm = targetForm + "?QA=" + qaRecordID;
                                        window.location.assign(targetForm);
                                    }
                                    else {
                                        noMatchFound();
                                    }
                                }
                            };
                            reader.readAsText(file);
                        }, logError);
                    }, logError);
                }, logError);
        });
        
    });
    
    
    
    // (4) Download Data File Modal !!!!! finish !!!!!
    // Run this script when refreshDataModal is shown
    $('#downloadDataFileModal').on('shown.bs.modal', function(e) {
        
        // !!!!! open stream
        
        // !!!!! write to filestream (only files of type='form')
        
        // !!!!! download file (send appropriate headers)
        
        // !!!!! When download is complete, run the following...
            // Close Modal and Reset DOM
            setTimeout( function() {

            // Close dialog box
            $('.modal').modal('hide');
            
            // !!!!! delete specific records from data.lmd !!!!!
            
           }, 1000 );
        
    });
    
    
    
    // (5) Refresh Data Modal
    // Run this script when refreshDataModal is shown
    $('#refreshDataModal').on('shown.bs.modal', function(e) {
        ajaxRefresh();
    });
    
    
    
    // (5) Versions
    $('#oldVersions_link').click(function() {
        $('#currentVersions_div').slideUp(1000,function(){
            $('#oldVersions_div').slideDown(1000);
            $('#oldVersions_link').hide();
            $('#currentVersions_link').show();
        });
    });
    
    $('#currentVersions_link').click(function() {
        $('#oldVersions_div').slideUp(1000,function(){
            $('#currentVersions_div').slideDown();
            $('#oldVersions_link').show();
            $('#currentVersions_link').hide();
        });
    });
    
    
    
});



$(window).load(function(){
    
    
    
    // (6) Only allow webkit-based broswers (Safari or Chrome); !!!!! consider using browser.version propery as well (Webkit build number) !!!!!
    var browser = (function() { var s = navigator.userAgent.toLowerCase(); var match = /(webkit)[ \/]([\w.]+)/.exec(s) || /(opera)(?:.*version)?[ \/]([\w.]+)/.exec(s) || /(msie) ([\w.]+)/.exec(s) || !/compatible/.test(s) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(s) || [];return { name: match[1] || "", version: match[2] || "0" }; }());
    if (browser.name != "webkit") {
        // Display error alert; redirect to home page
        alert("The LastMileData.org \"Data Entry / Quality Assurance\" application only works in Google Chrome or Safari.");
        window.location.assign('/LastMileData');
    }
    else {
        // !!!!! Move the initialized code here !!!!!
    }
    
    // (7) If site has been initialized, proceed to DEQA
    if ( localStorage.initialized == "yes" ) {
        
        // If user is not logged in, show login modal; else, proceed to DEQA
        if (!sessionStorage.username) {
            $('#deqaLogin').modal({
                backdrop:'static',
                keyboard:false,
                show:true
            });
        }
    }
    // If site has NOT been initialized
    else {
        // Show initialize modal
        $('#deqaInitialize').modal({
            backdrop:'static',
            keyboard:false,
            show:true
        });
        
        // Run when initialize modal is shown
        $('#deqaInitialize').on('shown.bs.modal', function() {
            ajaxRefresh(); // Note: showLoginModal is run on success callback of ajaxRefresh();
        });
    }
    
    
    
    // (7) DEQA Login
    $('#deqaLoginSubmit').click(function(event) {
        
        // Set usernames and passwords here; usernames should only contain lowercase letters; passwords are SHA1
        var deqaUserArray = JSON.parse(localStorage.deqaUsers);
        
        // Get username and password values from fields
        var username = $('#username').val();
        var password = $('#password').val();
        
        // Check username
        if( deqaUserArray[username] ) {
            
            // Check password
            if( CryptoJS.SHA1(password) == deqaUserArray[username] ) {
                
                // Hide modal form
                $('.modal').modal('hide');
                
                // Set session username variable
                sessionStorage.username = username;
            }
            else {
                showErrorMessage();
            }
        }
        else {
            showErrorMessage();
        }
    });
    
    
    
    // (8) Submit deqaLoginModal on "enter" keypress
    $('#password').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13') {
            $('#deqaLoginSubmit').click();
        }
    });
    
    
    
    // If AppCache update is ready
    applicationCache.onupdateready = function() {
        
        $('.modal').modal('hide');
        
        // Show appCacheRefresh modal
        $('#appCacheRefresh').modal({
            backdrop:'static',
            keyboard:false,
            show:true
        });
        
        // Run when appCacheRefresh modal is shown
        $('#appCacheRefresh').on('shown.bs.modal', function() {
            setTimeout( function() {
                location.reload();
            }, 1000 );
        });
    }
    
    
    
});



function ajaxRefresh() {
    
    // Run script to update localStorage with data values
    $.ajax({
        url: "/LastMileData/src/php/refreshData.php",
    //            data: "myTestData=3",     // AVI: Potentially send data to filter localStorage based on user (e.g. only FHWs in one site)
        dataType: "json",
        success: function(data) {
            
            // Update localStorage.deqaUsers
            localStorage.initialized = "yes";
            localStorage.deqaUsers = JSON.stringify(data['deqaUsers']);
            localStorage.villages = JSON.stringify(data['villages']);
            
            // Manipulate DOM
            $('#refreshData_text').text('Data was successfully refreshed.');
            $('#initialize_text').text('Initialization successful.');
            
            // Reload page
            setTimeout( function() {
                location.reload();
            }, 1000 );

        },
        error: function(request, status, error) {
            // Display error message
            $('#refreshData_text').text('Data refresh was unsuccessful. Please try again later.');
            $('#initialize_text').text('Initialization was unsuccessful. Please try again later.');
            
            // Redirect to home page
            setTimeout( function() {
                // !!!!! build in more comprehensive error handler; e.g., alert if no internet conncetion is present
                window.location.assign('/LastMileData/');
            }, 1000 );
        }
    });
    
}



function showErrorMessage() {
    $('#incorrectLogin').slideDown(1000).delay(1000).slideUp(1000);
}



function closeAndResetModal() {
    setTimeout( function() {

        // Close dialog box
        $('.modal').modal('hide');
        
        setTimeout( function() {
            // Reset DOM text of modal box
            $('#sendRecords,#cancelModal_2').css('display','');
            $('#sendRecords_text').html('Are you sure you want to send all current records to the database?');
        }, 200 );
        
    }, 1000 );
}



function addslashes( str ) {
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}



// !!!!! function launchQAModal() !!!!!
function launchQAModal()
{
    // Reset DOM; remove datepicker; clear fields
    $('#pKeyDiv1, #pKeyDiv2, #pKeyDiv3').css('display','');
    $('#pKey1, #pKey2, #pKeyv3').datepicker('destroy');
    $('#pKey1, #pKey2, #pKeyv3').val('');
    
    // Set form label in QA Modal box
    $('#qaFormName').text(qaFormName);
    
    // Manipulate modal DOM
    if ( pKey1_label != "" ) {
        $('#pKeyDiv1 label').text(pKey1_label);
    }
    else {
        $('#pKeyDiv1').css('display','none');
    }
    
    // Manipulate modal DOM
    if ( pKey2_label != "" ) {
        $('#pKeyDiv2 label').text(pKey2_label);
    }
    else {
        $('#pKeyDiv2').css('display','none');
    }
    
    // Manipulate modal DOM
    if ( pKey3_label != "" ) {
        $('#pKeyDiv3 label').text(pKey3_label);
    }
    else {
        $('#pKeyDiv3').css('display','none');
    }
    
    // Apply jQueryUI datepicker (MySQL date format)
    $("#" + pKey_date).datepicker({
        dateFormat: 'yy-mm-dd',
    });
    
    // Only allow valid MySQl date format if user types into .datepicker input
    // !!!!! Functionize this code; also used in deqa.js !!!!!
    $("#" + pKey_date).blur(function() {
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
    
    // Open modal
    $('#qaModal').modal();
}



function parseRecordIntoSQL(currentRecord) {
    
    // Set array of currentRecord properties that are not stored
    var notStored = ['table', 'type'];
    
    // Begin query string
    var qs = "INSERT INTO lastmiledata." + currentRecord.table + " SET ";
    
    // Add key/value pairs to query string
    for(var currKey in currentRecord) {
        // if key isn't in "notStored" array, add it to query string
        if ( notStored.indexOf(currKey) == -1) {
            qs += currKey + "='" + addslashes(currentRecord[currKey]) + "', ";
        }
    }
    
    // Finish query string segment
    qs = qs.slice(0,-2);
    qs += ";";
    
    // Return query string
    return qs;
}



function noRecordsMessage() {
    // Display "no records" message in DOM
    $('#sendRecords_text').html('There are currently no locally-stored records.');
    
    // Close modal box
    closeAndResetModal();
}



function noMatchFound() {
    // Flash "no match found" message div
    $('#qaNoMatch').slideDown(1000).delay(1000).slideUp(1000);
}



function logError(e) {
    // Use "closures"; see http://www.howtocreate.co.uk/referencedvariables.html
    if (e.name == 'NotFoundError') {
        console.log('Caught error -- no records ever submitted');
        noRecordsMessage();
    }
    else {
        console.log('deqa - logError');
        console.log(e);
    }
}



/*
CryptoJS v3.1.2
code.google.com/p/crypto-js
(c) 2009-2013 by Jeff Mott. All rights reserved.
code.google.com/p/crypto-js/wiki/License
*/
var CryptoJS=CryptoJS||function(e,m){var p={},j=p.lib={},l=function(){},f=j.Base={extend:function(a){l.prototype=this;var c=new l;a&&c.mixIn(a);c.hasOwnProperty("init")||(c.init=function(){c.$super.init.apply(this,arguments)});c.init.prototype=c;c.$super=this;return c},create:function(){var a=this.extend();a.init.apply(a,arguments);return a},init:function(){},mixIn:function(a){for(var c in a)a.hasOwnProperty(c)&&(this[c]=a[c]);a.hasOwnProperty("toString")&&(this.toString=a.toString)},clone:function(){return this.init.prototype.extend(this)}},
n=j.WordArray=f.extend({init:function(a,c){a=this.words=a||[];this.sigBytes=c!=m?c:4*a.length},toString:function(a){return(a||h).stringify(this)},concat:function(a){var c=this.words,q=a.words,d=this.sigBytes;a=a.sigBytes;this.clamp();if(d%4)for(var b=0;b<a;b++)c[d+b>>>2]|=(q[b>>>2]>>>24-8*(b%4)&255)<<24-8*((d+b)%4);else if(65535<q.length)for(b=0;b<a;b+=4)c[d+b>>>2]=q[b>>>2];else c.push.apply(c,q);this.sigBytes+=a;return this},clamp:function(){var a=this.words,c=this.sigBytes;a[c>>>2]&=4294967295<<
32-8*(c%4);a.length=e.ceil(c/4)},clone:function(){var a=f.clone.call(this);a.words=this.words.slice(0);return a},random:function(a){for(var c=[],b=0;b<a;b+=4)c.push(4294967296*e.random()|0);return new n.init(c,a)}}),b=p.enc={},h=b.Hex={stringify:function(a){var c=a.words;a=a.sigBytes;for(var b=[],d=0;d<a;d++){var f=c[d>>>2]>>>24-8*(d%4)&255;b.push((f>>>4).toString(16));b.push((f&15).toString(16))}return b.join("")},parse:function(a){for(var c=a.length,b=[],d=0;d<c;d+=2)b[d>>>3]|=parseInt(a.substr(d,
2),16)<<24-4*(d%8);return new n.init(b,c/2)}},g=b.Latin1={stringify:function(a){var c=a.words;a=a.sigBytes;for(var b=[],d=0;d<a;d++)b.push(String.fromCharCode(c[d>>>2]>>>24-8*(d%4)&255));return b.join("")},parse:function(a){for(var c=a.length,b=[],d=0;d<c;d++)b[d>>>2]|=(a.charCodeAt(d)&255)<<24-8*(d%4);return new n.init(b,c)}},r=b.Utf8={stringify:function(a){try{return decodeURIComponent(escape(g.stringify(a)))}catch(c){throw Error("Malformed UTF-8 data");}},parse:function(a){return g.parse(unescape(encodeURIComponent(a)))}},
k=j.BufferedBlockAlgorithm=f.extend({reset:function(){this._data=new n.init;this._nDataBytes=0},_append:function(a){"string"==typeof a&&(a=r.parse(a));this._data.concat(a);this._nDataBytes+=a.sigBytes},_process:function(a){var c=this._data,b=c.words,d=c.sigBytes,f=this.blockSize,h=d/(4*f),h=a?e.ceil(h):e.max((h|0)-this._minBufferSize,0);a=h*f;d=e.min(4*a,d);if(a){for(var g=0;g<a;g+=f)this._doProcessBlock(b,g);g=b.splice(0,a);c.sigBytes-=d}return new n.init(g,d)},clone:function(){var a=f.clone.call(this);
a._data=this._data.clone();return a},_minBufferSize:0});j.Hasher=k.extend({cfg:f.extend(),init:function(a){this.cfg=this.cfg.extend(a);this.reset()},reset:function(){k.reset.call(this);this._doReset()},update:function(a){this._append(a);this._process();return this},finalize:function(a){a&&this._append(a);return this._doFinalize()},blockSize:16,_createHelper:function(a){return function(c,b){return(new a.init(b)).finalize(c)}},_createHmacHelper:function(a){return function(b,f){return(new s.HMAC.init(a,
f)).finalize(b)}}});var s=p.algo={};return p}(Math);
(function(){var e=CryptoJS,m=e.lib,p=m.WordArray,j=m.Hasher,l=[],m=e.algo.SHA1=j.extend({_doReset:function(){this._hash=new p.init([1732584193,4023233417,2562383102,271733878,3285377520])},_doProcessBlock:function(f,n){for(var b=this._hash.words,h=b[0],g=b[1],e=b[2],k=b[3],j=b[4],a=0;80>a;a++){if(16>a)l[a]=f[n+a]|0;else{var c=l[a-3]^l[a-8]^l[a-14]^l[a-16];l[a]=c<<1|c>>>31}c=(h<<5|h>>>27)+j+l[a];c=20>a?c+((g&e|~g&k)+1518500249):40>a?c+((g^e^k)+1859775393):60>a?c+((g&e|g&k|e&k)-1894007588):c+((g^e^
k)-899497514);j=k;k=e;e=g<<30|g>>>2;g=h;h=c}b[0]=b[0]+h|0;b[1]=b[1]+g|0;b[2]=b[2]+e|0;b[3]=b[3]+k|0;b[4]=b[4]+j|0},_doFinalize:function(){var f=this._data,e=f.words,b=8*this._nDataBytes,h=8*f.sigBytes;e[h>>>5]|=128<<24-h%32;e[(h+64>>>9<<4)+14]=Math.floor(b/4294967296);e[(h+64>>>9<<4)+15]=b;f.sigBytes=4*e.length;this._process();return this._hash},clone:function(){var e=j.clone.call(this);e._hash=this._hash.clone();return e}});e.SHA1=j._createHelper(m);e.HmacSHA1=j._createHmacHelper(m)})();
