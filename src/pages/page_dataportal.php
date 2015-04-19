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
        <link rel="stylesheet" href="/LastMileData/res/page_dataportal.css"  type="text/css" />
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/lib/d3.min.js"></script>
        <script src="/LastMileData/lib/dimple.v2.1.0.min.js"></script>
        <script src="/LastMileData/src/js/LMD_dimpleHelper.js"></script>
        <script src="/LastMileData/src/js/LMD_accessControl.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        
        <script>
        $(document).ready(function(){
            
            // Add bullets to sidebar elements
            $('.dp_frag, .dp_iframe').each(function(){
                $(this).find('a').prepend('&bull;&nbsp;');
            });
            
            // Apply access control settings
            // Set permissions in LMD_accessControl.js file
            LMD_accessControl.setPage('dataPortal');
            LMD_accessControl.setUserType('<?php echo $_SESSION['usertype']; ?>');
            LMD_accessControl.go();
            
            // !!!!! This section is reserved for making specific data portal pages "linkable"; needs to interface with access control system !!!!!
            // !!!!! use these parameters to set initial page !!!!!
            // !!!!! below, when link changes, programmatically set URL (if possible)  !!!!!
            // !!!!! consider using hash anchors  !!!!!
//            var init_fragOrFrame = "<?php // echo @$_GET['fragOrFrame']; ?>";
//            var init_myLink = "<?php // echo @$_GET['myLink']; ?>";
            
            // Fade in overview pane by default
            $('#mainContainer').load('/LastMileData/src/fragments/dataportal_overview.php',function(){
                // These need to happen AFTER navbar loads
                $(this).scrollTop(0);
                $('#dashboard_iframe').hide();
                $('#mainContainer').fadeIn(1000);
                $('#dp_sidebar').fadeIn(1000);
            });
            
            // Handle sidebar clicks
            $('.dp_frag, .dp_iframe').click(function(){
                
                // Get link URL
                var myLink = $(this).attr('data-link');
                var fragOrFrame = $(this).hasClass('dp_frag') ? "frag" : "frame";

                    // Fade out current mainContainer
                    $('#whitespaceContainer').slideDown(500, function(){
                        
                        $('#mainContainer').scrollTop(0);
                        
                        // Handle fragment loads
                        if (fragOrFrame === "frag") {
                                $('#dashboard_iframe').hide();
                                $('#mainContainer').show();
                                $('#mainContainer').load(myLink, function(){
                                    setTimeout(function(){
                                        $('#whitespaceContainer').slideUp(1000);
                                    },500);
                                });

                        // Handle iframe loads
                        } else if (fragOrFrame === "frame") {
                                $('#mainContainer').hide();
                                $('#dashboard_iframe').show();
                                $('#dashboard_iframe').prop('src',myLink);
                        }

                    });

                // Switch active sidebar element
                $('.dp_frag, .dp_iframe').each(function(){
                    $(this).removeClass('dp-active');
                });
                $(this).addClass('dp-active');

            });
            
            // Fade out whitespaceContainer when iFrame is done loading
            document.getElementById("dashboard_iframe").onload = function() {
                $('#whitespaceContainer').slideUp(1000);
            };
            
            // jQueryUI Accordion on sidebar
            $("#myAccordion").accordion({
                heightStyle: "content",
                collapsible: true
//                active: false // uncomment this to hide all accordion sections by default
            });

        });
        </script>
        
    </head>
    
    <body>
        
        <div id="load_navbar"></div>
        
        <div class="container-fluid">
            
            <!--<div class="row" style="height:10vh">&nbsp;</div>-->
            <div class="row" style="height:100vh">
                
                <!-- Side navigation bar -->
                <div id="dp_sidebar" class="col-md-2" style="display:none; position:relative">
                    <div style="height:51px"></div>
                    <div class="nav nav-sidebar" id="myAccordion">
                        <!--    DIVs with class="dp_frag" will be loaded as a document fragment
                                DIVs with class="dp_iframe" will be loaded as iFrames               -->
                        <h3 id="header_overview">Overview</h3>
                        <div>
                            <div id="item_overview" class="dp_frag dp-active" data-link="/LastMileData/src/fragments/dataportal_overview.php"><a>Overview</a></div>
                            <div id="item_updates" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_updates.php"><a>Portal updates</a></div>
                        </div>
                        <h3 id="header_monthlyDashboards">Monthly Dashboards</h3>
                        <div>
                            <div id="item_execDashboard" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_execDashboard.php"><a>Executive dashboard</a></div>
                            <div id="item_konobo" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_konobo.php"><a>Konobo monthly report</a></div>
                            <div id="item_ebolaActivities" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_ebolaActivities.php"><a>Ebola activities</a></div>
                            <div id="item_fhwTraining" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_fhwTraining.php"><a>FHW training</a></div>
                        </div>
                        <h3 id="header_kpia">KPI Assessment (2014)</h3>
                        <div>
                            <div id="item_kpiaMortality" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_kpia_mortality.php"><a>Early childhood mortality</a></div>
                            <div id="item_kpiaFacilityDelivery" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_kpia_facilityDelivery.php"><a>Facility delivery</a></div>
                            <div id="item_kpiaANC" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_kpia_anc.php"><a>Antenatal care</a></div>
                            <div id="item_kpiaVaccination" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_kpia_vaccination.php"><a>Vaccination</a></div>
                        </div>
                        <h3 id="header_liberiaStats">Liberia stats</h3>
                        <div>
                            <div id="item_liberiaPopulation" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_liberiaPopulation.php"><a>Population</a></div>
                            <div id="item_DHS" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_dhs.php"><a>DHS 2013</a></div>
                        </div>
