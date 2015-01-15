// This page loads site content via AJAX. Allows for easier site maintenance.

$(document).ready(function() {
    
    // Check to see if the navbar container element is present
    if( $('#load_navbar').length == 1 ) {
        
        // Load the navbar HTML; run callback
        $('#load_navbar').load('/LastMileData/src/fragments/header_bootstrap.html', function() {
            
            // Get current URL and split out the page
            var currentURL = document.URL;
            var urlParts = currentURL.split("/");
            var page = urlParts[urlParts.length-1];
            var pageParts = page.split(".");
            page = pageParts[0] || 'page_home';
            
            // Add "active" class to appropriate navbar <li> to highlight it
            $('#' + page).addClass('active');
            
            // Fade in page body
            $('body').fadeIn(1500);
            
            // Add click handler for change PW button
            $('#modal_changePW_submit').click(function() {
                
                // Manipulate DOM; show loader GIF
                $('#modal_changePW_form').slideUp(500,function(){
                    $('#modal_changePW_message').html("<img src='/LastMileData/res/ajax-loader_v20140916.gif'>").delay(500).slideDown(500, function(){
                        
                        setTimeout(function(){
                            
                            // Collect form data
                            var pw_old = $('#modal_changePW_pw_old').val();
                            var pw_new_1 = $('#modal_changePW_pw_new_1').val();
                            var pw_new_2 = $('#modal_changePW_pw_new_2').val();

                            // Run password-change script ("changePassword.php") via ajax
                            $.ajax({
                                url: "/LastMileData/src/php/changePassword.php",
                                data: "pw_old=" + pw_old + "&pw_new_1=" + pw_new_1 + "&pw_new_2=" + pw_new_2 + "&username=" + sessionStorage.username,
                                dataType: "json",
                                success: function(data) {
                                    
                                    // Script response: old password is incorrect
                                    if ( data == 'old_pw_bad' ) {
                                        $('#modal_changePW_message').slideUp(500, function(){
                                            $(this).html('Old password is incorrect. Please try again.').slideDown(500, closeAndResetModal);
                                        });
                                    }
                                    // Script response: new passwords don't match
                                    else if ( data == 'new_pws_dont_match' ) {
                                        $('#modal_changePW_message').slideUp(500, function(){
                                            $(this).html('New passwords don\'t match. Please try again.').slideDown(500, closeAndResetModal);
                                        });
                                    }
                                    // Script response: success
                                    else if ( data == 'pw_success' ) {
                                        $('#modal_changePW_message').slideUp(500, function(){
                                            $(this).html('Success! New password has been set.').slideDown(500, closeAndResetModal);
                                        });
                                    }
                                    // Error handler
                                    else {
                                        $('#modal_changePW_message').slideUp(500, function(){
                                            $(this).html('Error. Please contact Avi Kenny for support.').slideDown(500, closeAndResetModal);
                                        });
                                    }

                                },
                                error: function(request, status, error) {
                                    // Error handler
                                    $('#modal_changePW_message').slideUp(500, function(){
                                        $(this).html('Error. Please contact Avi Kenny for support.').slideDown(500, closeAndResetModal);
                                    });
                                }
                            });
                            
                        },500);
                        
                    });
                });
                
            });
        });
    } else {
        // Fade in page body
        $('body').fadeIn(1000);
    }
    
});



function closeAndResetModal() {
    
    // Clear variables
    pw_old = ""; pw_new_1 = ""; pw_new_2 = "";
    
    setTimeout( function() {
        
        // Close modal
        $('.modal').modal('hide');
        
        setTimeout( function() {
            // Reset modal DOM
            $('#modal_changePW_form').show();
            $('#modal_changePW_pw_old,#modal_changePW_pw_new_1,#modal_changePW_pw_new_2').val('');
            $('#modal_changePW_message').html("");
        }, 500 );
        
    }, 2000 );
    
}
