<?php
    session_start();
    if( !isset($_SESSION['username']) ) { Header('Location:/LastMileData/'); }
?>

<!DOCTYPE html>
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
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        <script src="/LastMileData/src/js/changePassword_v20140916.js"></script>
        <script src="/LastMileData/src/js/deqa_v20141015.js"></script>
        
        <style>
            .btn { width:100%; }
        </style>
        
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container">
            <div class="jumbotron">
                <h1>Program Tools + Reports</h1>
                <p>This section is meant to be used by our M&E staff. This section requires an internet connection to work.</p>
            </div>
            
            <!-- Header -->
            <div class="row">
                <div class="col-md-4">
                    <h2>Tools</h2>
                    <p>Tools for our FHWs to use.</p>
                </div>
                <div class="col-md-4">
                    <h2>Reports</h2>
                    <p>Data reports for use by our program staff (FHW Leaders, Clinical Mentors, M&E Officers).</p>
                </div>
                <div class="col-md-4">
                    <h2>Program Data</h2>
                    <p>Use these buttons to update data related to our program.</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <p><a href="/LastMileData/src/forms/util_memberIDAssignmentList.html" class="btn btn-success" title='Click here to generate a "FHW: Member ID Assignment List" for an FHW'>Generate "Member ID Assignment List"</a></p>
                    <p><a href="/LastMileData/src/forms/util_householdIDAssignmentList.html" class="btn btn-success" title='Click here to generate a "FHW: Household ID Assignment List" for an FHW'>Generate "Household ID Assignment List"</a></p>
                </div>
                <div class="col-md-4">          <!-- !!!!! change IDs below to allow for version control !!!!! -->
                    <p><a id="qa_TST" href="#" class="btn btn-primary">Population report</a></p>
                </div>
                <div class="col-md-4">
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#sendRecordsModal" title="Click here to send records to the MySQL Database. Requires an active internet connection. This should ideally be done after data quality assurance has been performed.">Send Records</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#refreshDataModal" title="Click here to refresh system data. Should be done at least once a month. Requires an active internet connection.">Refresh system data</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#downloadDataFileModal" title="Click here to download all data entry forms onto local disk. This is useful if there is no internet connection, and you need to transfer the files on a thumb drive to someone else's computer to send to the MySQL databasse.">Download data file</a></p>
                    <p><a href="#" class="btn btn-danger" data-toggle="modal" data-target="#uploadDataFileModal" title="Use this to upload a data file from someone else's M&E laptop. Does not requires an internet connection, although an internet connection is required to send files to the MySQL database.">Upload data file</a></p>
                </div>
            </div>
            
        </div>
        
        
        
        <!-- START "DEQA LOGIN" (MODAL) -->
        <div id="deqaLogin" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Enter M&E Username/Password</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="username" type="text" class="form-control input-lg" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input id="password" type="password" class="form-control input-lg" placeholder="Password">
                        </div>
                        <div id="incorrectLogin" class="form-group" style="display:none">
                            <span style="color:red">Incorrect login credentials. Please try again.</span>
                        </div>
                        <div class="form-group">
                            <button id="deqaLoginSubmit" class="btn btn-success btn-lg btn-block">Submit</button>
                            <span>Please email <a href="mailto:avi@lastmilehealth.org?subject=Access to M&E Section of LastMileData.org">Avi Kenny</a> to request access to this section.</span>
                        </div>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>        
        <!-- END "DEQA LOGIN" (MODAL) -->
        
        
        
        <!-- START "INITIALIZE" (MODAL) -->
        <div id="deqaInitialize" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Initializing application</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="initialize_text">Initializing...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>

            </div>
        </div>        
        <!-- END "INITIALIZE" (MODAL) -->
        
        
        
        <!-- START "APPCACHE REFRESH" (MODAL) -->
        <div id="appCacheRefresh" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Refreshing Application</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="initialize_text">A new version of Last Mile Data is available. Refreshing now...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>

            </div>
        </div>        
        <!-- END "APPCACHE REFRESH" (MODAL) -->
        
        
        
        <!-- START: "REFRESH DATA" (MODAL) -->
        <div id="refreshDataModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Refresh system data</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="refreshData_text">Refreshing...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- END: "REFRESH DATA" (MODAL) -->
        
        
        
        <!-- START: "DOWNLOAD DATA FILE" (MODAL) -->
        <div id="downloadDataFileModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Download data file</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group text-center">
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif">
                            <h3 id="downloadDataFile_text">Downloading data file...</h3>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- END: "DOWNLOAD DATA FILE" (MODAL) -->
        
        
        
        <!-- START: "UPLOAD DATA FILE" (MODAL) -->
        <div id="uploadDataFileModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <!-- !!!!! build this out !!!!! -->
        </div>
        <!-- END: "UPLOAD DATA FILE" (MODAL) -->
        
        
        
        <!-- START: "SEND RECORDS" (MODAL) -->
        <div id="sendRecordsModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="text-center">Send Records</h1>
                    </div>
                    <div class="modal-body">
                        <form class="form col-md-12 center-block">
                            <div class="form-group text-center">
                                <h3 id="sendRecords_text">Are you sure you want to send all current records to the database?</h3>
                                <a id="sendRecords" class="btn btn-success btn-lg">Yes, send records</a>
                                &nbsp;
                                <button id="cancelModal_2" class="btn btn-success btn-lg" data-dismiss="modal" aria-hidden="true">No, go back</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>
        <!-- END: "SEND RECORDS" (MODAL) -->
        
        
        
        <!-- START: "QUALITY ASSURANCE" (MODAL) -->
        <div id="qaModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h1 class="text-center">Quality Assurance</h1>
                    </div>
                    <div class="modal-body">
                        <h3 id="qaFormName" class="text-center">Form name</h3>
                        <form class="form col-md-12 center-block">
                            <h3 id="modal_text" class="text-center"></h3>
                            <div id="pKeyDiv1" class="form-group">
                                <label for="pKey1">pKey 1:</label>
                                <input id="pKey1" class="form-control input-lg">
                            </div>
                            <div id="pKeyDiv2" class="form-group">
                                <label for="pKey2">pKey 2:</label>
                                <input id="pKey2" class="form-control input-lg">
                            </div>
                            <div id="pKeyDiv3" class="form-group">
                                <label for="pKey3">pKey 3:</label>
                                <input id="pKey3" class="form-control input-lg">
                            </div>
                            <div id="qaNoMatch" class="form-group" style="display:none">
                                <span style="color:red">No match was found in the current record set.</span>
                            </div>
                            <div class="form-group">
                                <a id="qaModalSubmit" class="btn btn-success btn-lg btn-block">Submit</a>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button id="cancelModal_3" class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>    
                    </div>
                </div>
            </div>
        </div>        
        <!-- END: "QUALITY ASSURANCE" (MODAL) -->
        
        
        
    </body>
    
</html>