<!--                        <h3 id="header_overview">Maps</h3>
                        <div>
                            <div id="item_mapsKonobo" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_mapsKonobo.php"><a>Population</a></div>
                            <div id="item_mapsRivercess" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_mapsRivercess.php"><a>DHS 2013</a></div>
                        </div>-->
                        <h3 id="header_meTools">M&E Team Tools</h3>
                        <div>
                            <div id="item_ebolaReport" class="dp_iframe" data-link="/LastMileData/src/pages/report_ebola.php"><a>Ebola report</a></div>
                            <div id="item_sickChildReport" class="dp_iframe" data-link="/LastMileData/src/pages/report_sickChild.php"><a>Sick child report</a></div>
                            <div id="item_dataEntrySummary" class="dp_frag" data-link="/LastMileData/src/pages/viewSentRecords.php"><a>Data entry summary</a></div>
                            <div id="item_idNumbers" class="dp_frag" data-link="/LastMileData/src/pages/idNumbers.php"><a>ID numbers</a></div>
                        </div>
                        <h3 id="header_staging">!!!!! Staging area !!!!!</h3>
                        <div>
                            <div id="item_leafletMap" class="dp_frag" data-link="/LastMileData/src/fragments/dataportal_leafletMap.html"><a>Leaflet map</a></div>
                        </div>
                    </div>
                </div>
                
                <!-- Outer container -->
                <div id="outerContainer" class="pane col-md-10">
                    
                    <div style="height:51px"></div>
                    
                    <!-- Whitespace container for fade in/out effects -->
                    <div id="whitespaceContainer"><br>
                        <!--<div>-->
                        <div style="position:relative; width:100px; left:20px">
                        <!--<div style="position:relative; left:10px; width:100px">-->
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif"><br>
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif"><br>
                            <img src="/LastMileData/res/ajax-loader_v20140916.gif"><br>
                        </div>
                    </div>
                    
                    <!-- Content containers -->
                    <div id="mainContainer"></div>
                    <iframe id="dashboard_iframe"></iframe>
                    
                </div>
                
                
            </div>
        </div>
        
    </body>
    
</html>