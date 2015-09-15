// Dependencies: jQuery, jQueryUI, LMD_fileSystemHelpers.js

$(document).ready(function(){


    // CLICK HANDLER: Send Records
    $('#modal_sendRecords_submit').click(function(){
        
        // Manipulate DOM
        $('#modal_sendRecords_buttons').slideUp(600);
        $('#modal_sendRecords_text').slideUp(800, function(){
            sendRecordsAJAX();
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
                        
                        // Construct the download link and programmatically click the link
                        var textToWrite = result;
                        var textFileAsBlob = new Blob([textToWrite], {type: 'text/plain'});
                        var today = new Date();
                        var dd = today.getDate();
                        var mm = today.getMonth() + 1;
                        var yyyy = today.getFullYear();
                        var fileNameToSaveAs = "data_" + yyyy + "-" + mm + "-" + dd + ".lmd";
                        var downloadLink = document.createElement("a");
                        downloadLink.download = fileNameToSaveAs;
                        downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
                        downloadLink.click();
                        
                        // Back up the file to the "dataFileBackups" directory
                        LMD_fileSystemHelper.createDirectory('dataFileBackups', function(){
                            // !!!!! create an interface to access these backups !!!!!
                            LMD_fileSystemHelper.createOrOverwriteFile('/dataFileBackups/downloadDataFileBackup_' + fileNameToSaveAs, textToWrite, function(){
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
                    
                    if (['xml','lmd'].indexOf(sFileExtension.toLowerCase()) === -1) {
                        // Incorrect file extension (not ".lmd" or ".xml")
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
                                    // !!!!! Need to build an error catcher for invalid XML !!!!!
                                    var $myJQXML = $(myJQXML);

                                    // Extract database schema name
                                    var $dbTag = $myJQXML.find('*').filter(function(){return /^LMD\-DATABASE/i.test(this.nodeName);}).remove();
                                    var dbName = $dbTag[0].textContent;

                                    // Extract sub-records (aka. "repeating groups")
                                    var $subRecords = $myJQXML.find('*').filter(function(){return /^LMD\-RPT/i.test(this.nodeName);}).remove();

                                    // Process main XML file ($myJQXML)
                                    var xmlKey, xmlValue, chkArray, pullString = "";
                                    var xmlRecord = { database:dbName };
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
            // !!!!! rewrite this using "$.when" !!!!!
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
            
            
        } // !!!!! if (!anyErrors) {
    });



    // QA Click handlers
    $('#qa_TST').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/0_testDE.html",
            qaFormName: "TEST FORM",
            pKey1_name: "var1",
            pKey2_name: "var6",
            pKey1_label: "Var 1",
            pKey2_label: "Var 6",
            pKey_date: "pKey2"
        });
    });
    $('#qa_REG_03').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_reg03_registration.html",
            qaFormName: "CHW: Registration",
            pKey1_name: "memberID_1",
            pKey2_name: "communityID",
            pKey1_label: "First Member ID",
            pKey2_label: "Community ID"
        });
    });
    $('#qa_REG_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms_old/fhw_reg02_registration.html",
            qaFormName: "FHW: Registration",
            pKey1_name: "memID_1",
            pKey1_label: "First Member ID"
        });
    });
    $('#qa_KPI_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms_old/fhw_kpi02_kpiassessment.html",
            qaFormName: "FHW: KPI Assessment",
            pKey1_name: "memberID",
            pKey2_name: "visitDate",
            pKey1_label: "Woman member ID",
            pKey2_label: "Visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_KPI_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_kpi03_kpiassessment.html",
            qaFormName: "FHW: KPI Assessment",
            pKey1_name: "memberID",
            pKey2_name: "visitDate",
            pKey1_label: "Woman member ID",
            pKey2_label: "Visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_REF_02').click(function() {
        launchQAModal({
            // !!!!!
        });
    });
    $('#qa_BBI_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_bbi02_bigbellyinitial.html",
            qaFormName: "FHW: Big Belly (initial)",
            pKey1_name: "memberID",
            pKey2_name: "visitDate",
            pKey1_label: "Member ID",
            pKey2_label: "Visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_BBF_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_bbf02_bigbellyfollowup.html",
            qaFormName: "FHW: Big Belly (follow-up)",
            pKey1_name: "memberID",
            pKey2_name: "visitDate",
            pKey1_label: "Member ID",
            pKey2_label: "Visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_PNI_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_pni02_postnatalinitial.html",
            qaFormName: "FHW: Postnatal (initial)",
            // !!!!!
        });
    });
    $('#qa_PNF_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_pnf02_postnatalfollowup.html",
            qaFormName: "FHW: Postnatal (follow-up)",
            // !!!!!
        });
    });
    $('#qa_BDM_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_bdm02_movements.html",
            qaFormName: "FHW: Births, Deaths, Movements",
            pKey1_name: "fhwID",
            pKey2_name: "bdmDate",
            pKey1_label: "FHW ID",
            pKey2_label: "Form date",
            pKey_date: "pKey2"
        });
    });
        $('#qa_BDM_03').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_bdm03_movements.html",
            qaFormName: "CHW: Births, Deaths, Movements",
            pKey1_name: "chwID",
            pKey2_name: "visitDate",
            pKey1_label: "CHW ID",
            pKey2_label: "Form date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_SCH_03').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_sch03_sickchild.html",
            qaFormName: "FHW: Sick Child",
            pKey1_name: "memberID",
            pKey2_name: "visitDate",
            pKey1_label: "Member ID",
            pKey2_label: "Visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_ECM_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_ecm02_ebolacasemanagement.html",
            qaFormName: "FHW: Ebola Case Management",
            // !!!!!
        });
    });
    $('#qa_ECT_01').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_ect01_ebolacontacttracing.html",
            qaFormName: "FHW: Ebola Contact Tracing",
            // !!!!!
        });
    });
    $('#qa_EES_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms_old/fhw_ees02_ebolaeducationscreening.html",
            qaFormName: "FHW: Ebola Education + Screening Ledger",
            pKey1_name: "memberID_1",
            pKey2_name: "visitDate_1",
            pKey1_label: "First member ID",
            pKey2_label: "First visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_EES_03').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_ees03_ebolaeducationscreening.html",
            qaFormName: "FHW: Ebola Education + Screening Ledger",
            pKey1_name: "memberID_1",
            pKey2_name: "visitDate_1",
            pKey1_label: "First member ID",
            pKey2_label: "First visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_MAT_01').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_mat01_malariaassessment.html",
            qaFormName: "FHW: Malaria Assessment Tool",
            pKey1_name: "memberID",
            pKey2_name: "visitDate",
            pKey1_label: "Member ID",
            pKey2_label: "Visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_SST_05').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_sst05_sicknessscreeningtool.html",
            qaFormName: "FHW: Sickness Screening Tool",
            pKey1_name: "memberID",
            pKey2_name: "visitDate",
            pKey1_label: "Member ID",
            pKey2_label: "Visit date",
            pKey_date: "pKey2"
        });
    });
    $('#qa_ESC_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_esc02_ebolascreening.html",
            qaFormName: "FHW: Ebola Screening Tool",
            // !!!!!
        });
    });
    $('#qa_ESC_03').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_esc03_ebolascreening.html",
            qaFormName: "FHW: Ebola Screening Tool",
            // !!!!!
        });
    });
    $('#qa_TRL_01').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms_old/prg_trl01_trainingledger.html",
            qaFormName: "Program: Training Ledger",
            pKey1_name: "trainingName",
            pKey2_name: "trainingDate",
            pKey3_name: "firstName_1",
            pKey4_name: "lastName_1",
            pKey1_label: "Training name",
            pKey2_label: "Training date",
            pKey3_label: "First name (1)",
            pKey4_label: "Last name (1)",
            pKey_date: "pKey2"
        });
    });
    $('#qa_TRL_02').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/prg_trl02_trainingledger.html",
            qaFormName: "Program: Training Ledger",
            pKey1_name: "trainingName",
            pKey2_name: "trainingDate",
            pKey3_name: "firstName_1",
            pKey4_name: "lastName_1",
            pKey1_label: "Training name",
            pKey2_label: "Training date",
            pKey3_label: "First name (1)",
            pKey4_label: "Last name (1)",
            pKey_date: "pKey2"
        });
    });
    $('#qa_MSH_01').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fac_msh01_mesh.html",
            qaFormName: "Facility: IPC MESH Tool",
            pKey1_name: "facilityName",
            pKey2_name: "visitDate",
            pKey3_name: "county",
            pKey1_label: "Facility name",
            pKey2_label: "Visit date",
            pKey3_label: "County",
            pKey_date: "pKey2"
        });
    });
    $('#qa_SST_04').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/fhw_sst04_ebolascreening.html",
            qaFormName: "FHW: Sickness Screening Tool",
            // !!!!!
        });
    });
    $('#qa_CHV_01').click(function() {
        launchQAModal({
            targetForm: "/LastMileData/src/forms/prg_chv01_gchvquestionnaire.html",
            qaFormName: "Program: gCHV Questionnaire",
            pKey1_name: "gchvName",
            pKey2_name: "todayDate",
            pKey1_label: "gCHV name",
            pKey2_label: "Date",
            pKey_date: "pKey2"
        });
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
    
    
    
    // Refresh Data Modal
    // Run this script when modal_refreshData is shown
    // !!!!! Change this to automatically refresh system data once per week ?????
    $('#modal_refreshData').on('shown.bs.modal', function(e) {
        ajaxRefresh();
    });
    
    
    
    // CLICK HANDLER: versions
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



// !!!!! Need to create documentation on ODK naming convention scheme !!!!!
function processLMD(inputKey, inputValue) {
    
    var outputKey, outputValue;
    var fieldType = inputKey.slice(4,7);

// !!!!! rewrite this as a switch !!!!!

    if (fieldType === 'TAB') {
        outputKey = "table";
        outputValue = inputValue;
    } else if (fieldType === 'VAL') {
        outputKey = inputKey.slice(8);
        outputValue = inputValue;
    } else if (fieldType === 'DAT') { // !!!!! this is an artifact of old forms and should be removed !!!!!
        outputKey = inputKey.slice(8);
        outputValue = inputValue;
    } else if (fieldType === 'TIM') {
        outputKey = inputKey.slice(8);
        outputValue = inputValue.slice(11,19);
    } else if (fieldType === 'CHK') {
        outputKey = "CHK";
        outputValue = inputValue;
    }

    return {
        key: outputKey,
        value: outputValue
    };

}



function ajaxRefresh() {
    
    // Run script to update localStorage with data values
    $.ajax({
        url: "/LastMileData/src/php/refreshData.php",
    //            data: "myTestData=3",     // AVI: Potentially send data to filter localStorage based on user (e.g. only FHWs in one site)
        dataType: "json",
        success: function(data) {
            
            // !!!!! this code is WET (with refreshData.php); refactor !!!!!
            // !!!!! most of the data stored in localStorage should be stored in FileSystem (for scalability) !!!!!
            
            // Update localStorage
            localStorage.deqaUsers = JSON.stringify(data['deqaUsers']);
            localStorage.villages = JSON.stringify(data['villages']);
            localStorage.fhws = JSON.stringify(data['fhws']);
            localStorage.initialized = "yes";
            
            // Manipulate DOM
            $('#modal_refreshData_text').text('Data was successfully refreshed. Reloading page now...');
            $('#modal_initialize_text').text('Initialization successful. Reloading page now...');
            
            // Reload page
            setTimeout( function() {
                location.reload();
            }, 1500 );
            
        },
        error: function(request, status, error) {
            // Display error message
            $('#modal_refreshData_text').text('Data refresh was unsuccessful. Please try again later. Reloading page now...');
            $('#modal_initialize_text').text('Initialization was unsuccessful. Please try again later. Reloading page now...');
            
            // Redirect to home page
            setTimeout( function() {
                // !!!!! build in more comprehensive error handler; e.g., alert if no internet conncetion is present !!!!!
                location.reload();
            }, 1000 );
        }
    });
    
}



function showErrorMessage() {
    $('#modal_deqaLogin_incorrectLogin').slideDown(1000).delay(1000).slideUp(1000);
}



// WET with indicators.js
function addslashes( str ) {
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
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



// Return MySQL-formatted "DATETIME" string of current date/time
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function mysql_date(inputDate) {
    if (arguments.length === 0) {
        var myDate = new Date();
    } else {
        var myDate = new Date(inputDate);
    }
    return ( myDate.getUTCFullYear() + "-" + twoDigits(1 + myDate.getUTCMonth()) + "-" + twoDigits(myDate.getUTCDate()) );
}

// Pad numbers to two digits ( helper function for mysql_datetime() )
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}


function parseRecordIntoSQL(currentRecord) {
    
    // Set array of currentRecord properties that are not stored
    var notStored = ['table', 'database']; // !!!!! database property not yet being used; filter out anyways !!!!!
    
    // Begin query string
    var database = currentRecord.database || 'lastmile_db';
    var queryString = "INSERT INTO " + database + "." + currentRecord.table + " SET ";
    
    // Add key/value pairs to query string
    for(var key in currentRecord) {
        // if key isn't in "notStored" array, add it to query string
        if ( notStored.indexOf(key) == -1) {
            // !!!!! wrap key in `` characters !!!!!
            queryString += key + "='" + addslashes(currentRecord[key]) + "', ";
        }
    }
    
    // Finish query string segment
    queryString = queryString.slice(0,-2);
    queryString += ";";
    
    // Return query string
    return queryString;
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



function sendRecordsAJAX(){
    
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
                    queryString = parseRecordIntoSQL(currentRecord);

                    // Send record to database via AJAX
                    var myData = {'queryString': queryString, 'rKey': key, 'transaction': 0} ;

                    // Send AJAX request
                    $.ajax({
                        type: "POST",
                        url: "/LastMileData/src/php/ajaxSendQuery.php",
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

                            // Change ajaxBlock to GREEN
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