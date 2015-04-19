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
        <script src="/LastMileData/src/js/loadContents_v20140916.js"></script>
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container main">
            <div class="jumbotron">
                <h1>Last Mile Data</h1>
                <p><i>Last Mile Data</i> is the primary programmatic database platform for Last Mile Health. It can be used by all staff across teams to access up-to-date data and information about our programs, as well as access critical M&E team documents. It is used by our M&E team to enter, aggregate, and quality-check data from both paper forms and mobile health forms.</p>
                <p><b>Welcome, <span style="color:green"><?php echo $_SESSION['username']; ?></span>.</b></p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h2>Data Portal</h2>
                    <p>The <i>Data Portal</i> is where all staff can go to access up-to-date data and information about our programs. Data is updated monthly on the 12th of each month.</p>
                    <p><a class="fadeLink btn btn-success" href="/LastMileData/src/pages/page_dataportal.php">Go to Data Portal &raquo;</a></p>
                </div>
                <div class="col-md-4">
                    <h2>M&E Documents</h2>
                    <p>This section contains all key documents relevant to the M&E team, including program forms, strategy memos, and survey reports.</p>
                    <p><a href="/LastMileData/src/pages/page_medocs.php" class="btn btn-success">Go to M&E Documents &raquo;</a></p>
                </div>
                <div class="col-md-4">
                    <h2>Data Entry / QA</h2>
                    <p>This section of the site is used by our M&E staff, and can be accessed offline when no internet connection is present.</p>
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