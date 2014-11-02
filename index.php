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
        <script src="/LastMileData/src/js/modalFocus_v20140916.js"></script>
    </head>
    
    <body>
        
        <nav id="myNavbar" class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation"></nav>
        
        <!-- START LOGIN MODAL CODE -->
        <div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h1 class="text-center">Login</h1>
                    </div>
                    <div class="modal-body">
                        <form class="form col-md-12 center-block" action="/LastMileData/src/php/login.php" method="POST">
                            <div class="form-group">
                                <input name="username" type="text" class="form-control input-lg" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input name="password" type="password" class="form-control input-lg" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success btn-lg btn-block">Sign In</button>
                                <span>Please email <a href="mailto:avi@lastmilehealth.org?subject=Access to LastMileData.org">Avi Kenny</a> to request access to this site.</span>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-md-12">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                        </div>    
                    </div>
                </div>
            </div>
        </div>        
        <!-- END LOGIN MODAL CODE -->
        
        
        
        
    <div class="container">
        <div class="jumbotron">
            <h1>Last Mile Data</h1>
            <p><i>Last Mile Data</i> is the new centralized database platform for Last Mile Health. It can be used by all staff (development, communications, programs, etc) to access up-to-date data and information about our programs (as well as assorted M&E team documents), and will be used by our M&E staff to enter and quality-check data. It is currently an internal site, but in the future, it can potentially be used to share data and information with external partners.</p>
            <p><a href="#" class="btn btn-success btn-lg" data-toggle="modal" data-target="#loginModal">Login</a></p>
            <?php
                if ( isset($_GET['retry']) ) {
                    echo "<p style='color:red'>Incorrect login credentials. Please try again.</p>";
                }
            ?>
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