<?php
    session_start();
    if( !isset($_SESSION['username']) ) {
        $URL = $_SERVER['PHP_SELF'];
        Header('Location:/LastMileData/index.php?redirect=' . urlencode($URL));
    }
?>
<!DOCTYPE html>
<html>
    
    <head>
        <?php
            if( isset($_SESSION['username']) ) {
                echo "<script>sessionStorage.username = '" . $_SESSION['username'] . "'</script>";
            }
        ?>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='robots' content='noindex'>
        <link rel='icon' type='image/png' href='/LastMileData/res/lmd_icon_v20140916.png'>
        <title>Last Mile Data</title>
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/res/page_medocs.css"  type="text/css" />
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/LMD_accessControl.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        
        <script>
        $(document).ready(function(){
            // Apply access control settings
            // Set permissions in LMD_accessControl.js file
            LMD_accessControl.setPage('meDocs');
            LMD_accessControl.setUserType('<?php echo $_SESSION['usertype']; ?>');
            LMD_accessControl.go();
        });
        </script>
        
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container">
            <div class="jumbotron">
                <h1>M&E Documents</h1>
                <p>This section of the site contains key documents relevant to the M&E team, including program forms, strategy documents, and reports.</p>
                <p>Note: If you wish to use data from within any of these documents, please consult the M&E team first.</p>
                <p><b>Updated: Mar 6, 2015</b></p>
            </div>
            
            <div id="mainContents">

                <div class="row">
                    
                    <div class="col-md-4">
                        <h3 id="header_programForms">Program Forms</h3>
                        <div>
                            <h4 id="item_form_msh"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFN1hPYXo1YzRIU0k/edit'>Facility: IPC Mesh Tool</a></h4>
                            <h4 id="item_form_bdm"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFMGRUYXNYT0VXQnM/edit'>FHW: Births, Deaths, Movements</a></h4>
                            <h4 id="item_form_ecm"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFbWRBcGlVTjRDMnc/edit'>FHW: Ebola Case Management</a></h4>
                            <h4 id="item_form_ect"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFYW5iWE5rbk03TDA/edit'>FHW: Contact Tracing</a></h4>
                            <h4 id="item_form_kpi"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFVHR3TTdYVzJ0a28/edit'>FHW: KPI Assessment</a></h4>
                            <h4 id="item_form_mat"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFbnkybXJSVEdxWmc/edit'>FHW: Malaria Assessment</a></h4>
                            <h4 id="item_form_reg"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFQURrTkJlejAzTUk/edit'>FHW: Registration</a></h4>
                            <h4 id="item_form_sch"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFNms3dnBaMWdYaE0/edit'>FHW: Sick Child</a></h4>
                            <h4 id="item_form_sst"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFVkFqVEp6RGlKaHc/edit'>FHW: Sickness Screening Tool</a></h4>
                            <h4 id="item_form_ees"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFMlEweUVNR2lsekk/edit'>FHW: Ebola Education + Screening Ledger</a></h4>
                            <h4 id="item_form_chv"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFdVRBWi1PRFdoc00/edit'>Program: gCHV Questionnaire</a></h4>
                            <h4 id="item_form_trl"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFNUg3Tll1M21CQkE/edit'>Program: Training Ledger</a></h4>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h3 id="header_reports">Reports</h3>
                        <div>
                            <h4 id="item_report_kbs2012"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFcFozcjV0SFZzNGs/edit'>Konobo Baseline Survey (KBS), August 2012</a></h4>
                            <h4 id="item_report_kpia2014"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFeVJYZmdDLVdxY2c/edit'>Key Performance Indicators Assessment (KPIA), August 2014</a></h4>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h3 id="header_strategy">Strategy documents</h3>
                        <div>
                            <h4 id="item_strategy_meRoadmap"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFZXNxWWlYQ3JzeFE/edit'>M&E Roadmap (May 2014)</a></h4>
                            <h4 id="item_strategy_meTimeline"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFZnFTM01BN1JJa1k/edit'>M&E Timeline (February 2015)</a></h4>
                            <h4 id="item_strategy_teamStructure"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFZERKbWlrcnk0Umc/edit'>Team Structure</a></h4>
                            <h4 id="item_strategy_logFrames"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFVEZLWWp4Zm9HMFU/edit'>Log frames</a></h4>
                            <h4 id="item_strategy_mHealthMemo"><a target="_blank" href='https://docs.google.com/a/tiyatienhealth.org/file/d/0BwzcZZmQ5MkFRWpWcWVfYm9zRGM/edit'>Memo: Electronic Data Management</a></h4>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h3 id="header_mHealthTraining">mHealth training materials</h3>
                        <div>
                            <h4 id="item_mHealthTraining_feasibilityAssessment"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGc1hpOW51d2J2MDg&authuser=0'>FHW Feasibility Assessment mHealth Training</a></h4>
                            <h4 id="item_mHealthTraining_supervisor"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGVFB2RUxNeDFIanM&authuser=0'>FHW Supervisor mHealth Training</a></h4>
                            <h4 id="item_mHealthTraining_lms"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGck1EUGtFTnktemc&authuser=0'>Last Mile Survey Training</a></h4>
                            <h4 id="item_mHealthTraining_fieldManual"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGVFlKRWVfd2ZuZlU&authuser=0'>mHealth Field Manual</a></h4>
                            <h4 id="item_mHealthTraining_SOP"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGbG9OcDlZa2d2Y3M&authuser=0'>mHealth Standard Operating Procedures</a></h4>
                            <h4 id="item_mHealthTraining_dctToDctTransfer"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGeXl6eDhPX1pSYkE&authuser=0'>DCT to DCT Transfer Protocol </a></h4>
                            <h4 id="item_mHealthTraining_dctToPcTrasfer"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGUG5Od1FZN1F2M0E&authuser=0'>DCT to PC Transfer Protocol</a></h4>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h3 id="header_xForms">mHealth xForms</h3>
                        <div>
                            <h4 id="item_xForm_kpia"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGTWU4eWtBaTdXcWs&authuser=0'>xForm - KPI Assessment</a></h4>
                            <h4 id="item_xForm_routineVisit"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGOVNmSF9JSXZyajA&authuser=0'>xForm - Routine Visit Form</a></h4>
                            <h4 id="item_xForm_vaccine"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGS0FOZGNsXzVtYlk&authuser=0'>xForm - Vaccine Form</a></h4>
                            <h4 id="item_xForm_arrival"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGQnphVHdWZjZYY3M&authuser=0'>xForm - Arrival Log</a></h4>
                            <h4 id="item_xForm_restock"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGMkRZdTEwOTFoUUE&authuser=0'>xForm - CHW Restock</a></h4>
                            <h4 id="item_xForm_departure"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGWGY3Ujc2Qi00TVE&authuser=0'>xForm - Departure Log</a></h4>
                            <h4 id="item_xForm_supervision"><a target="_blank" href='https://drive.google.com/open?id=0B2wbmjD6KZKGblJQUUdfM244dW8&authuser=0'>xForm - Supervision Report</a></h4>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h3 id="header_litReviews">Literature reviews</h3>
                        <div>
                            <h4 id="item_litReviews_mwh"><a target="_blank" href='#'>Maternal waiting homes</a></h4>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <h3 id="header_qualityAssessments">Quality assessments</h3>
                        <div>
                            <h4 id="item_qualityAssessments_kpiaImplementation"><a target="_blank" href='#'>KPIA implementation quality</a></h4>
                            <h4 id="item_qualityAssessments_mappingUtility"><a target="_blank" href='#'>Mapping utility assessment</a></h4>
                        </div>
                    </div>
                    
                </div>

            </div>
            
            
        </div>
        
    </body>
    
</html>