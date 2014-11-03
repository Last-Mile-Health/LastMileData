<!DOCTYPE html>

<html>
    
    <head>
        
        <title>FHW Tool (EES) - Ebola Education + Screening Ledger</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <meta name='robots' content='noindex'>
        <link rel='icon' type='image/png' href='/LastMileData/res/lmd_icon_v20140916.png'>
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/fhwForms_v20140916.js"></script>
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
        <link rel='stylesheet' type='text/css' media='all' href='/LastMileData/res/fhwForms_v20140916.css'> <!-- Stylesheet specific to forms; must come after bootstrap stylesheet to override -->
        
        <style>
            .inner_L { width: 3.0in; }
            .inner_C { width: 2.3in; }
            .inner_R { width: 2.0in; }
            
            .meBox { position:relative; top:-17px; }
            
            #RT { position:relative; top:-10px }
            .regTable { border: 1px solid black; border-collapse:collapse; margin:0px; padding:3px; }
            .regTable th { border: 1px solid black; padding:3px; text-align:center; font-weight:bold; }
            .regTable td { border: 1px solid black; height:28px; padding:0px; text-align:center; }
            
            .c1 { width:2.0in; border:none; margin-right:1px; margin-left:1px; }
            .c2 { width:0.4in; border:none; margin-right:1px; margin-left:1px; }
            .c3 { width:0.4in; border:none; margin-right:1px; margin-left:1px; }
            .c4 { width:0.6in; border:none; margin-right:1px; margin-left:1px; }
            .c5 { width:0.9in; border:none; margin-right:1px; margin-left:1px; }
            .c6 { width:0.9in; border:none; margin-right:1px; margin-left:1px; }
            .c7 { width:0.8in; border:none; margin-right:1px; margin-left:1px; }
            .c8 { width:0.9in; border:none; margin-right:1px; margin-left:1px; }
        </style>
        
    </head>
    
    <body>
        
        <!-- START: VALIDATION BOX -->
        <div id="validationBox"></div>
        <!-- END: VALIDATION BOX -->
        
        
        
        <!-- START: PAGE 1 -->
        <div class="page">
            
            
            
            <!-- START: FORM HEADER -->
            <div class="formHeader">
                <img style="float:left" src="/LastMileData/res/logo_LMH_v20140916.png">
                <span class="h2">FHW: Education + Screening Ledger</span>
                <img style="float:right" src="/LastMileData/res/logo_TH_v20140916.png">
                <div style="clear:both"></div>
            </div>
            <hr>
            
            <div class="thickBox">
                
                <div>
                    <b>Visit each household. Screen and educate. Record information below about each visit.</b>
                </div>
                <hr>
                
                <div class="headField" style="width:1.05in">FHW name/ID:</div>
                <div class="headField" style="width:1.37in"><input class="stored" id="fhwName" data-lmd-valid-required="yes" style="width:120px"> / </div>
                <div class="headField" style="width:1.0in"><input class="stored" id="fhwID" data-lmd-valid-required="yes" style="width:60px"></div>
                <div style="clear:both; height:3px;"></div>
                <hr>
                
                <div style="float:left; width:160px">
                    <b>Screening codes:</b>
                </div>
                <div style="float:left; width:160px">
                    <b>(A)</b> Probable case
                    <br>
                    <b>(B)</b> Suspect case
                </div>
                <div style="float:left; width:180px">
                    <b>(C)</b> Possible malaria
                    <br>
                    <b>(D)</b> Contact risk
                </div>
                <div style="float:left">
                    <b>(E)</b> Not a case
                </div>
                <div style="clear:both"></div>
                <hr>
                
                <div style="float:left; width:160px">
                    <b>Education codes:</b>
                </div>
                <div style="float:left; width:160px">
                    <b>(A)</b> Signs of Ebola
                    <br>
                    <b>(B)</b> Ebola treatment
                </div>
                <div style="float:left; width:180px">
                    <b>(C)</b> Transmission
                    <br>
                    <b>(D)</b> Handling Ebola cases
                </div>
                <div style="float:left">
                    <b>(E)</b> Ebola rumors
                </div>
                <div style="clear:both"></div>
                
            </div>
            <!-- END: FORM HEADER -->

            
            
            <!-- START: SECTION A -->
            <div class="thickBox" id="RT">
                
                <!-- START: Registration table -->
                <table class='regTable'>
                    
                    <!-- Table header -->
                    <tr>
                        <th>Name</th>
                        <th>Sex</th>
                        <th>Age</th>
                        <th>HH ID</th>
                        <th>Member ID</th>
                        <th>Visit date<br>(d/m/y)</th>
                        <th>Screening<br>code</th>
                        <th>Education<br>codes</th>
                    </tr>
                    
                    <!-- Row 01 of 30 -->
                    <tr>
                        <td><input class="stored c1" id="id"></td>
                        <td><input class="stored c2" id="id"></td>
                        <td><input class="stored c3" id="id"></td>
                        <td><input class="stored c4" id="id"></td>
                        <td><input class="stored c5" id="id"></td>
                        <td><input class="stored c6" id="id"></td>
                        <td><input class="stored c7" id="id"></td>
                        <td><input class="stored c8" id="id"></td>
                    </tr>
                    
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    <tr><td><input class="stored c1" id="id"></td><td><input class="stored c2" id="id"></td><td><input class="stored c3" id="id"></td><td><input class="stored c4" id="id"></td><td><input class="stored c5" id="id"></td><td><input class="stored c6" id="id"></td><td><input class="stored c7" id="id"></td><td><input class="stored c8" id="id"></td></tr>
                    
                </table>
                <!-- END: Registration table -->
                
            </div>
            <!-- END: SECTION A -->
            
            
            
            <!-- START: M&E Box -->
            <div class="meBox">
                <span class="blackBox">M&E</span>
                <span class="blackBox">EES.02</span>
                &nbsp;&nbsp;&nbsp;
                DE Initials: <input class="stored" id="de_init">
                DE Date: <input class="stored" id="de_date">
                QA Initials: <input class="stored" id="qa_init">
                QA Date: <input class="stored" id="qa_date">
            </div>
            <!-- START: M&E Box -->
            
            
            
        </div>
        <!-- END: PAGE 1 -->
        
        
        
        <!-- START: S/C Buttons -->
        <div class="formButtons">
            <button id="lmd_submit" class="btn btn-success">Submit</button>&nbsp;
            <button id="lmd_cancel" class="btn btn-success">Cancel</button>
        </div>
        <!-- END: S/C Buttons -->
        
        
        
        <!-- START: Hidden fields -->
        <input type="hidden" class="stored" id="table" value="tbl_data_fhw_reg_registration">
        <!-- END: Hidden fields -->
        
    </body>
    
</html>