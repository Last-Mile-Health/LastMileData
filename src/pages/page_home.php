<?php
    session_start();
    if( !isset($_SESSION['username']) ) { Header('Location:/LastMileData/'); }
?>

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
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
        <script src="/LastMileData/src/js/changePassword_v20140916.js"></script>
        <script>
            $(document).ready(function(){
                sessionStorage.username = '<?php echo $_SESSION['username']; ?>';
                console.log(sessionStorage);
            });
        </script>
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container main">
            <div class="jumbotron">
                <h1>Last Mile Data</h1>
                <p><i>Last Mile Data</i> is the new centralized database platform for Last Mile Health. It can be used by all staff (development, communications, programs, etc) to access up-to-date data and information about our programs (as well as assorted M&E team documents), and will be used by our M&E staff to enter and quality-check data. It is currently an internal site, but in the future, it can potentially be used to share data and information with external partners.</p>
                <p><b>Welcome, <span style="color:green"><?php echo $_SESSION['username']; ?></span>. </b></p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h2>Data Portal</h2>
                    <p>The <i>Data Portal</i> is where all staff can go to access up-to-date information. Currently, data is uploaded manually on a monthly basis, but in the future, we will provide "real time" access to programmatic data.</p>
                    <p><a class="fadeLink btn btn-success" href="/LastMileData/src/pages/page_dataportal.php#overview">Go to Data Portal &raquo;</a></p>
                </div>
                <div class="col-md-4">
                    <h2>M&E Documents</h2>
                    <p>This section of the site contains all key documents relevant to the M&E team, including strategy memos, program forms, survey reports, active/pending publications, team bios, and more.</p>
                    <p><a href="/LastMileData/src/pages/page_medocs.php?folder=uploads" class="btn btn-success">Go to M&E Documents &raquo;</a></p>
                </div>
                <div class="col-md-4">
                    <h2>Data Entry / QA</h2>
                    <p>This section is meant to be used by our M&E staff. This section can be accessed offline when no internet connection is present. For access to this section of the site, please email <a href='mailto:avi@lastmilehealth.org'>Avi Kenny</a>.</p>
                    <p><a href="/LastMileData/src/pages/page_deqa.html" class="btn btn-success">Go to Data Entry / QA &raquo;</a></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <footer>
                        <p>&copy; Copyright 2014 Last Mile Health.</p>
                    </footer>
                </div>
            </div>
        </div>        
        
    </body>
    
</html>