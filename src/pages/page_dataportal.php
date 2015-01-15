<?php
    session_start();
    if( !isset($_SESSION['username']) ) { Header('Location:/LastMileData/'); }
?>

<!-- !!!!! BUILD OUT THIS ENTIRE PAGE !!!!! -->
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
        <script src="/LastMileData/lib/d3.min.js"></script>
        <script src="/LastMileData/lib/dimple.v2.1.0.min.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        <script src="/LastMileData/src/js/sendRecords_v20140916.js"></script>
        
        <script>
            $(document).ready(function(){
//                $('#enterDataPortal').click(function(){
//                    $('.jumbotron').slideUp(1000,function(){
//                        $('.container-fluid').fadeIn(1000);
//                    });
//                });
                // Hide panes (NOTE: this needs to be done here instead of in stylesheet because of a Dimple.js quirk)
                $('.pane').each(function(){
                    $(this).css('display','none');
                });
                
                // Get initial value of currentPane; fade it in; highlight list item in sidebar
                var currentPane = window.location.hash.substring(1);
                $('#pane_' + currentPane + ', #footer').fadeIn(1000);
                $('a[href="#' + currentPane + '"]').parent().addClass('active');
                
                // Handle sidebar clicks
                $('.sidebar ul li').click(function(){
                    
                    // Get newPane
                    newPane = $(this).children('a').attr('href').substring(1);
                    
                    // Switch active sidebar element
                    $('a[href="#' + currentPane + '"]').parent().removeClass('active');
                    $(this).addClass('active');
                    
                    // Fade out current pane; run callback
                    $('#pane_' + currentPane).fadeOut(500, function(){
                        
                        // Scroll to top of page
                        window.scrollTo(0, 0);
                        
                        // Fade in new page
                        $('#pane_' + newPane).fadeIn(1000);
                        
                        // Set currentPane
                        currentPane = newPane;
                    });
                });

            });
        </script>
        
        <style>
            body {
              padding-top: 50px;
            }
            
            .jumbotron {
                display:none;
            }
            
            .pane hr {
                position:relative; top:-10px
            }
            
            .container-fluid {
                /*display:none;*/
            }
            
            #pane_container {
                /*display:none;*/
            }
            
            .pane {
                position:relative;
                left:228px;
            }
            
            .sidebar {
                position: fixed;
                top: 50px;
                bottom: 0;
                left: 0;
                display: block;
                padding: 20px;
                width: 228px;
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
        </style>
    </head>
    
    <body>  <!-- not fading this page in because of Dimple bug -->
        
        <div id="load_navbar"></div>
        
        <div class="container">
            <div class="jumbotron">
                <h1>Data Portal</h1>
                <p>The <i>Data Portal</i> is where all staff can go to access up-to-date information. Currently, data is uploaded manually on a monthly basis, but in the future, we will provide "real time" access to programmatic data.</p>
                <p><a href="#" id="enterDataPortal" class="btn btn-success btn-lg">Enter the Data Portal &raquo;</a></p>
            </div>
        </div>
            
        <div class="container-fluid">
            
            <div class="row">
                
                <div class="sidebar">
                    <ul class="nav nav-sidebar">
                        <li><a href="#overview">Overview</a></li>
                        <li><a href="#dashboard">Dashboard</a></li>
                    </ul>
                    <ul class="nav nav-sidebar">
                        <li><a href="#scaledata">Scale data</a></li>
                        <li><a href="#treatmentdata">Treatment data</a></li>
                    </ul>
                </div>
                
                
                
                    <!-- START: Pane_Overview -->
                    <div id="pane_overview" class="pane col-md-10">
                        
                        <h1>Overview</h1>
                        <hr>
                        
                        <h3>Dashboard</h3>
                        <div>
                            First draft of our program dashboard.
                        </div>
                        
                        <h3>Scale data</h3>
                        <div>
                            Information about the population and demographics of the communities that we serve, as well as the scale of our programs.
                        </div>
                        
                        <h3>Treatment data</h3>
                        <div>
                            Data on the treatment that we administer through our program.
                        </div>
                        
                        <div class="whitespace"></div>
                        
                    </div>
                    <!-- END: Pane_Overview -->
                    
                    
                    
                    <!-- START: Pane_Dashboard -->
                    <div id="pane_dashboard" class="pane col-md-10">
                        
                        <h1>Dashboard</h1>
                        <hr>
                        
                        <h3>Scale: Number of FHWs/Villages Served</h4>
                        <div id="dashboard_8">
                            <script src="/LastMileData/src/js/Dimple/dashboard_8.js"></script>
                        </div>
                        
                        <h3>Scale: Number of People Served (by FHWs)</h3>
                        <div id="dashboard_14">
                            <script src="/LastMileData/src/js/Dimple/dashboard_14.js"></script>
                        </div>
                        
                        <h3>Access to Care</h3>
                        <div id="dashboard_12">
                            <script src="/LastMileData/src/js/Dimple/dashboard_12.js"></script>
                        </div>
                        
                        <h3>Patient Visits - Distribution</h3>
                        <div id="dashboard_9">
                            <script src="/LastMileData/src/js/Dimple/dashboard_9.js"></script>
                            <br><br>
                            *Note: data on patient visits for Oct/Nov is incomplete; data will be available in January.
                            <br><br>
                        </div>
                        
                        <h3>Patient Visits - Outputs</h3>
                        <div id="dashboard_10">
                            <script src="/LastMileData/src/js/Dimple/dashboard_10.js"></script>
                            <br><br>
                            *Note: data on patient visits for Oct/Nov is incomplete; data will be available in January.
                            <br><br>
                        </div>
                        
                        <h3>Patient Visits - Outputs (without IMCI)</h3>
                        <div id="dashboard_11">
                            <script src="/LastMileData/src/js/Dimple/dashboard_11.js"></script>
                            <br><br>
                            *Note: data on patient visits for Oct/Nov is incomplete; data will be available in January.
                            <br><br>
                        </div>
                        
                        <h3>Treatment (IMCI)</h3>
                        <div id="dashboard_13">
                            <script src="/LastMileData/src/js/Dimple/dashboard_13.js"></script>
                            <br><br>
                            *Note: data on patient visits for Oct/Nov is incomplete; data will be available in January.
                            <br><br>
                        </div>
                        
                        <hr style="border:1px solid black">
                        
                        <h3>Indicator progression</h3>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>Dashboard Version</th>
                                <th>Indicators</th>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle">Current</td>
                                <td>
                                    <b>Scale</b>: [# of FHWs], [# of villages], [# of people]
                                    <br>
                                    <b>Delivery</b>: [% access, by health intervention], [patient visits, by health intervention],
                                    <br>
                                    [# treated, by condition]
                                    <br>
                                    <b>Outcome</b>: none
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle">Early-2015</td>
                                <td>
                                    <b>Scale</b>: [# of patient visits], [# of villages] (plotted against targets)
                                    <br>
                                    <b>Delivery</b>: [% access, by health intervention], [# vaccines delivered], [# of ANC visits]
                                    <br>
                                    <b>Impact</b>: [# child deaths]
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:middle">Mid-2015</td>
                                <td>
                                    <b>Scale</b>: [# of patient visits], [# of villages] (plotted against targets)
                                    <br>
                                    <b>Delivery</b>: [% full immunization], [% FP unmet need], [% in active care for HIV, TB, or NCDs],
                                    <br>
                                    [avg cost per person], [% "A"s on FHW scorecard]
                                    <br>
                                    <b>Outcome</b>: [% safe deliveries], [# child deaths]
                                </td>
                            </tr>
                        </table>
                        
                        <hr style="border:1px solid black">
                        
                        <h3>KPI Descriptions</h3>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th style="">Category</th>
                                <th style="">Indicator</th>
                                <th style="">Calculation</th>
                            </tr>
                            <tr>
                                <td rowspan="2" style="vertical-align:middle">Scale</td>
                                <td>Total number of patient visits</td>
                                <td>Facility-based visits + Outreach visits + FHW visits</td>
                            </tr>
                            <tr>
                                <td>Total number of villages served</td>
                                <td>Villages served by clinic + Villages served by FHW</td>
                            </tr>
                            <tr></tr>
                            <tr>
                                <td rowspan="5" style="vertical-align:middle">Delivery</td>
                                <td>Proportion of children who are fully immunized</td>
                                <td>Percentage of living children, age 12–23 months, who have received all WHO-specified vaccines</td>
                            </tr>
                            <tr>
                                <td>Percent of women with unmet need for family planning</td>
                                <td>Percentage of women of reproductive age who are fecund and sexually active but are not using any method of contraception, and report not wanting any more children or wanting to delay the next child</td>
                            </tr>
                            <tr>
                                <td>Percent of patients in active care for HIV, TB, or NCDs</td>
                                <td>Percentage of patients diagnosed with a condition who are actively receiving care (medication, counseling, and monitoring)</td>
                            </tr>
                            <tr>
                                <td>Average cost per person served</td>
                                <td>Total programmatic costs / total number of people served</td>
                            </tr>
                            <tr>
                                <td>Percent of FHWs who scored an "A" on FHW scorecard</td>
                                <td>Percentage of FHWs who scored an "A" on his/her scorecard, defined as an aggregate score of 85 or above, with no less than a 70 in any one category</td>
                            </tr>
                            <tr>
                                <td rowspan="5" style="vertical-align:middle">Impact</td>
                                <td>Proportion of safe (facility-based) deliveries</td>
                                <td>Percentage of live births that occurred in a government or private health facility</td>
                            </tr>
                            <tr>
                                <td>Under-five mortality rate</td>
                                <td>Probability of dying between birth and exactly five years of age, expressed per 1,000 live births</td>
                            </tr>
                        </table>
                        
<!--                        <h3>Patient Visits</h3>
                        <div id="dashboard_1">
                            <script src="/LastMileData/src/js/Dimple/dashboard_1.js"></script>
                        </div>
                        
                        <h3>Number of Children Treated</h3>
                        <div id="dashboard_2">
                            <script src="/LastMileData/src/js/Dimple/dashboard_2.js"></script>
                        </div>
                        
                        <h3>Pie chart</h3>
                        <div id="dashboard_3">
                            <script src="/LastMileData/src/js/Dimple/dashboard_3.js"></script>
                        </div>
                        
                        <h3>Pie Matrix</h3>
                        <div id="dashboard_4">
                            <script src="/LastMileData/src/js/Dimple/dashboard_4.js"></script>
                        </div>
                        
                        <h3>Line graph (single)</h3>
                        <div id="dashboard_5">
                            <script src="/LastMileData/src/js/Dimple/dashboard_5.js"></script>
                        </div>
                        
                        <h3>Line graph (multiple)</h3>
                        <div id="dashboard_6">
                            <script src="/LastMileData/src/js/Dimple/dashboard_6.js"></script>
                        </div>-->
                        
                        <div class="whitespace"></div>
                        
                    </div>
                    <!-- END: Pane_Dashboard -->
                    
                    
                    
                    <!-- START: Pane_Scale Data -->
                    <div id="pane_scaledata" class="pane col-md-10">
                        
                        <h1>Scale Data</h1>
                        <hr>
                        
                        <h3>Population</h3>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>County</th>
                                <th>District</th>
                                <th># Villages</th>
                                <th># Households</th>
                                <th># People</th>
                                <th>Source</th>
                            </tr>
                            <tr>
                                <td rowspan="3" style="vertical-align:middle">Grand Gedeh</td>
                                <td>Konobo</td>
                                <td>33</td>
                                <td>1,698</td>
                                <td>12,880</td>
                                <td>TH registration data</td>
                            </tr>
                            <tr>
                                <td>Glio-Twarbo</td>
                                <td>12</td>
                                <td>453</td>
                                <td>2,370</td>
                                <td>TH registration data</td>
                            </tr>
                            <tr>
                                <td>Gboe</td>
                                <td>10</td>
                                <td>?</td>
                                <td>2,769</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td rowspan="3" style="vertical-align:middle">Rivercess</td>
                                <td>Central Rivercess</td>
                                <td>73</td>
                                <td>2,048</td>
                                <td>10,240</td>
                                <td>TH estimates (based on HH counts)</td>
                            </tr>
                            <tr>
                                <td>Beawor</td>
                                <td>19</td>
                                <td>678</td>
                                <td>3,390</td>
                                <td>TH estimates (based on HH counts)</td>
                            </tr>
                            <tr>
                                <td>Sam Gbalor</td>
                                <td>36</td>
                                <td>?</td>
                                <td>3,714</td>
                                <td>2008 Census</td>
                            </tr>
                        </table>
                        <br>
                        
                        <h3>FHWs</h3>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>County</th>
                                <th>District</th>
                                <th># FHWs</th>
                                <th># trained<br>in FHW1</th>
                                <th># trained<br>in IMCI</th>
                                <th># trained<br>in MNH</th>
                                <th># trained<br>in FP</th>
                                <th># trained<br>in NCDs</th>
                            </tr>
                            <tr>
                                <td rowspan="3" style="vertical-align:middle">Grand Gedeh</td>
                                <td>Konobo</td>
                                <td>42</td>
                                <td>42</td>
                                <td>42</td>
                                <td>26</td>
                                <td>10</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Glio-Twarbo</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                                <td>---</td>
                            </tr>
                            <tr>
                                <td>Gboe</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td rowspan="3" style="vertical-align:middle">Rivercess</td>
                                <td>Central Rivercess</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Beawor</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Sam Gbalor</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                        </table>
                        <br>
                        
                        <div class="whitespace"></div>
                        
                    </div>
                    <!-- END: Pane_Scale Data -->
                    
                    
                    
                    <!-- START: Pane_Treatment Data -->
                    <div id="pane_treatmentdata" class="pane col-md-10">
                        
                        <h1>Treatment Data</h1>
                        <hr>
                        
                        <h3>Coming soon...</h3>
                        
                        <div class="whitespace"></div>
                        
                    </div>
                    <!-- END: Pane_Treatment Data -->
                    
                    
                    
            </div>
        </div>
        
    </body>
    
</html>