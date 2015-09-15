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
        
        <?php
            if( isset($_SESSION['username']) ) {
                echo "<script>sessionStorage.username = '" . $_SESSION['username'] . "'</script>";
            }
        ?>
        
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='robots' content='noindex'>
        
        <title>Last Mile Data</title>
        
        <link rel='icon' type='image/png' href='../images/lmd_icon.png'>
        <link rel="stylesheet" href="../../lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="../../lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        
        <script src="../../lib/jquery.min.js"></script>
        <script src="../../lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script src="../js/loadContents.js"></script>
        
    </head>
    
    <body style="display:none">
        
        <div id="load_navbar"></div>
        
        <div class="container main">
            <div class="jumbotron">
                <h1>Last Mile Data</h1>
                <p><i>Last Mile Data</i> is the primary programmatic database platform for Last Mile Health. It can be used by all staff across teams to access up-to-date data and information about our programs, as well as RM&E team documents. It is used by our RM&E team to enter, aggregate, and quality-check data from both paper forms and mobile health forms.</p>
                <p><b>Welcome, <span style="color:#3E86C3"><?php echo $_SESSION['username']; ?></span>.</b></p>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h2>Data Portal</h2>
                    <p>The <i>Data Portal</i> is where all staff can go to access up-to-date data and information about our programs. Data is updated monthly on the 15th of each month.</p>
                    <p><a class="fadeLink btn btn-primary" href="page_dataportal.php">Go to Data Portal &raquo;</a></p>
                </div>
                <div class="col-md-6">
                    <h2>Data Entry / QA</h2>
                    <p>This section of the site is used by our RM&E staff, and can be accessed offline when no internet connection is present.</p>
                    <p><a href="page_deqa.html" class="btn btn-primary">Go to Data Entry / QA &raquo;</a></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <footer>
                        <p>&copy; Copyright 2015 Last Mile Health.</p>
                    </footer>
                </div>
            </div>
        </div>        
        
    </body>
    
</html>