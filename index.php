<!DOCTYPE html>
<html>
    
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name='robots' content='noindex'>
        <title>Last Mile Data</title>
        <link rel="stylesheet" href="lib/bootstrap-3.2.0-dist/css/bootstrap.min.css"  type="text/css" />
        <link rel="stylesheet" href="lib/bootstrap-3.2.0-dist/css/bootstrap-theme.min.css"  type="text/css" />
        <script src="lib/jquery.min.js"></script>
        <script src="lib/bootstrap-3.2.0-dist/js/bootstrap.min.js"></script>
        <script>
        $(document).ready(function(){
            
            delete sessionStorage.username;
            
            // Focus on first element when modal login form loads
            $('.modal').on('shown.bs.modal', function() {
                document.activeElement.blur();
                $(this).find(".modal-body :input:visible").first().focus();
            });
            
            // Load redirect URL into the form, if it exists
            if (location.hash !== '') {
                var redirect = $('#redirect').val();
                $('#redirect').val(redirect + location.hash);
            }
            
        });
        </script>
    </head>
    
    <body>

        <nav id="myNavbar" class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation"></nav>

        <div class="container">
            <div class="jumbotron">
                <h1>Last Mile Data</h1>
                <p><i>Last Mile Data</i> is the primary programmatic database platform for Last Mile Health. It can be used by all staff across teams to access up-to-date data and information about our programs, as well as RM&E team documents. It is used by our RM&E team to enter, aggregate, and quality-check data from both paper forms and mobile health forms.</p>
                <p><a href="#" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginModal">Login</a></p>
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
                        <p>&copy; Copyright <?php echo date("Y"); ?> Last Mile Health.</p>
                    </footer>
                </div>
            </div>
        </div>

        <!-- START LOGIN MODAL CODE -->
        <div id="loginModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h1 class="text-center">Login</h1>
                    </div>
                    <div class="modal-body">
                        <form class="form col-md-12 center-block" action="php/scripts/login.php" method="POST">
                            <div class="form-group">
                                <input name="username" type="text" class="form-control input-lg" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input name="password" type="password" class="form-control input-lg" placeholder="Password">
                                <input id="redirect" name="redirect" type="hidden" value="<?php echo @$_GET['redirect']; ?>">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary btn-lg btn-block">Sign In</button>
                                <span>Please email <a href="mailto:LMD@lastmilehealth.org?subject=Access to LastMileData.org">LMD@lastmilehealth.org</a> to request access to this site.</span>
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

    </body>
</html>