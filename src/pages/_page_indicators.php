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
        <script src="/LastMileData/lib/jquery-ui-1.11.1/jquery-ui.min.js"></script>
        <script src="/LastMileData/lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/formHelper.js"></script>
        <script src="/LastMileData/src/js/formValidate.js"></script>
        <script src="/LastMileData/src/js/_indicators.js"></script>
        
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
            
            .pane {
                position:relative;
                left:300px;
            }
            
            .sidebar {
                position: fixed;
                top: 50px;
                left: 0px;
                bottom: 0;
                display: block;
                padding: 20px;
                width: 300px;
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
                cursor: pointer;
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
            
            .f {
                float:left;
            }
            
        </style>
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container-fluid">
            
            <div class="row">
                
                <div id="searchParameters" class="sidebar">
                    
                    <div>
                        <table>
                            <tr style="font-weight:bold; font-size:16px">
                                <td colspan="2">Search:</td>
                            </tr>
                            <tr>
                                <td>Name:</td>
                                <td><input id="search_name" style="width:180px"></td>
                            </tr>
                            <tr>
                                <td>Level:</td>
                                <td><input id="search_level" data-lmd-valid-select='["Input","Output","Outcome","Impact"]' style="width:180px"></td>
                            </tr>
                            <tr>
                                <td>Category:</td>
                                <td><input id="search_category" data-lmd-valid-select='["General","IMCI","MNH","FP","Well Adult","Supervision","Ebola","Malaria"]' style="width:180px"></td>
                            </tr>
                            <tr>
                                <td>Tags:</td>
                                <td>
                                    <input id="search_tag_perfmgmt_fhw" type="checkbox">Perf mgmt (FHW)<br>
                                    <input id="search_tag_perfmgmt_fhwl" type="checkbox">Perf mgmt (FHWL)<br>
                                    <input id="search_tag_perfmgmt_site" type="checkbox">Perf mgmt (Site)
                                </td>
                            </tr>
                        </table>
                        <hr>
                    </div>
                    
                    <div style="font-weight:bold">
                        <ul id="searchResults" class="nav nav-sidebar"></ul>
                    </div>
                    
                </div>
                
                
                
                <!-- START: Pane_Overview -->
                <div class="pane col-md-10" style="overflow-x:no-display">
                    
                    <h2>Indicator: <input readonly id="ind_name2" style="width:800px; font-weight:bold; border:none"></h2>
                    <hr>

                    <h3>Basic Info</h3>
                    <div class="f" style="width:100px">Name:</div>
                    <div class="f" style="width:300px"><input class="stored" style="width:200px" id="ind_name"></div>
                    <div class="f" style="width:100px">Category:</div>
                    <div class="f" style="width:300px"><input class="stored" style="width:200px" id="ind_category" data-lmd-valid-select='["General","IMCI","MNH","FP","Well Adult","Supervision","Ebola","Malaria"]'></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:100px">Level:</div>
                    <div class="f" style="width:300px"><input class="stored" style="width:200px" id="ind_level" data-lmd-valid-select='["Input","Output","Outcome","Impact"]'></div>
                    <div class="f" style="width:100px">Sub-category:</div>
                    <div class="f" style="width:300px"><input class="stored" style="width:200px" id="ind_subcategory"></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:100px">Definition:</div>
                    <div class="f"><textarea class="stored" style="resize:none; width:600px" id="ind_definition"></textarea></div>
                    <div style="clear:both; height:5px"></div>
                    <hr>

                    <h3>Details</h3>
                    <div class="f" style="width:100px">Numerator:</div>
                    <div class="f"><textarea class="stored" style="resize:none; width:600px" id="ind_numerator"></textarea></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:100px">Denominator:</div>
                    <div class="f"><textarea class="stored" style="resize:none; width:600px" id="ind_denominator"></textarea></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:100px">Disaggregate:</div>
                    <div class="f"><textarea class="stored" style="resize:none; width:600px" id="ind_disaggregate"></textarea></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:100px">Data source:</div>
                    <div class="f"><textarea class="stored" style="resize:none; width:600px" id="ind_datasource"></textarea></div>
                    <div style="clear:both; height:5px"></div>
                    <hr>

                    <h3>Tags</h3>
                    <div class="f" style="width:200px">Performance mgmt (FHW)</div>
                    <div class="f" style="width:100px"><input class="stored" type="checkbox" id="ind_tag_perfmgmt_fhw"></div>
                    <div class="f" style="width:100px">Tag 4</div>
                    <div class="f" style="width:100px"><input class="stored" type="checkbox" id="ind_tag_4"></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:200px">Performance mgmt (FHWL)</div>
                    <div class="f" style="width:100px"><input class="stored" type="checkbox" id="ind_tag_perfmgmt_fhwl"></div>
                    <div class="f" style="width:100px">Tag 5</div>
                    <div class="f" style="width:100px"><input class="stored" type="checkbox" id="ind_tag_5"></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:200px">Performance mgmt (Site)</div>
                    <div class="f" style="width:100px"><input class="stored" type="checkbox" id="ind_tag_perfmgmt_site"></div>
                    <div class="f" style="width:100px">Tag 6</div>
                    <div class="f" style="width:100px"><input class="stored" type="checkbox" id="ind_tag_6"></div>
                    <div style="clear:both; height:5px"></div>
                    <hr>

                    <h3>Notes</h3>
                    <div class="f" style="width:100px">General notes:</div>
                    <div class="f"><textarea class="stored" style="resize:none; width:600px" id="ind_definition"></textarea></div>
                    <div style="clear:both; height:5px"></div>
                    <div class="f" style="width:100px">Reporting requirements:</div>
                    <div class="f"><textarea class="stored" style="resize:none; width:600px" id="ind_definition"></textarea></div>
                    <div style="clear:both; height:5px"></div>
                    <hr>
                    
                    <input type="hidden" id="pk">
                    
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button id="ind_update" class="btn btn-success" style="width:300px">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <button id="ind_delete" class="btn btn-danger" style="width:300px">Delete</button>
                    <hr>
                    
                </div>
                <!-- END: Pane_Overview -->
                    
            </div>
            
        </div>
        
    </body>
    
</html>