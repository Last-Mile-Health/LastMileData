// Dependencies: jQuery, jQueryUI, LMD_fileSystemHelpers.js, LMD_utilities.js

$(document).ready(function(){
    
    // Set GLOBALS object
    var GLOBALS = {
        queryDebugging: false
    };
    
    
    // Set app "last updated" timestamp (from AppCache manifest)
    $.ajax({
        url: '../../lastmiledata.appcache',
        success: function(data){
            $('#appVersion').text('App last updated: ' + data.substring(23,47));
        }
    });


    // CLICK HANDLER: Send Records
    $('#modal_sendRecords_submit').click(function(){
        
        // Manipulate DOM
        $('#modal_sendRecords_buttons').slideUp(600);
        $('#modal_sendRecords_text').slideUp(800, function(){
            sendRecordsAJAX(GLOBALS.queryDebugging);
        });
        
    });

    
    // CLICK HANDLER: Close "uploadLMD" modal
    $('#modal_uploadLMD_done').click(function(){
        
        // Close dialog box
        $('.modal').modal('hide');
        
        // Pause, reset DOM
        setTimeout( function() {
            $('#modal_uploadLMD_message').text('Uploading and merging data file...');
            $('#modal_uploadLMD_status').hide();
            $('#modal_uploadLMD_done').hide();
            $('#modal_uploadLMD_formContent').show();
            $('#modal_uploadLMD_form').get(0).reset();
        }, 500 );
        
    });
    
    
    // CLICK HANDLER: Close "createLMU" modal
    $('#modal_createLMU_done').click(function(){
        
        // Close dialog box
        $('.modal').modal('hide');
        
        // Pause, reset DOM
        setTimeout( function() {
            $('#modal_createLMU_message').text('Creating .LMU file...');
            $('#modal_createLMU_status').hide();
            $('#modal_createLMU_done').hide();
            $('#modal_createLMU_formContent').show();
            $('#modal_createLMU_form').get(0).reset();
        }, 500 );
        
    });
    
    
    // CLICK HANDLER: Close send Records modal
    $('#modal_sendRecords_close').click(function(){
        
        // Close dialog box
        $('.modal').modal('hide');
        
        // Pause, reset DOM
        setTimeout( function() {
            $('#modal_sendRecords_buttons, #modal_sendRecords_ajaxInner, #modal_sendRecords_close').css('display','');
            $('#modal_sendRecords_buttons').css('display','block');
            $('#modal_sendRecords_text').html('Are you sure you want to send all current records to the database?');
            $('#modal_sendRecords_ajaxInner').html('');
        }, 500 );
        
    });
    
    
    // CLICK HANDLER: Download data file
    $("#modal_downloadDataFile_submit").click(function() {
        
        LMD_fileSystemHelper.readAndUseFile('data.lmd', function(result){
            
            $('#modal_downloadDataFile_prompt').slideUp(500,function(){
                
                if (result == "" || result == "{}") {
                    // Display message, close and reset modal
                    $('#modal_downloadDataFile_text').text('There are currently no locally-stored records.');
                    $('#modal_downloadDataFile_downloading').slideDown(500,function(){
                        setTimeout(function(){
                            $('.modal').modal('hide');
                            setTimeout(function(){
                                $('#modal_downloadDataFile_prompt').show();
                                $('#modal_downloadDataFile_downloading').hide();
                                $('#modal_downloadDataFile_text').text('Downloading data file...');
                            },500);
                        },1500);
                    });
                } else {
                    
                    // Display message, close and reset modal
                    $('#modal_downloadDataFile_downloading').slideDown(500,function(){
                        
                        // Download LMU file (function returns filename)
                        var fullFilename = LMD_utilities.downloadStringAsFile({
                            fileName: 'data',
                            fileExtension: 'lmd',
                            fileContents: result,
                            appendDateToFilename: true
                        });

                        // Back up the file to the "dataFileBackups" directory
                        LMD_fileSystemHelper.createDirectory('dataFileBackups', function(){
                            // !!!!! create an interface to access these backups !!!!!
                            LMD_fileSystemHelper.createOrOverwriteFile('/dataFileBackups/downloadDataFileBackup_' + fullFilename, result, function(){
                                // Delete file
                                LMD_fileSystemHelper.deleteFile('data.lmd');
                                
                                // Close modal and reset DOM
                                setTimeout(function(){
                                    $('.modal').modal('hide');
                                    setTimeout(function(){
                                        $('#modal_downloadDataFile_prompt').show();
                                        $('#modal_downloadDataFile_downloading').hide();
                                    },500);
                                },1500);
                            });
                        });
                    });
                }
            });
        });
    });
    
    
    // CLICK HANDLER: Upload data file
    // Data files are either ".LMD" files (which are either JSON or concatenated XML documents delimited with "<LMD_delimiter>") or ".XML" files
    $("#modal_uploadLMD_submit").click(function() {
        
        // Reset error flag; get file input contents
        var anyErrors = false;
        var myInput = document.getElementById('modal_uploadLMD_fileInput');
        
        // Error check #1: No file was selected
        if (myInput.files.length === 0) {
            $('#modal_uploadLMD_error').text('No file was selected.');
            flashDiv('#modal_uploadLMD_error');
            anyErrors = true;
        }
        
        // Error check #2: incorrect file extension(s)
        if (!anyErrors) {
            for(var i=0; i<myInput.files.length; i++) {
                if (!anyErrors) {
                    
                    // Get file and extension
                    var fileToLoad = myInput.files[i];
                    if (fileToLoad !== undefined) {
                        var sFileName = fileToLoad.name;
                        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
                    }
                    
                    // Incorrect file extension (not ".lmd" or ".xml")
                    if (['xml','lmd'].indexOf(sFileExtension.toLowerCase()) === -1) {
                        // User is NOT notified that he/she can select XML files; this is a feature for advanced users only
                        $('#modal_uploadLMD_error').text('Please select only ".lmd" files.');
                        anyErrors = true;
                        flashDiv('#modal_uploadLMD_error');
                    }
                    
                }
            }
        }
        
        // No errors; proceed with upload
        if (!anyErrors) {
            
            // Set counter
            var numFilesProcessed = 0;
            
            // Manipulate DOM
            $('#modal_uploadLMD_formContent').slideUp(500, function(){
                $('#modal_uploadLMD_status').slideDown(500);
            });
            
            // Reset uploadedRecordset global object
            uploadedRecordset = {
                counter: 0,
                records: {},
                addRecord: function(myRecord){
                    this.records[this.counter] = myRecord;
                    this.counter++;
                },
                clear: function(){ this.records = {}; }
            };

            // Loop through files and parse data
            for(var i=0; i < myInput.files.length; i++) {
                (function(i){
                    
                    var file = myInput.files[i];
                    var reader = new FileReader();
                    reader.onload = function() {

                        // Get file contents; test if XML or JSON
                        var fileContents = reader.result;
                        var xmlOrJson;
                        var snippet = fileContents.substring(0,20).trim();
                        if (snippet.substring(0,1) === "{") {
                            var xmlOrJson = "json";
                        } else if (snippet.substring(0,1) === "<") {
                            var xmlOrJson = "xml";
                        }
                        
                        // Proceed if JSON
                        if (xmlOrJson === "json") {
                            
                            // Add records to uploadedRecordset object
                            try {
                                var myRecords = JSON.parse(fileContents);
                                
                                for (var key in myRecords) {
                                    uploadedRecordset.addRecord(myRecords[key]);
                                }
                                
                            } catch(e) {
                                // Display error message
                                $('#modal_uploadLMD_message').text('Error parsing file.');
                                anyErrors = true;
                                console.log(e);
                            }
                            
                        // Proceed if XML
                        } else if (xmlOrJson === "xml") {
                            
                            try {
                                // Split LMD files into components
                                var fileContentArray = fileContents.split("<LMD_delimiter>");

                                // Parse individual XML files
                                for (var j=0; j<fileContentArray.length; j++) {

                                    var myJQXML = $.parseXML(fileContentArray[j].trim());
                                    // !!!!! Build an error catcher for invalid XML that displays helpful error messages !!!!!
									// !!!!! Sample errors to catch: (1) missing <LMD-DATABASE> tag, (2) missing <LMD-TABLE> tag. (3) generic "invalid XML" !!!!!
                                    
                                    // Check for null strings caused by trailing whitespace or trailing <LMD_delimiter> tags
                                    if (myJQXML !== null) {
                                        
                                        var $myJQXML = $(myJQXML);

                                        // Extract database schema name
                                        var $dbTag = $myJQXML.find('*').filter(function(){return /^LMD\-DATABASE/i.test(this.nodeName);}).remove();
                                        var dbName = $dbTag[0].textContent;

                                        // Extract sub-records (aka. "repeating groups")
                                        var $subRecords = $myJQXML.find('*').filter(function(){return /^LMD\-RPT/i.test(this.nodeName);}).remove();

                                        // Process main XML file ($myJQXML)
                                        var xmlKey, xmlValue, chkArray, pullString = "";
                                        var xmlRecord = { database:dbName, meta_uploadUser:sessionStorage.username };
                                        var $elementSet = $myJQXML.find('*').filter(function(){return /^LMD\-/i.test(this.nodeName);});
                                        $elementSet.each(function(){
                                            // !!!!! document this code !!!!!
                                            xmlPair = processLMD($(this).prop("tagName"),$(this).text());
                                            if (xmlPair.key === "CHK") {
                                                chkArray = xmlPair.value.split(" ");
                                                for (var opt=0; opt<chkArray.length; opt++) {
                                                    if (chkArray[opt] !== "") {
                                                        chkKey = chkArray[opt].slice(8);
                                                        xmlRecord[$(this).prop("tagName").slice(8) + "_" + chkKey] = 1;
                                                    }
                                                }
                                            } else {
                                                xmlRecord[xmlPair.key] = xmlPair.value;
                                            }
                                        });

                                        uploadedRecordset.addRecord(JSON.stringify(xmlRecord));

                                        // Process subgroups ($subRecords)
                                        for(var k=0; k<$subRecords.length; k++) {

                                            var pullFields = [];
                                            var xmlSubrecord = { database:dbName, table: $subRecords[k].tagName.slice(8) };
                                            $elementSet = $($subRecords[k]).find('*').filter(function(){return /^LMD\-/i.test(this.nodeName);});
                                            $elementSet.each(function(){
                                                // !!!!! document this code
                                                if ($(this).prop("tagName")==='LMD-PULL') {
                                                    pullString = $(this).text();
                                                    var pullFieldsBulky = pullString.split(",");
                                                    pullFields = $.map(pullFieldsBulky,function(val){return val.trim().slice(8);});
                                                } else {
                                                    xmlPair = processLMD($(this).prop("tagName"),$(this).text());
                                                    if (xmlPair.key === "CHK") {
                                                        chkArray = xmlPair.value.split(" ");
                                                        for (var opt=0; opt<chkArray.length; opt++) {
                                                            if (chkArray[opt] !== "") {
                                                                chkKey = chkArray[opt].slice(8);
                                                                xmlRecord[$(this).prop("tagName").slice(8) + "_" + chkKey] = 1;
                                                            }
                                                        }
                                                    } else {
                                                        xmlSubrecord[xmlPair.key] = xmlPair.value;
                                                    }
                                                }
                                            });

                                            // Pull fields from xmlRecord into sub-record
                                            if (pullFields.length > 0) {
                                                for(var l=0; l<pullFields.length; l++) {
                                                    // Filter out invalid PULL fields (will have no effect for proper xForms) !!!!! remove after LMS ?????
                                                    if(xmlRecord[pullFields[l]] !== undefined) {
                                                        xmlSubrecord[pullFields[l]] = xmlRecord[pullFields[l]];
                                                    }
                                                }
                                            }
                                            uploadedRecordset.addRecord(JSON.stringify(xmlSubrecord));
                                        }
                                    }
                                }
                            } catch(e) {
                                // Display error message
                                $('#modal_uploadLMD_message').text('Error parsing file.');
                                anyErrors = true;
                                console.log(e);
                            }
                            
                        } else {
                            // !!!!! Build out all error handlers !!!!!
                            // Display error message
                            $('#modal_uploadLMD_message').text('Error parsing file.');
                            anyErrors = true;
                            console.log(e);
                        }
                        
                        // Increment file counter
                        numFilesProcessed++;
                        
                    };
                    reader.readAsText(file);
                })(i);
            }
            
            // When all files have been processed, proceed
            var myTimer = setInterval(function(){

                if(numFilesProcessed === myInput.files.length) {

                    if (!anyErrors) {

                        // Merge new records with old records
                        var myRecord = {};
                        LMD_fileSystemHelper.readAndUseFile('data.lmd', function(result){

                            // Read existing file (if exists) into mergedRecordset
                            var mergedRecordset = {};
                            i = 1;
                            if (result != "") {
                                var oldRecordset = JSON.parse(result);
                                for (var key in oldRecordset) {
                                    mergedRecordset[i] = oldRecordset[key];
                                    i++;
                                }
                            }

                            // Add uploaded records to mergedRecordset
                            for (var key in uploadedRecordset.records) {
                                mergedRecordset[i] = uploadedRecordset.records[key];
                                i++;
                            }

                            // Write new recordset to data.lmd
                            LMD_fileSystemHelper.createOrOverwriteFile('data.lmd', JSON.stringify(mergedRecordset), function(){
                                setTimeout(function(){
                                    // Handle success
                                    $('#modal_uploadLMD_message').text('Upload and merge complete.');
                                    $('#modal_uploadLMD_done').fadeIn();
                                },1500);
                            });
                        });
                    }

                    clearInterval(myTimer);
                }

            },500);
            
            
        }
    });
    

    // CLICK HANDLER: Create .LMU file
    // Data files are ".XML" xForm files. This function concatenates uploaded files and puts <LMD_delimiter> in between (with two line breaks before and after delimiter)
    $("#modal_createLMU_submit").click(function() {
        
        // Reset error flag; get file input contents
        var anyErrors = false;
        var myInput = document.getElementById('modal_createLMU_fileInput');
        
        // Error check #1: No file was selected
        if (myInput.files.length === 0) {
            $('#modal_createLMU_error').text('No file was selected.');
            flashDiv('#modal_createLMU_error');
            anyErrors = true;
        }
        
        // Error check #2: incorrect file extension(s)
        if (!anyErrors) {
            for(var i=0; i<myInput.files.length; i++) {
                if (!anyErrors) {
                    
                    // Get file and extension
                    var fileToLoad = myInput.files[i];
                    if (fileToLoad !== undefined) {
                        var sFileName = fileToLoad.name;
                        var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
                    }
                    
                    // Incorrect file extension (not ".xml")
                    if (sFileExtension.toLowerCase() !== 'xml') {
                        $('#modal_createLMU_error').text('Please select only ".XML" files.');
                        anyErrors = true;
                        flashDiv('#modal_createLMU_error');
                    }
                    
                }
            }
        }
        
        // No errors; proceed with file creation
        if (!anyErrors) {
            
            // Set counter; create LMU file
            var numFilesProcessed = 0;
            var LMU = '';
            
            // Manipulate DOM
            $('#modal_createLMU_formContent').slideUp(500, function(){
                $('#modal_createLMU_status').slideDown(500);
            });
            
            // Loop through files and parse data
            for(var i=0; i < myInput.files.length; i++) {
                (function(i){
                    
                    var file = myInput.files[i];
                    var reader = new FileReader();
                    reader.onload = function() {

                        // Get file contents; test if XML or JSON
                        LMU += reader.result;
                        LMU += '\n\n<LMD_delimiter>\n\n';
                        
                        // Increment file counter
                        numFilesProcessed++;
                    };
                    reader.readAsText(file);
                })(i);
            }
            
            // When all files have been processed, proceed
            var myTimer = setInterval(function(){

                if(numFilesProcessed === myInput.files.length) {
                    
                    // Trim LMU file
                    LMU = LMU.slice(0,-19);
                    
                    // Display success message
                    $('#modal_createLMU_message').text('LMU creation complete.');
                    $('#modal_createLMU_done').fadeIn();

                    // Download LMU file
                    LMD_utilities.downloadStringAsFile({
                        fileName: 'LMU',
                        fileExtension: 'lmu',
                        fileContents: LMU,
                        appendDateToFilename: true
                    });
                    
                    clearInterval(myTimer);
                }

            },500);
            
        }
    });


    // CLICK HANDLER: View an xForm
    // Data files are ".XML" xForm files. This function visualizes the xForm as a readable HTML file
    $("#modal_viewXform_submit").click(function() {
        
        // Reset error flag; get file input contents
        var anyErrors = false;
        var myInput = document.getElementById('modal_viewXform_fileInput');
        
        // Error check #1: No file was selected
        if (myInput.files.length === 0) {
            $('#modal_viewXform_error').text('No file was selected.');
            flashDiv('#modal_viewXform_error');
            anyErrors = true;
        }
        
        // Error check #2: incorrect file extension(s)
        if (!anyErrors) {
            if (!anyErrors) {

                // Get file and extension
                var fileToLoad = myInput.files[0];
                if (fileToLoad !== undefined) {
                    var sFileName = fileToLoad.name;
                    var sFileExtension = sFileName.split('.')[sFileName.split('.').length - 1].toLowerCase();
                }

                // Incorrect file extension (not ".xml")
                if (sFileExtension.toLowerCase() !== 'xml') {
                    $('#modal_viewXform_error').text('Please select only ".XML" files.');
                    anyErrors = true;
                    flashDiv('#modal_viewXform_error');
                }

            }
        }
        
        // No errors; proceed
        if (!anyErrors) {
            // Submit form
            $('#modal_viewXform_form').submit();
        }
    });


    // QA Click handlers (current forms)
    $('#qa_MSR_0501').click(function() {
        launchQAModal({
            targetForm: "../forms/cha_msr0501_monthlyservicereport.html",
            qaFormName: "CHA Form (MSR 5.1) - CHA Monthly Service Report",
            pKey1_name: "cha_id",
            pKey2_name: "chss_id",
            pKey3_name: "community_id",
            pKey1_label: "CHA ID",
            pKey2_label: "CHSS ID",
            pKey3_label: "Community ID"
        });
    }); 
    
    $('#qa_HHR_0101').click(function() {
        launchQAModal({
            targetForm: "../forms/cha_hhr0101_householdregistration.html",
            qaFormName: "CHA Form (HHR 1.1) - Household Registration Form",
            pKey1_name: "chaID",
            pKey2_name: "communityID",
            pKey3_name: "1_1_A_total_number_households",
            pKey1_label: "CHA ID",
            pKey2_label: "Community ID",
            pKey3_label: "1.1A Total Number Households"
        });
    });
    
    $('#qa_SMR_0502').click(function() {
        launchQAModal({
            targetForm: "../forms/chss_msr0502_monthlyservicereport.html",
            qaFormName: "CHSS Form (MSR 5.2) - CHSS Monthly Service Report",
            pKey1_name: "chss_id",
            pKey2_name: "month_reported",
            pKey3_name: "cha_id_1",
            pKey1_label: "CHSS ID",
            pKey2_label: "Report Month",
            pKey3_label: "CHA ID 1"
        });
    }); 
    
        $('#qa_CSC_0601').click(function() {
        launchQAModal({
            targetForm: "../forms/cha_status_change_form_06_01.html",
            qaFormName: "CHA Form (CSC 6.1) - CHA Status Change Form",
            pKey1_name: "cha_id",
            pKey2_name: "first_name",
            pKey3_name: "last_name",
            pKey1_label: "CHA ID",
            pKey2_label: "First Name",
            pKey3_label: "Last Name"
        });
    }); 
    
    $('#qa_CCD_0400').click(function() {
        launchQAModal({
            targetForm: "../forms/chss_commodity_distribution_04_00.html",
            qaFormName: "CHSS Form (CCD 4.0) - Commodity Distribution",
            pKey1_name: "chss_id",
            pKey2_name: "restock_date",
            pKey1_label: "CHSS ID",
            pKey2_label: "Restock Date"
        });
    });

    $('#qa_CST_0101').click(function() {
        launchQAModal({
            targetForm: "../forms/case_scenarios_1_1.html",
            qaFormName: "(CST 1.0) - Case Scenarios",
            pKey1_name: "cha_id",
            pKey2_name: "date_form",
            pKey1_label: "CHA ID",
            pKey2_label: "Date"
        });
    });  

    $('#qa_TST').click(function() {
        launchQAModal({
            targetForm: "../forms/0_testDE.html",
            qaFormName: "TEST FORM",
            pKey1_name: "var1",
            pKey2_name: "var6",
            pKey1_label: "Var 1",
            pKey2_label: "Var 6",
            pKey_date: "pKey2"
        });
    });
    
    // QA Click handlers (old forms).  Be sure to add "old/" to form path.
    $('#qa_TRL_03').click(function() {
        launchQAModal({
            targetForm: "../forms/old/prg_trl03_trainingledger.html",
            qaFormName: "Program: Training Ledger",
            pKey1_name: "trainingType",
            pKey2_name: "trainingDate",
            pKey3_name: "firstName_1",
            pKey4_name: "lastName_1",
            pKey1_label: "Training Type",
            pKey2_label: "Training date",
            pKey3_label: "First name (1)",
            pKey4_label: "Last name (1)",
            pKey_date: "pKey2"
        });
    });


    // CLICK HANDLER: Query debugging
    $('#toggleQueryDebugging').click(function(){
        GLOBALS.queryDebugging = !GLOBALS.queryDebugging;
        var state = (GLOBALS.queryDebugging) ? 'ON' : 'OFF';
        alert('Query debugging is ' + state);
    });


    // CLICK HANDLER: QA modal
    $('#modal_QA_submit').click(function() {

        // Set pKey values
        pKey1_val = $('#modal_QA_pKey1').val();
        pKey2_val = $('#modal_QA_pKey2').val();
        pKey3_val = $('#modal_QA_pKey3').val();
        pKey4_val = $('#modal_QA_pKey4').val();
        
        // Initialize "QA Record ID" (i.e. key of myRecordset object where desired record is contained)
        var qaRecordID = false;
        
        // Read in file and run callback
        LMD_fileSystemHelper.readAndUseFile('data.lmd', function(result){
            
            if (result == "" || result == "{}") {
                flashDiv('#qaNoMatch');
            }
            else {
                // Otherwise, parse myRecordset into object
                myRecordset = JSON.parse(result);
                
                // First loop through keys of myRecordset (set numRecords)
                for (var key in myRecordset) {
                    try {
                        // Assign record object to currentRecord
                        currentRecord = JSON.parse(myRecordset[key]);
                    }
                    catch(e) {
                        currentRecord = 1;  // To avoid JSON.Parse() returning an error if value variable is not valid JSON
                    }
                    
                    // Test that pKey1 matches
                    if ( pKey1_val == currentRecord[window.lmd_qaOptions.pKey1_name] ) {

                        // Test that pKey2 matches (or doesn't exist)
                        if ( window.lmd_qaOptions.pKey2_name === undefined || pKey2_val == currentRecord[window.lmd_qaOptions.pKey2_name] ) {

                            // Test that pKey3 matches (or doesn't exist)
                            if ( window.lmd_qaOptions.pKey3_name === undefined || pKey3_val == currentRecord[window.lmd_qaOptions.pKey3_name] ) {

                                // Test that pKey4 matches (or doesn't exist)
                                if ( window.lmd_qaOptions.pKey4_name === undefined || pKey4_val == currentRecord[window.lmd_qaOptions.pKey4_name] ) {
                                    // Match found; set qaRecordID
                                    var qaRecordID = key;
                                }
                            }
                        }
                    }
                }
                
                // Handle: match is found
                if (qaRecordID) {
                    // Set qaRecordID; redirect
                    localStorage.qaRecordID = qaRecordID;
                    location.assign(window.lmd_qaOptions.targetForm);
                }
                // Handle: no match found
                else {
                    flashDiv('#qaNoMatch');
                }
            }

        });
        
    });
    
    
    // Run "Refresh System Data" function when modal_refreshData is shown
    $('#modal_refreshData').on('shown.bs.modal', function(e) {
        ajaxRefresh();
    });
    
    
    // Run "Refresh System Data" function once per day
    if (localStorage.lastAjaxRefresh !== LMD_utilities.mysql_date()) {
        $("#modal_refreshData").modal();
        ajaxRefresh();
    }
    
    
    // CLICK HANDLERS: versions
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
    
    
    // ERROR HANDLER: AppCache error
    window.applicationCache.onerror = function (e) {
        $('#modal_appcacheRefresh_text').html('An error occurred (AppCache error). Please contact <a href=\'mailto:LMD@LastMileHealth.org\'>LMD@LastMileHealth.org</a> for assistance.');
        $('#modal_appcacheRefresh_progress').text('');
        console.log(e);
    }
    
    
});



