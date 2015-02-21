<?php
    session_start();
    if( !isset($_SESSION['username']) ) {
        $URL = $_SERVER['PHP_SELF'];
        Header('Location:/LastMileData/index.php?redirect=' . urlencode($URL));
    }
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
                    $('.nav-sidebar li').each(function(){
                        $(this).removeClass('active');
                    });
                    $(this).addClass('active');

                    // Fade out current pane; run callback
                    $('#pane_' + currentPane).fadeOut(500, function(){

                        // Scroll to top of page
                        window.scrollTo(0, 0);

                        // Fade in new page
                        $('#pane_' + newPane).fadeIn(1000, function(){
                            paneLoaded = 1;
                        });

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
                        <li><a href="#scaledata">Scale data</a></li>
                        <li><a href="#populationdata">Population data</a></li>
                        <!--<li><a href="#treatmentdata">Treatment data</a></li>-->
                    </ul>
                </div>
                
                
                
                    <!-- START: Pane -->
                    <div id="pane_overview" class="pane col-md-10">
                        
                        <h1>Overview</h1>
                        <hr>
                        
                        <h3>Dashboard</h3>
                        <div>
                            Our current programmatic dashboard.
                        </div>
                        
                        <h3>Scale data</h3>
                        <div>
                            Number of FHWs, broken down by trainings received.
                        </div>
                        
                        <h3>Population data</h3>
                        <div>
                            Population data for the communities in Liberia
                        </div>
                        
<!--                        <h3>Treatment data</h3>
                        <div>
                            Data on the treatment that we administer through our program.
                        </div>-->
                        
                        <div class="whitespace"></div>
                        
                    </div>
                    <!-- END: Pane -->
                    
                    
                    
                    <!-- START: Pane -->
                    <div id="pane_dashboard" class="pane col-md-10">
                        
                        <h1>Dashboard <span style="font-size:60%">(updated: 2/1/2015)</span></h1>
                        <hr>
                        
                        <h3><b>Scale</b>: Number of FHWs, Number of villages served</h4>
                        <div id="dashboard_8">
                            <script src="/LastMileData/src/js/Dimple/dashboard_8.js"></script>
                        </div>
                        
                        <h3><b>Scale</b>: Number of people served by FHWs</h3>
                        <div id="dashboard_14">
                            <script src="/LastMileData/src/js/Dimple/dashboard_14.js"></script>
                        </div>
                        
                        <h3><b>Delivery</b>: Access to Care</h3>
                        <div id="dashboard_12">
                            <script src="/LastMileData/src/js/Dimple/dashboard_12.js"></script>
                        </div>
                        
<!--                        <h3><b>Delivery</b>: Patient visits (distribution)</h3>
                        <div id="dashboard_9">
                            <script src="/LastMileData/src/js/Dimple/dashboard_9.js"></script>
                        </div>-->
                        
                        <h3><b>Delivery</b>: Patient visits (outputs)</h3>
                        <div id="dashboard_10">
                            <script src="/LastMileData/src/js/Dimple/dashboard_10.js"></script>
                        </div>
                        
                        <h3><b>Delivery</b>: Patient visits (outputs, without IMCI)</h3>
                        <div id="dashboard_11">
                            <script src="/LastMileData/src/js/Dimple/dashboard_11.js"></script>
                        </div>
                        
                        <h3><b>Delivery</b>: Treatment (IMCI)</h3>
                        <div id="dashboard_13">
                            <script src="/LastMileData/src/js/Dimple/dashboard_13.js"></script>
                        </div>
                        
                    </div>
                    <!-- END: Pane -->
                    
                    
                    
                    <!-- START: Pane-->
                    <div id="pane_scaledata" class="pane col-md-10">
                        
                        <h1>Scale Data <span style="font-size:60%">(updated: 2/19/2015)</span></h1>
                        <hr>
<div>
    <?php echo $_SERVER['REQUEST_URI']; ?>
</div>
                        
                        <h3>FHWs</h3>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>County</th>
                                <th>Health District</th>
                                <th># FHWs</th>
                                <th># trained<br>in FHW1</th>
                                <th># trained<br>in IMCI</th>
                                <th># trained<br>in MNH</th>
                                <th># trained<br>in FP</th>
                                <th># trained<br>in NCDs</th>
                            </tr>
                            <tr>
                                <td>Grand Gedeh</td>
                                <td>Konobo</td>
                                <td>55</td>
                                <td>55</td>
                                <td>42</td>
                                <td>26</td>
                                <td>10</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Grand Gedeh</td>
                                <td>Gboe-Ploe</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Rivercess</td>
                                <td>Central C</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Rivercess</td>
                                <td>Yarnee</td>
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
                    <!-- END: Pane -->
                    
                    
                    
                    <!-- START: Pane-->
                    <div id="pane_populationdata" class="pane col-md-10">
                        
                        <h1>Liberia Population Data <span style="font-size:60%">(updated: 2/19/2015)</span></h1>
                        <hr>
                        
                        <h3>Liberia population, by county</h3>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>County</th>
                                <th># People</th>
                                <th>Source</th>
                            </tr>
                            <tr>
                                <td>Bomi</td>
                                <td>82,036</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Bong</td>
                                <td>328,919</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Gbarpolu</td>
                                <td>83,758</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Grand Bassa</td>
                                <td>224,839</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Grand Cape Mount</td>
                                <td>129,055</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Grand Gedeh</td>
                                <td>125,937</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Grand Kru</td>
                                <td>57,903</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Lofa</td>
                                <td>270,114</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Margibi</td>
                                <td>199,689</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Maryland</td>
                                <td>135,738</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Montsserado</td>
                                <td>1,144,806</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Nimba</td>
                                <td>461,745</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>River Gee</td>
                                <td>66,789</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Rivercess</td>
                                <td>71,509</td>
                                <td>2008 Census</td>
                            </tr>
                            <tr>
                                <td>Sinoe</td>
                                <td>102,391</td>
                                <td>2008 Census</td>
                            </tr>
                        </table>
                        <br>
                        
                        <h3>Liberia population, by district (southeast only)</h3>
                        <table class="table table-striped table-hover">
                            <tr>
                                <th>County</th>
                                <th>Admin District</th>
                                <th># People</th>
                                <th># Households</th>
                                <th># Villages</th>
                                <th>Source</th>
                            </tr>
                            <tr><td>Grand Gedeh</td><td>B'hai</td><td>125,937</td><td>18,207</td><td>264</td><td>2008 Census</td></tr>
                            <tr><td>Grand Gedeh</td><td>Cavala</td><td>13,314</td><td>2,126</td><td>47</td><td>2008 Census</td></tr>
                            <tr><td>Grand Gedeh</td><td>Gbao</td><td>12,324</td><td>1,986</td><td>39</td><td>2008 Census</td></tr>
                            <tr><td>Grand Gedeh</td><td>Gboe-Ploe</td><td>6,271</td><td>856</td><td>18</td><td>2008 Census</td></tr>
                            <tr><td>Grand Gedeh</td><td>Glio-Twarbo</td><td>2,640</td><td>465</td><td>12</td><td>LMH registration data</td></tr>
                            <tr><td>Grand Gedeh</td><td>Konobo</td><td>13,956</td><td>1,754</td><td>42</td><td>LMH registration data</td></tr>
                            <tr><td>Grand Gedeh</td><td>Putu</td><td>16,426</td><td>1,792</td><td>27</td><td>2008 Census</td></tr>
                            <tr><td>Grand Gedeh</td><td>Tchien</td><td>32,695</td><td>6,357</td><td>54</td><td>2008 Census</td></tr>
                            <tr><td>Grand Kru</td><td>Barclayville</td><td>11,563</td><td>1,624</td><td>22</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Bleebo</td><td>1,710</td><td>267</td><td>5</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Bolloh</td><td>1,917</td><td>284</td><td>2</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Buah</td><td>643</td><td>123</td><td>8</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Dorbor</td><td>2,364</td><td>403</td><td>9</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Dweh</td><td>928</td><td>216</td><td>4</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Felo-Jekwi</td><td>2,011</td><td>370</td><td>3</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Fenetoe</td><td>1,696</td><td>251</td><td>4</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Forpoh</td><td>1,545</td><td>279</td><td>9</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Garraway</td><td>9,525</td><td>1,524</td><td>18</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Gee</td><td>2,543</td><td>413</td><td>5</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Grand Cess</td><td>10,809</td><td>1,439</td><td>15</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Kpi</td><td>1,597</td><td>268</td><td>15</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Lower Jloh</td><td>1,285</td><td>220</td><td>11</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Nrokwia-W</td><td>1,876</td><td>338</td><td>9</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Trenbo</td><td>3,631</td><td>555</td><td>10</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Upper Jloh</td><td>1,573</td><td>283</td><td>9</td><td>2008 Census</td>
                            <tr><td>Grand Kru</td><td>Wlogba</td><td>687</td><td>112</td><td>2</td><td>2008 Census</td>
                            <tr><td>Maryland</td><td>Gwelekpok</td><td>10,060</td><td>881</td><td>13</td><td>2008 Census</td>
                            <tr><td>Maryland</td><td>Harper</td><td>38,024</td><td>5,730</td><td>39</td><td>2008 Census</td>
                            <tr><td>Maryland</td><td>Karluway #1</td><td>8,294</td><td>1,256</td><td>31</td><td>2008 Census</td>
                            <tr><td>Maryland</td><td>Karluway #2</td><td>17,159</td><td>2,148</td><td>35</td><td>2008 Census</td>
                            <tr><td>Maryland</td><td>Nyorken</td><td>10,057</td><td>1,072</td><td>11</td><td>2008 Census</td>
                            <tr><td>Maryland</td><td>Pleebo/Sod</td><td>43,223</td><td>7,270</td><td>82</td><td>2008 Census</td>
                            <tr><td>Maryland</td><td>Whojah</td><td>8,921</td><td>899</td><td>9</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Boe & Quil</td><td>18,262</td><td>3,666</td><td>81</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Buu-Yao</td><td>40,007</td><td>6,756</td><td>74</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Doe</td><td>35,918</td><td>7,384</td><td>200</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Garr-Bain</td><td>61,225</td><td>10,451</td><td>141</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Gbehlay-Ge</td><td>32,670</td><td>6,492</td><td>74</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Gbi & Doru</td><td>8,131</td><td>1,548</td><td>35</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Gbor</td><td>10,875</td><td>1,913</td><td>20</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Kparblee</td><td>11,424</td><td>1,807</td><td>23</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Leewehpea</td><td>24,747</td><td>4,135</td><td>40</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Meinpea-M</td><td>24,157</td><td>4,314</td><td>98</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Sanniquelli</td><td>25,370</td><td>4,361</td><td>68</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Twan River</td><td>36,764</td><td>6,483</td><td>87</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Wee-Gbeh</td><td>32,934</td><td>5,799</td><td>62</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Yarmein</td><td>22,718</td><td>3,620</td><td>20</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Yarpea Ma</td><td>21,647</td><td>2,944</td><td>37</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Yarwein M</td><td>25,524</td><td>3,711</td><td>68</td><td>2008 Census</td>
                            <tr><td>Nimba</td><td>Zoe Gbao</td><td>29,372</td><td>5,328</td><td>37</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Chedepo</td><td>10,518</td><td>1,570</td><td>21</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Gbeapo</td><td>10,934</td><td>1,401</td><td>8</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Glaro</td><td>4,992</td><td>710</td><td>18</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Karforh</td><td>5,956</td><td>763</td><td>11</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Nanee</td><td>6,002</td><td>865</td><td>18</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Nyenawlike</td><td>5,159</td><td>819</td><td>12</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Nyenebo</td><td>5,703</td><td>890</td><td>26</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Potupo</td><td>7,337</td><td>893</td><td>39</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Sarbo</td><td>5,320</td><td>961</td><td>16</td><td>2008 Census</td>
                            <tr><td>River Gee</td><td>Tuobo</td><td>4,868</td><td>950</td><td>32</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Beawor</td><td>3,854</td><td>1,004</td><td>33</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Central Rivercess</td><td>8,303</td><td>1,693</td><td>58</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Doedain</td><td>13,041</td><td>2,330</td><td>51</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Fen River</td><td>12,630</td><td>2,158</td><td>88</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Jo River</td><td>8,921</td><td>1,806</td><td>90</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Norwein</td><td>13,900</td><td>2,776</td><td>138</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Sam Gbalor</td><td>3,714</td><td>786</td><td>36</td><td>2008 Census</td>
                            <tr><td>Rivercess</td><td>Zarflahn</td><td>7,146</td><td>1,428</td><td>46</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Bodae</td><td>3,539</td><td>343</td><td>12</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Bokon</td><td>4,373</td><td>736</td><td>14</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Butaw</td><td>3,432</td><td>678</td><td>51</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Dugbe River</td><td>9,239</td><td>1,554</td><td>98</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Greenville</td><td>15,715</td><td>3,052</td><td>36</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Jaedae</td><td>3,539</td><td>512</td><td>25</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Jeadepo</td><td>7,895</td><td>968</td><td>60</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Juarzon</td><td>6,088</td><td>801</td><td>18</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Kpayan</td><td>10,661</td><td>1,909</td><td>174</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Kulu Shaw</td><td>8,555</td><td>969</td><td>26</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Plahn Nyar</td><td>6,677</td><td>765</td><td>28</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Pynes Town</td><td>3,067</td><td>494</td><td>23</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Sanquin</td><td>8,526</td><td>1,600</td><td>76</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Seekon</td><td>7,024</td><td>792</td><td>27</td><td>2008 Census</td>
                            <tr><td>Sinoe</td><td>Wedjah</td><td>4,061</td><td>656</td><td>25</td><td>2008 Census</td>
                            </tr>
                        </table>
                        
                    </div>
                    <!-- END: Pane -->
                    
                    
                    
                    <!-- START: Pane -->
                    <div id="pane_treatmentdata" class="pane col-md-10">
                        
                        <h1>Treatment Data</h1>
                        <hr>
                        
                        <h3>Coming soon...</h3>
                        
                        <div class="whitespace"></div>
                        
                    </div>
                    <!-- END: Pane -->
                    
                    
                    
            </div>
        </div>
        
    </body>
    
</html>