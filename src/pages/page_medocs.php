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
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        
        <!-- !!!!! Refactor into stylesheet !!!!! -->
        <style>
            
            table a {
                font-weight:bold;
            }
            
            .folderLink {
                background-color:#B19131;
            }
            
        </style>
        
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container">
            <div class="jumbotron">
                <h1>M&E Documents</h1>
                <p>This section of the site contains all key documents relevant to the M&E team, including program forms, strategy documents, and reports.</p>
                <p><b>Updated: Feb 21, 2015</b></p>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h3>Program Forms</h3>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFN1hPYXo1YzRIU0k/edit'>Facility: IPC Mesh Tool</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFMGRUYXNYT0VXQnM/edit'>FHW: Births, Deaths, Movements</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFbWRBcGlVTjRDMnc/edit'>FHW: Ebola Case Management</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFYW5iWE5rbk03TDA/edit'>FHW: Contact Tracing</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFVHR3TTdYVzJ0a28/edit'>FHW: KPI Assessment</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFbnkybXJSVEdxWmc/edit'>FHW: Malaria Assessment</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFQURrTkJlejAzTUk/edit'>FHW: Registration</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFNms3dnBaMWdYaE0/edit'>FHW: Sick Child</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFVkFqVEp6RGlKaHc/edit'>FHW: Sickness Screening Tool</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFMlEweUVNR2lsekk/edit'>FHW: Ebola Education + Screening Ledger</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFdVRBWi1PRFdoc00/edit'>Program: gCHV Questionnaire</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFNUg3Tll1M21CQkE/edit'>Program: Training Ledger</a></h4>
                </div>
                <div class="col-md-4">
                    <h3>Strategy documents</h3>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFZXNxWWlYQ3JzeFE/edit'>M&E Roadmap (May 2014)</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFd0VieV9JaXJsUHM/edit'>M&E Timeline (February 2015)</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFZERKbWlrcnk0Umc/edit'>Team Structure</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFVEZLWWp4Zm9HMFU/edit'>Log frames</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFRWpWcWVfYm9zRGM/edit'>Memo: Electronic Data Management</a></h4>
                </div>
                <div class="col-md-4">
                    <h3>Reports</h3>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFcFozcjV0SFZzNGs/edit'>Konobo Baseline Survey (KBS), August 2012</a></h4>
                    <h4><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFeVJYZmdDLVdxY2c/edit'>Key Performance Indicators Assessment (KPIA), August 2014</a></h4>
                </div>
            </div>
            
        </div>
        
    </body>
    
</html>