$(window).load(function(){
// !!!!! what needs to go here versus in the document.ready section above ?????
    
    
    
    // Only allow webkit-based broswers (Safari or Chrome); !!!!! consider using browser.version propery as well (Webkit build number) !!!!!
    var browser = (function() { var s = navigator.userAgent.toLowerCase(); var match = /(webkit)[ \/]([\w.]+)/.exec(s) || /(opera)(?:.*version)?[ \/]([\w.]+)/.exec(s) || /(msie) ([\w.]+)/.exec(s) || !/compatible/.test(s) && /(mozilla)(?:.*? rv:([\w.]+))?/.exec(s) || [];return { name: match[1] || "", version: match[2] || "0" }; }());
    if (browser.name !== "webkit") {
        // Display error alert; redirect to home page
        alert("The LastMileData.org \"Data Entry / Quality Assurance\" application only works in Google Chrome or Safari.");
        window.location.assign('/LastMileData');
    }
    else {
        // If site has been initialized, proceed to DEQA
        if ( localStorage.initialized === "yes" ) {
            // If user is not logged in, show login modal; else, proceed to DEQA
            if (!sessionStorage.username) {
                $('#modal_deqaLogin').modal();
            }
        // If site has NOT been initialized
        } else {
            // Show initialize modal
            $('#modal_initialize').modal();

            // Run when initialize modal is shown
            $('#modal_initialize').on('shown.bs.modal', function() {
                ajaxRefresh(); // Note: showLoginModal is run on success callback of ajaxRefresh();
            });
        }
    }
    
    
    
    // CLICK HANDLER: DEQA Login
    $('#modal_deqaLogin_submit').click(function(event) {
        
        // Set usernames and passwords here; usernames should only contain lowercase letters; passwords are SHA1
        var deqaUserArray = JSON.parse(localStorage.deqaUsers);
        
        // Get username and password values from fields
        var username = $('#modal_deqaLogin_username').val();
        var password = $('#modal_deqaLogin_password').val();
        
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
    
    
    
    //  KEYPRESS HANDLER: Submit deqaLoginModal on "enter" keypress
    $('#modal_deqaLogin_password').keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13') {
            $('#modal_deqaLogin_submit').click();
        }
    });
    
    
    
    // EVENT HANDLER: AppCache downloading
    applicationCache.ondownloading = function() {
        
        if (localStorage.initialized === "yes") {
            // Hide any existing modals; display appcacheRefresh modal
            $('.modal').modal('hide');
            $('#modal_appcacheRefresh').modal();
            return false; // !!!!! why are the "return false" statements necessary (here and below) ?????
        }
        
    }
    
    
    
    // EVENT HANDLER: AppCache progress
    applicationCache.onprogress = function(ev) {
        var progressMessage = "Progress: " + ev.loaded + " of " + ev.total + " files ("+ Math.round(100*ev.loaded/ev.total) +"%)";
        $("#modal_appcacheRefresh_progress").text(progressMessage);
        $("#modal_initialize_progress").text(progressMessage);
        return false;
    };
    
    
    
    // EVENT HANDLER: AppCache cached
    applicationCache.oncached = function() {
        
        // To handle cases where the AppCache was cleared manually by the user (but localStorage remains)
        $('#modal_appcacheRefresh_text').text('New application version has been successfully downloaded. Reloading page now...')
        setTimeout(function(){
            location.reload();
        }, 1500);
        return false;
        
    };
    
    
    
    // EVENT HANDLER: AppCache update ready
    applicationCache.onupdateready = function() {
        
        if (localStorage.initialized === "yes") {
            $('#modal_appcacheRefresh_text').text('New application version has been successfully downloaded. Reloading page now...')
            setTimeout(function(){
                location.reload();
            }, 1500);
            return false;
        }
    }
    
    
    
});



