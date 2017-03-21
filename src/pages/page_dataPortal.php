<?php
    session_start();
    if( !isset($_SESSION['username']) ) {
        $URL = $_SERVER['PHP_SELF'];
        Header('Location:../../index.php?redirect=' . urlencode($URL));
    }
?>
<!DOCTYPE html>
<html>
    
    <head>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='robots' content='noindex'>
        
        <title>Last Mile Data</title>
        
        <link rel='icon' type='image/png' href='../images/lmd_icon.png'>
        <link rel="stylesheet" href="../../lib/bootstrap-3.2.0-dist/css/bootstrap.min.css" type="text/css" />
        <link rel="stylesheet" href="../../lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css" type="text/css" />
        <link rel="stylesheet" href="../../lib/multiselect.css" type="text/css" />
        <link rel="stylesheet" href="../../lib/shepherd-1.8.1/shepherd-theme-lmd.css" type="text/css" />
        <link rel="stylesheet" href="../css/page_dataPortal.css" type="text/css" />
        
        <?php
            // Echo username / userGroups (used by access control system)
            echo "<script>";
            echo "sessionStorage.username = '" . $_SESSION['username'] . "';";
            echo "sessionStorage.userGroups = '" . $_SESSION['userGroups'] . "';";

            // Initiate/configure CURL session
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

            // Echo JSON (sidebar contents)
            $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/php/scripts/LMD_REST.php/json_objects/1";
            curl_setopt($ch,CURLOPT_URL,$url1);
            $json1 = curl_exec($ch);

            // Close CURL session and echo JSON
            curl_close($ch);
            echo "var model_sidebar = JSON.parse($json1.objectData);". "\n\n";
            echo "</script>";
        ?>
        
        <script src="../../lib/jquery.min.js"></script>
        <script src="../../lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="../../lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="../../lib/knockout/knockout-3.3.0.js"></script>
        <script src="../../lib/knockout/knockout.mapping-latest.js"></script>
        <script src="../../lib/moment.min.js"></script>
        <!--<script src="../../lib/underscore.min.js"></script>-->
        <!--<script src="../../lib/backbone.min.js"></script>-->
        <script src="../../lib/showdown.min.js"></script>
        <script src="../../lib/multiselect.min.js"></script>
        <script src="../../lib/d3.min.js"></script>
        <script src="../../lib/dimple.v2.1.0.min.js"></script>
        <script src="../../lib/shepherd-1.8.1/tether.min.js"></script>
        <script src="../../lib/shepherd-1.8.1/shepherd.min.js"></script>
        <script src="../js/loadContents.js"></script>
        <script src="../js/LMD_dimpleHelper.js"></script>
        <script src="../js/LMD_utilities.js"></script>
        <script src="../js/LMD_dataPortal.js"></script>
        <script src="../js/LMD_koREST.js"></script>
        <script src="../js/LMD_shepherd.js"></script>
        <script src="../js/page_dataPortal.js"></script>
        
    </head>
    
    <body>

        <!-- START: header / navigation bar -->
        <div id="load_navbar"></div>
        <!-- END: header / navigation bar -->
        
        <div id="outerContainer">
            
            <!-- START: Sidebar -->
            <div id="dp_sidebar">
                <div class="nav nav-sidebar" id="sidebarDIV" data-bind="foreach:groups">
                    <div>
                        <h3 data-bind="text:name, attr: {id:id}"></h3>
                        <div data-bind="foreach:tabs">
                            <div data-bind="attr: {id:id, class:type, 'data-link':link}">
                                <a>&bull;&nbsp;<span data-bind="text:name"></span></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: Sidebar -->
            
            <!-- START: Main body container -->
            <div id="innerContainer">
                
                <!-- DIV for Shepherd.js tour to attach to -->
                <div style="float:right" id="shepherdAnchor"></div>

                <!-- Whitespace container for fade in/out effects -->
                <div id="whitespaceContainer"><br>
                    <div>
                        <img src="../images/ajax_loader.gif"><br>
                        <img src="../images/ajax_loader.gif"><br>
                        <img src="../images/ajax_loader.gif"><br>
                    </div>
                </div>

                <!-- Content containers -->
                <div id="mainContainer"></div>
                <iframe id="dashboard_iframe"></iframe>

            </div>
            <!-- END: Main body container -->
            
        </div>

   </body>
</html>