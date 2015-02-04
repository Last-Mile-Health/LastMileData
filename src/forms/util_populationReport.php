<?php
    // Extract GET parameters
    extract($_GET);
?>

<!DOCTYPE html>

<html>
    
    <head>
        
        <title>FHW Tool (POP 01) - Population Report</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <meta name='robots' content='noindex'>
        <link rel='icon' type='image/png' href='/LastMileData/res/lmd_icon_v20140916.png'>
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/LMD_fileSystemHelper.js"></script>
        <script src="/LastMileData/src/js/fhwForms_v20140916.js"></script>
        <script src="/LastMileData/src/js/formHelper.js"></script>
        <script src="/LastMileData/src/js/formValidate.js"></script>
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.css"  type="text/css" />
        <link rel='stylesheet' type='text/css' media='all' href='/LastMileData/res/fhwForms_v20140916.css'> <!-- Stylesheet specific to forms; must come after bootstrap stylesheet to override -->
        
        <style>
            .inner_L { width: 3.0in; }
            .inner_C { width: 2.3in; }
            .inner_R { width: 2.0in; }
            
            /*.meBox { position:relative; top:-17px; }*/
            
            .multiplePages {
                overflow:hidden;
                margin-top:30px;
                border:2px solid black;
                margin:auto;
                padding:5px;
                width:7.5in;
                /*height:9.9in;*/
                page-break-after:always;
            }
            
            @media screen {
                .multiplePages {
                    margin-top:30px;
                }
            }
            
            @media print {
                .c8 {
                    page-break-inside: avoid;
                }
            }
            
            #ES { position:relative; top:-8px }
            .eesTable { border: 1px solid black; border-collapse:collapse; margin:0px; padding:3px; page-break-inside:auto; }
            .eesTable th { border: 1px solid black; padding:3px; text-align:center; font-weight:bold; }
            .eesTable tr { page-break-inside:avoid; page-break-after:auto; }
            .eesTable td { border: 1px solid black; height:28px; padding:0px; text-align:center; page-break-inside: avoid; overflow:hidden; }
            .eesTable td input { text-align:center; }
            
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
                <span class="h2">FHW: Population Report</span>
                <img style="float:right" src="/LastMileData/res/logo_TH_v20140916.png">
                <div style="clear:both"></div>
            </div>
            <hr>
            
            <div class="thickBox">
                
                <div class="headField" style="width:1.05in">FHW name/ID:</div>
                <div class="headField" style="width:2.2in"><input value="<?php echo @htmlspecialchars($fhwName, ENT_QUOTES); ?>" class="stored" id="fhwName" data-lmd-valid-required="yes" style="width:200px; text-align:center; font-weight:bold"> / </div>
                <div class="headField" style="width:1.0in"><input value="<?php echo @htmlspecialchars($fhwID, ENT_QUOTES); ?>" class="stored" id="fhwID" data-lmd-valid-required="yes" style="width:60px; text-align:center; font-weight:bold"></div>
                <div style="clear:both; height:3px;"></div>
                
            </div>
            <!-- END: FORM HEADER -->
            
            
            
            <!-- START: SECTION -->
            <div class="thickBox" id="ES">
                
                    <?php
                        // Set include path; require connection strings
                        set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
                        require_once("cxn.php");
                        
                        // !!!!! Set PHP max execution time !!!!!
                        // !!!!! the underlying view (view_reg_current-population) is problematic and needs to be fixed. takes way too long to run. filter before the join ?????
                        set_time_limit(120);
                        
                        // Send query to get population records