function processLMD(inputKey, inputValue) {
    
    var outputKey, outputValue;
    var fieldType = inputKey.slice(4,7);

    switch(fieldType) {
        
        case 'TAB':
            outputKey = "table";
            outputValue = inputValue;
            break;
        case 'VAL':
            outputKey = inputKey.slice(8);
            outputValue = inputValue;
            break;
        case 'DAT': // !!!!! this is an artifact of old forms and should eventually be removed !!!!!
            outputKey = inputKey.slice(8);
            outputValue = inputValue;
            break;
        case 'TIM': // !!!!! this is an artifact of old forms and should eventually be removed !!!!!
            outputKey = inputKey.slice(8);
            outputValue = inputValue;
            break;
        case 'CHK':
            outputKey = "CHK";
            outputValue = inputValue;
            break;
        default:
            // !!!!! This error is not currently handled !!!!!
            outputKey = "invalid LMD file";
            outputValue = "invalid LMD file";
    }

    return {
        key: outputKey,
        value: outputValue
    };

}



function ajaxRefresh() {
    
    // Run script to update localStorage with data values
    // See refreshData.php for further info
    $.ajax({
        url: "/LastMileData/php/scripts/refreshData.php",
        dataType: "json",
        success: function(data) {
            
            // !!!!! This data is currently stored in localStorage; should be stored instead in FileSystem (to avoid running out of storage space); this approach may also lead to namespacing issues !!!!!
            
            // Update localStorage
            localStorage.initialized = "yes";
            localStorage.lastAjaxRefresh = LMD_utilities.mysql_date();
            for (var key in data) {
                localStorage[key] = JSON.stringify(data[key]);
            }
            
            // Manipulate DOM
            $('#modal_refreshData_text').text('System data was successfully refreshed. Reloading page now...');
            $('#modal_initialize_text').text('Initialization successful. Reloading page now...');
            
            // Reload page
            setTimeout( function() {
                // Note: this will result in an infinite loop if any of the above queries fail
                location.reload();
            }, 1500 );
            
        },
        error: function(request, status, error) {
            // Display error message
            $('#modal_refreshData_text').text('Data refresh was unsuccessful. Please try again later.');
            $('#modal_initialize_text').text('Initialization was unsuccessful. Please try again later.');
            
            // Redirect to home page
            setTimeout( function() {
                location.reload();
            }, 1000 );
        }
    });
    
}



