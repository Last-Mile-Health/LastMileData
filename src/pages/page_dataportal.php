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
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='robots' content='noindex'>
        <title>Last Mile Data</title>
        <link rel='icon' type='image/png' href='/LastMileData/res/lmd_icon_v20140916.png'>
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <link rel="stylesheet" href="/LastMileData/res/page_dataportal.css"  type="text/css" />
        
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <!--<script src="/LastMileData/lib/underscore.min.js"></script>-->  <!-- !!!!! to save on bandwidth, only load these where they're needed (like Leaflet.js) !!!!! -->
        <!--<script src="/LastMileData/lib/backbone.min.js"></script>-->    <!-- !!!!! to save on bandwidth, only load these where they're needed (like Leaflet.js) !!!!! -->
        <!--<script src="/reusables/lib/underscore.min.js"></script>-->
        <script src="/LastMileData/lib/rivets.bundled.min.js"></script>
        <script src="/LastMileData/lib/d3.min.js"></script>
        <script src="/LastMileData/lib/dimple.v2.1.0.min.js"></script>
        <script src="/LastMileData/src/js/LMD_dimpleHelper.js"></script>
        <!--<script src="/LastMileData/src/js/LMD_accessControl.js"></script>-->    <!-- !!!!! DELETE AFTER REFACTORING !!!!! -->
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>      <!-- !!!!! Is this needed ????? -->
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>        <!-- !!!!! Is this needed ????? -->
        
        <script>
        $(document).ready(function(){

        <?php

            // Echo username (used by access control system)
            if( isset($_SESSION['username']) ) {
                echo "<script>sessionStorage.username = '" . $_SESSION['username'] . "'</script>";
            }
            
            // Initiate/configure CURL session
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

            // Echo JSON (sidebar contents)
            $url1 = "localhost/LastMileData/src/php/LMD_REST.php/json_objects/1";
            curl_setopt($ch,CURLOPT_URL,$url1);
            $json1 = curl_exec($ch);

            // Close CURL session and echo JSON
            curl_close($ch);
            echo "var model_sidebar = JSON.parse($json1.objectData);". "\n\n";

        ?>

            // Configure rivets.js
            rivets.configure({templateDelimiters: ['{{', '}}']});

            // !!!!! Make specific data portal pages "linkable" (with hash anchors); needs to interface with access control system !!!!!

            // Bind sidebar model to accordion DIV
            rivets.bind($('#sidebarDIV'), {model_sidebar: filteredSidebar});

            // Fade in overview pane by default
            $('#mainContainer').load('/LastMileData/src/fragments/dataportal_overview.php',function(){
                // These need to happen AFTER navbar loads
                $(this).scrollTop(0);
                $('#dashboard_iframe').hide();
                $('#mainContainer').fadeIn(1000);
                $('#dp_sidebar').fadeIn(1000);
                $('.dp_frag').first().addClass('dp-active');
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
                                $('#mainContainer').load(myLink, function(responseText, textStatus, jqXHR){
                                    if (textStatus === "error") {
                                        $('#mainContainer').html("<h1>Error.</h1><h3>Please check your internet connection and try again later.</h3>");
                                    }
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
            $("#sidebarDIV").accordion({
                header: "h3",
                heightStyle: "content",
                collapsible: true
            });

        });
        </script>
        
    </head>
    
    <body>
        
        <div id="load_navbar"></div>
        
        <div class="container-fluid">
            
            <div class="row" style="height:100vh">
                
                <!-- Side navigation bar -->
                <div id="dp_sidebar" class="col-md-2" style="display:none; position:relative">
                    <div style="height:51px"></div>
                    <div class="nav nav-sidebar" id="sidebarDIV">
                        <div rv-each-sidebar="model_sidebar">
                            <h3 rv-id="sidebar.id" id="{{sidebar.id}}">{{sidebar.name}}</h3>
                            <div>
                                <div rv-id="tabs.id" rv-class="tabs.type" rv-data-link="tabs.link" rv-each-tabs="sidebar.tabs"><a>&bull;&nbsp;{{tabs.name}}</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Outer container -->
                <div id="outerContainer" class="pane col-md-10">
                    
                    <div style="height:51px"></div>
                    
                    <!-- Whitespace container for fade in/out effects -->
                    <div id="whitespaceContainer"><br>
                        <div style="position:relative; width:100px; left:20px">
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