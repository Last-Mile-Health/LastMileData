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
        <script src="/LastMileData/lib/jquery.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/lib/d3.min.js"></script>
        <script src="/LastMileData/lib/dimple.v2.1.0.min.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        
        <script>
        $(document).ready(function(){
            
            // Fade in overview pane by default
            $('#mainContainer').load('/LastMileData/src/fragments/dataportal_overview.html',function(){
                $('#mainContainer').fadeIn(1000);
                $('#mySidebar').fadeIn(1000);
            });
            
            // Handle sidebar clicks
            $('.sidebar ul li').click(function(){
                
                // Get newPane
                var newPane = $(this).attr('id').substring(3);
                
                // Fade out current mainContainer
                $('#whitespaceContainer').fadeIn(500, function(){
                    $('#mainContainer').load('/LastMileData/src/fragments/dataportal_' + newPane + '.html', function(){
                        $('#whitespaceContainer').fadeOut(500);
                    });
                });

                // Switch active sidebar element
                $('.nav-sidebar li').each(function(){
                    $(this).removeClass('active');
                });
                $(this).addClass('active');

            });
            
        });
        </script>
        
        <style>
            body {
                padding-top: 50px;
                overflow-x: hidden;
            }
            
            .pane hr {
                position:relative; top:-10px
            }
            
            .pane {
                position:relative;
                left:217px;
            }
            
            .sidebar {
                position: fixed;
                top: 50px;
                bottom: 0;
                left: 0;
                display: block;
                padding: 20px;
                width: 217px;
                overflow-x: hidden;
                overflow-y: auto;
                background-color: #d7d7d7;
                border-right: 1px solid #eee;
                z-index: 10;    /* under navbar but over content */
            }
            
            .whitespace {
                height:500px;
            }
            
            th {
                font-size:120%;
            }
            
            /* Sidebar navigation */
            .nav-sidebar {
                margin-right: -21px; /* 20px padding + 1px border */
                margin-bottom: 20px;
                margin-left: -20px;
            }
            .nav-sidebar > li > a {
                cursor:pointer;
                color:green;
                font-weight:bold;
                padding-right: 20px;
                padding-left: 20px;
            }
            .nav-sidebar > .active > a,
            .nav-sidebar > .active > a:hover,
            .nav-sidebar > .active > a:focus {
                color: #fff;
                background-color: green;
            }
            
            // !!!!! Unused "hover for definition" button !!!!!
            // !!!!! <span class="definition">D</span> !!!!!
            .definition {
                cursor:pointer;
                font-weight:bold;
                color:white!important;                  /* overrides boostrap.min.css */
                background-color:#336600!important;     /* overrides boostrap.min.css */
                padding-top:3px;
                border-radius:10px;
                padding-bottom:2px;
                padding-left:4px;
                padding-right:4px;
                padding-top:2px;
                -webkit-print-color-adjust:exact;
            }
            
            .definition:hover {
                position:relative;
                top:1px;
            }
            
            table.ptg_data th {
                border: 1px solid white;
                text-align:center;
                padding: 3px;
                font-size:100%;
                color:white;
                background-color: #E26B0A;
            }
            
            table.ptg_data td {
                border: 1px solid white;
                text-align:center;
                padding: 3px;
                background-color: #eeeeee;
            }
            
            .smallHR {
                margin-bottom:0px;
            }
            
            #mainContainer {
                display:none;
                position: absolute;
                z-index: 100;
            }

            #whitespaceContainer {
                display:none;
                position: absolute;
                z-index: 110;
                background-color:white;
                width:2000px;
                height:2000px;
            }
            
        </style>
    </head>
    
    <body>
        
        <div id="load_navbar"></div>
        
        <div class="container-fluid">
            
            <div class="row">
                
                <!-- Sidebar -->
                <div id="mySidebar" class="sidebar" style="display:none">
                    <ul class="nav nav-sidebar">
                        <li id="li_overview" class="active"><a>Overview</a></li>
                        <li id="li_execDashboard"><a>Executive dashboard</a></li>
                        <li id="li_treatment"><a>Treatment</a></li>
                        <li id="li_ebolaActivities"><a>Ebola activities</a></li>
                        <li id="li_fhwTraining"><a>FHW training</a></li>
                        <li id="li_liberiaPopulation"><a>Liberia population</a></li>
                    </ul>
                </div>
                
                <!-- Main container -->
                <div id="mainContainer" class="pane col-md-10"></div>
                
                <!-- Whitespace container for fade in/out effects -->
                <div id="whitespaceContainer" class="pane col-md-10"><br>
                    <div style="position:relative; left:10px;">
                        <img src="/LastMileData/res/ajax-loader_v20140916.gif"><br>
                        <img src="/LastMileData/res/ajax-loader_v20140916.gif"><br>
                        <img src="/LastMileData/res/ajax-loader_v20140916.gif"><br>
                    </div>
                </div>
                
            </div>
        </div>
        
    </body>
    
</html>