function showErrorMessage() {
    $('#modal_deqaLogin_incorrectLogin').slideDown(1000).delay(1000).slideUp(1000);
}



function launchQAModal(options)
{
    // Set global qaOptions object
    window.lmd_qaOptions = options;
    
    // Reset DOM
    $('#modal_QA_pKeyDiv1, #modal_QA_pKeyDiv2, #modal_QA_pKeyDiv3, #modal_QA_pKeyDiv4').css('display','');
    $('#modal_QA_pKey1, #modal_QA_pKey2, #modal_QA_pKey3, #modal_QA_pKey4').datepicker('destroy');
    $('#modal_QA_pKey1, #modal_QA_pKey2, #modal_QA_pKey3, #modal_QA_pKey4').val('');
    $('#qaFormName').text(options.qaFormName);
    
    // Manipulate modal DOM
    if ( options.pKey1_label ) {
        $('#modal_QA_pKeyDiv1 label').text(options.pKey1_label);
    }
    else {
        $('#modal_QA_pKeyDiv1').css('display','none');
    }
    if ( options.pKey2_label ) {
        $('#modal_QA_pKeyDiv2 label').text(options.pKey2_label);
    }
    else {
        $('#modal_QA_pKeyDiv2').css('display','none');
    }
    if ( options.pKey3_label ) {
        $('#modal_QA_pKeyDiv3 label').text(options.pKey3_label);
    }
    else {
        $('#modal_QA_pKeyDiv3').css('display','none');
    }
    if ( options.pKey4_label ) {
        $('#modal_QA_pKeyDiv4 label').text(options.pKey4_label);
    }
    else {
        $('#modal_QA_pKeyDiv4').css('display','none');
    }
    
    // Apply jQueryUI datepicker (MySQL date format)
    if ( options.pKey_date ) {
        // Datepicker; enforce MySQl date format
        $("#" + options.pKey_date).datepicker({dateFormat: 'yy-mm-dd'});
        $("#" + options.pKey_date).blur(datepickerBlur);
    }
    
    // Open modal
    $('#modal_QA').modal();
}