//                        $query = "SELECT * FROM `view_reg_current-population` WHERE current_fhwID=$fhwID ORDER BY abs(hhID), firstName;";
                        $query = "select 
                                        `tbl_assc_staff_memberid`.`fk_staff` AS `current_fhwID`,
                                        `view_reg_registration`.`fhwID` AS `original_fhwID`,
                                        `view_reg_registration`.`fk_village` AS `fk_village`,
                                        `view_reg_registration`.`memberID` AS `memberID`,
                                        `view_reg_registration`.`hhID` AS `hhID`,
                                        `view_reg_registration`.`firstName` AS `firstName`,
                                        `view_reg_registration`.`lastName` AS `lastName`,
                                        `view_reg_registration`.`sex` AS `sex`,
                                        `view_reg_registration`.`dob` AS `dob`
                                    from
                                        (`view_reg_registration`
                                        left join `tbl_assc_staff_memberid` ON ((`view_reg_registration`.`memberID` = `tbl_assc_staff_memberid`.`memberID`)))
                                    where
                                                fhwID=$fhwID AND
                                        (not (`view_reg_registration`.`memberID` in (select 
                                                `view_bdm_deaths`.`memberID`
                                            from
                                                `view_bdm_deaths` union select 
                                                `view_bdm_moveouts`.`memberID`
                                            from
                                                `view_bdm_moveouts`))) 
                                    union select 
                                        `tbl_assc_staff_memberid`.`fk_staff` AS `current_fhwID`,
                                        `view_bdm_births`.`fhwID` AS `original_fhwID`,
                                        `view_bdm_births`.`fk_village` AS `fk_village`,
                                        `view_bdm_births`.`memberID` AS `memberID`,
                                        `view_bdm_births`.`hhID` AS `hhID`,
                                        `view_bdm_births`.`firstName` AS `firstName`,
                                        `view_bdm_births`.`lastName` AS `lastName`,
                                        `view_bdm_births`.`sex` AS `sex`,
                                        `view_bdm_births`.`dob` AS `dob`
                                    from
                                        (`view_bdm_births`
                                        left join `tbl_assc_staff_memberid` ON ((`view_bdm_births`.`memberID` = `tbl_assc_staff_memberid`.`memberID`)))
                                    where
                                                fhwID=$fhwID AND
                                        (not (`view_bdm_births`.`memberID` in (select 
                                                `view_bdm_deaths`.`memberID`
                                            from
                                                `view_bdm_deaths` union select 
                                                `view_bdm_moveouts`.`memberID`
                                            from
                                                `view_bdm_moveouts`))) 
                                    union select 
                                        `tbl_assc_staff_memberid`.`fk_staff` AS `current_fhwID`,
                                        `view_bdm_moveins`.`fhwID` AS `original_fhwID`,
                                        `view_bdm_moveins`.`fk_village` AS `fk_village`,
                                        `view_bdm_moveins`.`memberID` AS `memberID`,
                                        `view_bdm_moveins`.`hhID` AS `hhID`,
                                        `view_bdm_moveins`.`firstName` AS `firstName`,
                                        `view_bdm_moveins`.`lastName` AS `lastName`,
                                        `view_bdm_moveins`.`sex` AS `sex`,
                                        `view_bdm_moveins`.`dob` AS `dob`
                                    from
                                        (`view_bdm_moveins`
                                        left join `tbl_assc_staff_memberid` ON ((`view_bdm_moveins`.`memberID` = `tbl_assc_staff_memberid`.`memberID`)))
                                    where
                                                fhwID=$fhwID AND
                                        (not (`view_bdm_moveins`.`memberID` in (select 
                                                `view_bdm_deaths`.`memberID`
                                            from
                                                `view_bdm_deaths` union select 
                                                `view_bdm_moveouts`.`memberID`
                                            from
                                                `view_bdm_moveouts`)))
                                            ORDER BY abs(hhID), firstName;";
                                
                        $result = mysqli_query($cxn, $query) or die("Failed to connect to database") ;
                        
                        // Reset counter and endpoint
                        $i = 0;
                        $lastRecord = mysqli_num_rows($result) - 1;
                        
                        // Loop through recordset
                        While ( $row = mysqli_fetch_assoc($result) ) {
                            
                            // Start a new table
                            if ($i==0 || $i==23 || ($i-23)%30==0) { // !!!!! use mod function; here and below (X3) !!!!!
                                
                                // Start a new page
                                if ($i==23 || ($i-23)%30==0) { Echo "<div class='page'><div class='thickBox'>" . "\n"; }
                                
                                // Echo table headers
                                Echo "<table class='eesTable'>" ."\n\n";
                                    Echo "<tr>" ."\n";
                                        Echo "<th>Name</th>" ."\n";
                                        Echo "<th>Sex</th>" ."\n";
                                        Echo "<th>Age</th>" ."\n";
                                        Echo "<th>HH ID</th>" ."\n";
                                        Echo "<th>Member ID</th>" ."\n";
                                    Echo "</tr>" . "\n\n";
                            }
                            
                            // Echo table row
                            Echo "<tr>" ."\n";
                                Echo "<td><input class='c1' value='" . $row['firstName'] . " " . $row['lastName'] . "'></td>" ."\n";
                                Echo "<td><input class='c2' value='" . $row['sex'] . "'></td>" ."\n";
                                Echo "<td><input class='c3' value='" . floor(abs(strtotime($row['dob']) - strtotime(date("Y-m-d"))) / (365.25*60*60*24)) . "'></td>" ."\n";
                                Echo "<td><input class='c4' value='" . $row['hhID'] . "'></td>" ."\n";
                                Echo "<td><input class='c5' value='" . $row['memberID'] . "'></td>" ."\n";
                            Echo "</tr>" ."\n";
                            
                            // Close table, div
                            if ($i==22 || ($i-22)%30==0 || $i==$lastRecord) {
                                    Echo "</table>" ."\n";
                                    Echo "<!-- END -->" ."\n\n";
                                    Echo "</div>" ."\n";
                                    Echo "</div>" ."\n";
                                    Echo "<!-- END: PAGE -->" ."\n";
                            }
                            
                            // Increment counter
                            $i++;
                        }
                    ?>
        
    </body>
    
</html>