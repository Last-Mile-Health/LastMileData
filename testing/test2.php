<!DOCTYPE html>

<!--<html manifest="/LastMileData/src/lastmiledata.appcache">-->
<html>
    
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='robots' content='noindex'>
        <link rel='icon' type='image/png' href='/LastMileData/res/lmd_icon_v20140916.png'>
        <title>Last Mile Data</title>
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/res/page_deqa.css"  type="text/css" />
        
        <!-- !!!!! RELEVANT 1: START !!!!! -->
        <script src="//tinymce.cachefly.net/4.1/tinymce.min.js"></script>
        <!-- !!!!! RELEVANT 1: END !!!!! -->
        
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        <script src="/LastMileData/src/js/LMD_fileSystemHelper.js"></script>
        <script src="/LastMileData/src/js/LMD_accessControl.js"></script>
        <script src="/LastMileData/src/js/deqa_v20141015.js"></script>
        <script src="/LastMileData/src/js/formHelper.js"></script>
        
        <script type="text/javascript">
//        $(document).ready(function(){
            
            tinymce.init({
                selector: "textarea",
                plugins: [
                    "advlist autolink lists link image charmap preview anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | link image"
            });
            
//            $('#tinymce').change(function(){
//                alert('hey');
//            });
//        });
        </script>
        
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container">
            <div class="jumbotron">
                <h1>Data Entry / Quality Assurance</h1>
                <p>This section is meant to be used by our M&E staff. This section can be accessed offline when no internet connection is present.</p>
                <h4><b>App version: 00488</b></h4>
            </div>
            
            <!-- Current forms -->
            <div id="currentVersions_div" class="row">
                <div class="col-md-4" id="DE_forms">
                    <h2>Data Entry</h2>
                    <p>Click on one of the buttons below to perform data entry.</p>
                    <p><a href="/LastMileData/src/forms/0_testDE.html" class="btn btn-success">TEST FORM &nbsp;&nbsp;<b>[TST.01]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_reg02_registration.html" class="btn btn-success">FHW: Registration &nbsp;&nbsp;<b>[REG.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_kpi03_kpiassessment.html" class="btn btn-success">FHW: KPI Assessment &nbsp;&nbsp;<b>[KPI.03]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_ref02_referral.html" class="btn btn-success">FHW: Referral &nbsp;&nbsp;<b>[REF.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_bbi02_bigbellyinitial.html" class="btn btn-success">FHW: Big Belly (initial) &nbsp;&nbsp;<b>[BBI.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_bbf02_bigbellyfollowup.html" class="btn btn-success">FHW: Big Belly (follow-up) &nbsp;&nbsp;<b>[BBF.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_pni02_postnatalinitial.html" class="btn btn-success">FHW: Postnatal (initial) &nbsp;&nbsp;<b>[PNI.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_pnf02_postnatalfollowup.html" class="btn btn-success">FHW: Postnatal (follow-up) &nbsp;&nbsp;<b>[PNF.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_bdm02_movements.html" class="btn btn-success">FHW: Births, Deaths, Movements &nbsp;&nbsp;<b>[BDM.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_sch03_sickchild.html" class="btn btn-success">FHW: Sick Child &nbsp;&nbsp;<b>[SCH.03]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_ecm02_ebolacasemanagement.html" class="btn btn-success">FHW: Ebola Case Management &nbsp;&nbsp;<b>[ECM.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_ect03_ebolacontacttracing.html" class="btn btn-success">FHW: Ebola Contact Tracing &nbsp;&nbsp;<b>[ECT.03]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_ees03_ebolaeducationscreening.html" class="btn btn-success">FHW: Ebola Education + Screening Ledger &nbsp;&nbsp;<b>[EES.03]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_mat01_malariaassessment.html" class="btn btn-success">FHW: Malaria Assessment Tool &nbsp;&nbsp;<b>[MAT.01]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fhw_sst05_sicknessscreeningtool.html" class="btn btn-success">FHW: Sickness Screening Tool &nbsp;&nbsp;<b>[SST.05]</b></a></p>
                    <p><a href="/LastMileData/src/forms/fac_msh01_mesh.html" class="btn btn-success">Facility: IPC MESH Tool &nbsp;&nbsp;<b>[MSH.01]</b></a></p>
                    <p><a href="/LastMileData/src/forms/prg_trl02_trainingledger.html" class="btn btn-success">Program: Training Ledger &nbsp;&nbsp;<b>[TRL.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms/prg_chv01_gchvquestionnaire.html" class="btn btn-success">Program: gCHV Questionnaire &nbsp;&nbsp;<b>[CHV.01]</b></a></p>
                </div>
                <div class="col-md-4" id="QA_forms">
                    <h2>Quality Assurance</h2>
                    <p>Click on one of the buttons below to perform data QA.</p>
                    <p><a id="qa_TST" href="#" class="btn btn-primary">TEST FORM &nbsp;&nbsp;<b>[TST.01]</b></a></p>
                    <p><a id="qa_REG_02" href="#" class="btn btn-primary">FHW: Registration &nbsp;&nbsp;<b>[REG.02]</b></a></p>
                    <p><a id="qa_KPI_03" href="#" class="btn btn-primary">FHW: KPI Assessment &nbsp;&nbsp;<b>[KPI.03]</b></a></p>
                    <p><a id="qa_REF_02" href="#" class="btn btn-primary">FHW: Referral &nbsp;&nbsp;<b>[REF.02]</b></a></p>
                    <p><a id="qa_BBI_02" href="#" class="btn btn-primary">FHW: Big Belly (initial) &nbsp;&nbsp;<b>[BBI.02]</b></a></p>
                    <p><a id="qa_BBF_02" href="#" class="btn btn-primary">FHW: Big Belly (follow-up) &nbsp;&nbsp;<b>[BBF.02]</b></a></p>
                    <p><a id="qa_PNI_02" href="#" class="btn btn-primary">FHW: Postnatal (initial) &nbsp;&nbsp;<b>[PNI.02]</b></a></p>
                    <p><a id="qa_PNF_02" href="#" class="btn btn-primary">FHW: Postnatal (follow-up) &nbsp;&nbsp;<b>[PNF.02]</b></a></p>
                    <p><a id="qa_BDM_02" href="#" class="btn btn-primary">FHW: Births, Deaths, Movements &nbsp;&nbsp;<b>[BDM.02]</b></a></p>
                    <p><a id="qa_SCH_03" href="#" class="btn btn-primary">FHW: Sick Child &nbsp;&nbsp;<b>[SCH.03]</b></a></p>
                    <p><a id="qa_ECM_02" href="#" class="btn btn-primary">FHW: Ebola Case Management &nbsp;&nbsp;<b>[ECM.02]</b></a></p>
                    <p><a id="qa_ECT_03" href="#" class="btn btn-primary">FHW: Ebola Contact Tracing &nbsp;&nbsp;<b>[ECT.03]</b></a></p>
                    <p><a id="qa_EES_03" href="#" class="btn btn-primary">FHW: Ebola Education + Screening Ledger &nbsp;&nbsp;<b>[EES.03]</b></a></p>
                    <p><a id="qa_MAT_01" href="#" class="btn btn-primary">FHW: Malaria Assessment Tool &nbsp;&nbsp;<b>[MAT.01]</b></a></p>
                    <p><a id="qa_SST_05" href="#" class="btn btn-primary">FHW: Sickness Screening Tool &nbsp;&nbsp;<b>[SST.05]</b></a></p>
                    <p><a id="qa_MSH_01" href="#" class="btn btn-primary">Facility: IPC MESH Tool &nbsp;&nbsp;<b>[MSH.01]</b></a></p>
                    <p><a id="qa_TRL_02" href="#" class="btn btn-primary">Program: Training Ledger &nbsp;&nbsp;<b>[TRL.02]</b></a></p>
                    <p><a id="qa_CHV_01" href="#" class="btn btn-primary">Program: gCHV Questionnaire &nbsp;&nbsp;<b>[CHV.01]</b></a></p>
                </div>
                <div class="col-md-4">
                    <h2>Database Management</h2>
                    <p>Functions to be used only by the database manager.</p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_content-edit" title="Send records to the MySQL Database. Requires an active internet connection. This should ideally be done after data quality assurance has been performed.">Edit content</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_sendRecords" title="Send records to the MySQL Database. Requires an active internet connection. This should ideally be done after data quality assurance has been performed.">Send Records</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_uploadLMD" title="Upload data file (.LMD)">Upload data file (.LMD)</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_refreshData" title="Refresh system data. Should be done at least once a month. Requires an active internet connection.">Refresh system data</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_downloadDataFile" title="Click here to download all data entry forms onto local disk. This is useful if there is no internet connection, and you need to transfer the files on a thumb drive to someone else's computer to send to the MySQL databasse.">Download data file</a></p>
                    <p><a href="/LastMileData/src/pages/viewLocalRecords.html" class="btn btn-danger" title="View all locally-stored records.">View local records</a></p>
                    <p><a href="/LastMileData/src/forms/util_memberIDAssignmentTool.html" class="btn btn-danger" title="Generate Member ID Assignment Tools.">Member ID Assignment Tool</a></p>
                    <p><a href="/LastMileData/src/forms/util_householdIDAssignmentTool.html" class="btn btn-danger" title="Generate Household ID Assignment Tools.">Household ID Assignment Tool</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_eesLedger" title="Generate the 'FHW EES Ledger'">Generate "FHW EES Ledger"</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modal_populationReport" title="Generate the 'FHW Population Report'">Generate "FHW Population Report"</a></p>
                </div>
            </div>
            
            <!-- Display archived forms -->
            <div class="row">
                <div class="col-md-8" style="text-align:center">
                    <hr>
                    <a id="oldVersions_link" style="cursor:pointer">Click to use older form versions</a>
                    <a id="currentVersions_link" style="cursor:pointer; display:none">Click to use current form versions</a>
                    <hr>
                </div>
            </div>
            
            <!-- Archived forms -->
            <div id="oldVersions_div" class="row" style="display:none">
                <div class="col-md-4">
                    <h2>Data Entry</h2>
                    <p>Click on one of the buttons below to perform data entry.</p>
                    <p><a href="/LastMileData/src/forms/fhw_ees02_ebolaeducationscreening.html" class="btn btn-success">FHW: Ebola Education + Screening Ledger &nbsp;&nbsp;<b>[EES.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms_old/fhw_esc02_ebolascreening.html" class="btn btn-success">FHW: Ebola Screening Tool &nbsp;&nbsp;<b>[ESC.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms_old/fhw_esc03_ebolascreening.html" class="btn btn-success">FHW: Ebola Screening Tool &nbsp;&nbsp;<b>[ESC.03]</b></a></p>
                    <p><a href="/LastMileData/src/forms_old/fhw_kpi02_kpiassessment.html" class="btn btn-success">FHW: KPI Assessment &nbsp;&nbsp;<b>[KPI.02]</b></a></p>
                    <p><a href="/LastMileData/src/forms_old/fhw_sst04_ebolascreening.html" class="btn btn-success">FHW: Sickness Screening Tool &nbsp;&nbsp;<b>[SST.04]</b></a></p>
                    <p><a href="/LastMileData/src/forms_old/prg_trl01_trainingledger.html" class="btn btn-success">Program: Training Ledger &nbsp;&nbsp;<b>[TRL.01]</b></a></p>
                    <br><br>
                </div>
                <div class="col-md-4"> <!-- !!!!! modify; cross-check this code with DEQA.js !!!!! -->
                    <h2>Quality Assurance</h2>
                    <p>Click on one of the buttons below to perform data QA.</p>
                    <p><a id="qa_EES_02" href="#" class="btn btn-primary">FHW: Ebola Education + Screening Ledger &nbsp;&nbsp;<b>[EES.02]</b></a></p>
                    <p><a id="qa_ESC_02" href="#" class="btn btn-primary">FHW: Ebola Screening Tool &nbsp;&nbsp;<b>[ESC.02]</b></a></p>
                    <p><a id="qa_ESC_03" href="#" class="btn btn-primary">FHW: Ebola Screening Tool &nbsp;&nbsp;<b>[ESC.03]</b></a></p>
                    <p><a id="qa_KPI_02" href="#" class="btn btn-primary">FHW: Ebola Screening Tool &nbsp;&nbsp;<b>[KPI.02]</b></a></p>
                    <p><a id="qa_SST_04" href="#" class="btn btn-primary">FHW: Sickness Screening Tool &nbsp;&nbsp;<b>[SST.04]</b></a></p>
                    <p><a id="qa_TRL_01" href="#" class="btn btn-primary">Program: Training Ledger &nbsp;&nbsp;<b>[TRL.01]</b></a></p>
                    <br><br>
                </div>
            </div>
        </div>        
        
        
        
        <!-- MODAL START: "DEQA LOGIN" -->
        <div id="modal_deqaLogin" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Enter M&E Username/Password</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="modal_deqaLogin_username" type="text" class="form-control input-lg" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input id="modal_deqaLogin_password" type="password" class="form-control input-lg" placeholder="Password">
                        </div>
                        <div id="modal_deqaLogin_incorrectLogin" class="form-group" style="display:none">
                            <span style="color:red">Incorrect login credentials. Please try again.</span>
                        </div>
                        <div class="form-group">
                            <button id="modal_deqaLogin_submit" class="btn btn-success btn-lg btn-block">Submit</button>
                            <span>Please email <a href="mailto:avi@lastmilehealth.org?subject=Access to M&E Section of LastMileData.org">Avi Kenny</a> to request access to this section.</span>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>        
        <!-- MODAL END: "DEQA LOGIN" -->
        
        
        
        <!-- MODAL START: "INITIALIZE" -->
        <div id="modal_initialize" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Initializing application</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="modal_initialize_text">Initializing the Last Mile Data application. Downloading files now...</h3>
                            <div id="modal_initialize_progress"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>

            </div>
        </div>        
        <!-- MODAL END: "INITIALIZE" -->
        
        
        
        <!-- MODAL CONTENT-EDIT: START -->
        <div id="modal_content-edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Send Records</h1>
                    </div>
                    <div class="modal-body">
                        
                        <div class="text-center">
                            <form method="post" action="somepage">
                                <textarea id='edit' name="content" style="width:100%"></textarea>
                            </form></body>
                        </div>
                        
                        <div id="modal_sendRecords_close">
                            <button class="btn btn-success btn-lg" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL END: "INITIALIZE" -->
        
        
        
        <!-- !!!!! temporary code; to be replaced with mHealth !!!!! -->
        <!-- MODAL START: "EES LEDGER" -->
        <div id="modal_eesLedger" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Generate the "FHW EES Ledger"</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="modal_eesLedger_fhwID" type="text" class="form-control input-lg" placeholder="FHW ID">
                        </div>
                        <div class="form-group">
                            <input id="modal_eesLedger_fhwName" type="text" class="form-control input-lg" placeholder="FHW Name">
                        </div>
                        <div class="form-group">
                            <button id="modal_eesLedger_submit" class="btn btn-success btn-lg btn-block">Submit</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>

            </div>
        </div>        
        <!-- MODAL START: "EES LEDGER" -->
        
        
        
        <!-- MODAL START: "POPULATION REPORT" -->
        <div id="modal_populationReport" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Generate "FHW Population Report"</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="modal_populationReport_fhwID" type="text" class="form-control input-lg" placeholder="FHW ID">
                        </div>
                        <div class="form-group">
                            <input id="modal_populationReport_fhwName" type="text" class="form-control input-lg" placeholder="FHW Name">
                        </div>
                        <div class="form-group">
                            <button id="modal_populationReport_submit" class="btn btn-success btn-lg btn-block">Submit</button>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>

            </div>
        </div>        
        <!-- MODAL START: "POPULATION REPORT" -->
        
        
        
        <!-- MODAL START: "APPCACHE REFRESH" -->
        <div id="modal_appcacheRefresh" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Refreshing Application</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="modal_appcacheRefresh_text">A new version of the Last Mile Data application is available. Downloading files now...</h3>
                            <div id="modal_appcacheRefresh_progress"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL END: "APPCACHE REFRESH" -->
        
        
        
        <!-- MODAL START: "REFRESH DATA" -->
        <div id="modal_refreshData" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Refresh system data</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="modal_refreshData_text">Refreshing...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL END: "REFRESH DATA" -->
        
        
        
        <!-- MODAL START: "DOWNLOAD DATA FILE" -->
        <div id="modal_downloadDataFile" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Download data file</h1>
                    </div>
                    <div class="modal-body">
                        <div id="modal_downloadDataFile_prompt" class="form-group text-center">
                            <a id="modal_downloadDataFile_submit" class="btn btn-success btn-lg btn-block">Download</a>
                            <br>
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>
                        <div id="modal_downloadDataFile_downloading" class="form-group text-center" style="display:none">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="modal_downloadDataFile_text">Downloading data file...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL END: "DOWNLOAD DATA FILE" -->
        
        
        
        <!-- MODAL START: "UPLOAD LMD DATA FILES" -->
        <div id="modal_uploadLMD" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Upload data files</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <div id="modal_uploadLMD_formContent">
                                <form id="modal_uploadLMD_form">
                                    <div class="form-group text-center">
                                        <input class="form-control input-lg" type="file" id="modal_uploadLMD_fileInput" multiple><br>
                                        <p id="modal_uploadLMD_error" class="form-group" style="display:none; color:red"></p>
                                        <a id="modal_uploadLMD_submit" class="btn btn-success btn-lg btn-block">Upload</a>
                                        <br>
                                        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                    </div>
                                </form>
                            </div>
                            <div id="modal_uploadLMD_status" style='display:none'>
                                <img src='/LastMileData/res/ajax-loader_v20140916.gif'><br>
                                <h3 id='modal_uploadLMD_message'>Uploading and merging data file...</h3>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL END: "UPLOAD LMD DATA FILES" -->
        
        
        
        <!-- MODAL START: "SEND RECORDS" -->
        <div id="modal_sendRecords" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Send Records</h1>
                    </div>
                    <div class="modal-body">
                        
                        <div id="modal_sendRecords_ajaxLoadIcon" class="center-block text-center">
                            <img src='/LastMileData/res/ajax-loader_v20140916.gif'>
                            <br><br>
                        </div>
                        
                        <div id="modal_sendRecords_ajaxInner"></div>
                        
                        <div class="text-center">
                            <h3 id="modal_sendRecords_text">Are you sure you want to send all<br>current records to the database?<br><br></h3>
                            <div id='modal_sendRecords_buttons'>
                                <a id="modal_sendRecords_submit" class="btn btn-success btn-lg">Yes, send records</a>
                                &nbsp;
                                <button class="btn btn-success btn-lg" data-dismiss="modal" aria-hidden="true">No, go back</button>
                            </div>
                        </div>
                        
                        <div id="modal_sendRecords_close">
                            <button class="btn btn-success btn-lg" data-dismiss="modal" aria-hidden="true">Close</button>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL END: "SEND RECORDS" -->
        
        
        
        <!-- MODAL START: "QUALITY ASSURANCE" -->
        <!-- !!!!! fix all of these IDs to match standard prefixed format !!!!! -->
        <div id="modal_QA" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h1 class="text-center">Quality Assurance</h1>
                    </div>
                    <div class="modal-body">
                        <h3 id="qaFormName" class="text-center">Form name</h3>
                        <form class="form col-md-12 center-block">
                            <h3 class="text-center"></h3>
                            <div id="modal_QA_pKeyDiv1" class="form-group">
                                <label for="modal_QA_pKey1">pKey 1:</label>
                                <input id="modal_QA_pKey1" class="form-control input-lg">
                            </div>
                            <div id="modal_QA_pKeyDiv2" class="form-group">
                                <label for="modal_QA_pKey2">pKey 2:</label>
                                <input id="modal_QA_pKey2" class="form-control input-lg">
                            </div>
                            <div id="modal_QA_pKeyDiv3" class="form-group">
                                <label for="modal_QA_pKey3">pKey 3:</label>
                                <input id="modal_QA_pKey3" class="form-control input-lg">
                            </div>
                            <div id="modal_QA_pKeyDiv4" class="form-group">
                                <label for="modal_QA_pKey4">pKey 4:</label>
                                <input id="modal_QA_pKey4" class="form-control input-lg">
                            </div>
                            <div id="qaNoMatch" class="form-group" style="display:none">
                                <span style="color:red">No match was found in the current record set.</span>
                            </div>
                            <div class="form-group">
                                <a id="modal_QA_submit" class="btn btn-success btn-lg btn-block">Submit</a>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>    
                    </div>
                </div>
            </div>
        </div>        
        <!-- MODAL END: "QUALITY ASSURANCE" -->
        
        
        
    </body>
    
</html>