function flashDiv(myDiv) {
    // Flash "no match found" message div
    $(myDiv).slideDown(1000).delay(1000).slideUp(1000);
}



function noRecordsMessage() {
    // Display "no records" message in DOM; reset modal
    $('#modal_sendRecords_text').html('There are currently no locally-stored records.');
    $('#modal_sendRecords_text').slideDown(500);
    $('#modal_sendRecords_close').slideDown(500);
}



function sendRecordsAJAX(queryDebugging){
    
    // Reset variables
    var queryString = "",
        numRecords = 0,
        numAjax_success = 0,
        numAjax_fail = 0;
        
    LMD_fileSystemHelper.readAndUseFile('data.lmd', function(result){
        
        if (result == "" || result == "{}") {
            noRecordsMessage();
        }
        else {
            
            // Manipulate DOM
            $('#modal_sendRecords_ajaxLoadIcon').slideDown(500);
            $('#modal_sendRecords_ajaxInner').slideDown(500,function(){
                
                // Otherwise, parse myRecordset into object
                myRecordset = JSON.parse(result);
                
                // First loop through keys of myRecordset (set numRecords and manipulate DOM)
                for (var key in myRecordset) {
                    try {
                        // Assign record object to currentRecord
                        currentRecord = JSON.parse(myRecordset[key]);
                    }
                    catch(e) {
                        currentRecord = 1;  // To avoid JSON.Parse() returning an error if value variable is not valid JSON
                    }
                    
                    // Add "color blocks" (one block represents one record)
                    numRecords++;
                    $('#modal_sendRecords_ajaxInner').append('<div id="ajaxBlock_' + key + '" class="ajaxBlock">' + numRecords + '</div>');
                }
                
                // Second loop through keys of myRecordset (process numRecords)
                for (var key in myRecordset) {
                    
                    try {
                        // Assign record object to currentRecord
                        currentRecord = JSON.parse(myRecordset[key]);
                    }
                    catch(e) {
                        currentRecord = 1;  // To avoid JSON.Parse() returning an error if value variable is not valid JSON
                    }
                    
                    // Parse SQL Insert query
                    queryString = LMD_utilities.parseJSONIntoSQL(currentRecord, currentRecord.database, currentRecord.table, ['table', 'database']);
                    
                    // Send record to database via AJAX
                    var myData = { 'queryString': queryString, 'rKey': key, 'transaction': 0, 'queryDebugging': queryDebugging } ;

                    // Send AJAX request
                    $.ajax({
                        type: "POST",
                        url: "/LastMileData/php/scripts/ajaxSendQuery.php",
                        data: myData,
                        dataType: "json",
                        success: function(data) {

                            // Change ajaxBlock to GREEN
                            $("#ajaxBlock_" + data.rKeyAJAX).css('background-color','#5CB85C');

                            // Log success; remove record from myRecordset; increment AJAX success counter
                            console.log('ajax success!');
                            delete myRecordset[data.rKeyAJAX];
                            numAjax_success++;

                        },
                        error: function(request, status, error) {

                            // Change ajaxBlock to RED
                            $("#ajaxBlock_" + JSON.parse(request.responseText).rKeyAJAX).css('background-color','#C12E2A');

                            // Log failure; increment AJAX failure counter
                            console.log('ajax error :/');
                            console.log(request);
                            numAjax_fail++;
                        }
                    });
                }
                
                var myTimer = setInterval(function(){
                    
                    if(numRecords == numAjax_success + numAjax_fail) {
                        
                        // !!!!! Try doing this without deleting file !!!!!
                        LMD_fileSystemHelper.deleteFile('data.lmd',function(){
                            
                            LMD_fileSystemHelper.createOrOverwriteFile('data.lmd', JSON.stringify(myRecordset), function(){
                                
                                if (numRecords == numAjax_success) {
                                    // Display success message
                                    $('#modal_sendRecords_text').html('Success. All records were sent to the MySQL database.');
                                }
                                
                                else if (numRecords == numAjax_fail) {
                                    // Display "full error" message
                                    $('#modal_sendRecords_text').html('No records were successfully sent.<br>Please try again later.');
                                }
                                
                                else if (numRecords == numAjax_success + numAjax_fail) {
                                    // Display "partial error" message
                                    $('#modal_sendRecords_text').html('Only some records were sent successfully.<br>Please try again to send the remaining records.');
                                }
                                
                                else {
                                    // Display "unknown error" message
                                    $('#modal_sendRecords_text').html('An unknown error occurred.<br>Please contact the database manager for support');
                                }
                                
                                // Update DOM
                                $('#modal_sendRecords_ajaxLoadIcon').slideUp(500, function(){
                                    $('#modal_sendRecords_text, #modal_sendRecords_close').slideDown(500);
                                });
                                
                            });
                            
                        });
                        
                        clearInterval(myTimer);
                    }
                    
                },500);
                
            });
            
        }
        
    });
    
}