// This page loads site content via AJAX. Allows for easier site maintenance.

$(document).ready(function() {
    
    // Load the navbar HTML; run callback
    $('#load_navbar').load('../pages/header_bootstrap.html', function() {
        
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
                $('#modal_changePW_message').html("<img src='../images/ajax_loader.gif'>").delay(500).slideDown(500, function(){

                    setTimeout(function(){

                        // Collect form data
                        var pw_old = $('#modal_changePW_pw_old').val();
                        var pw_new_1 = $('#modal_changePW_pw_new_1').val();
                        var pw_new_2 = $('#modal_changePW_pw_new_2').val();

                        // Run password-change script ("changePassword.php") via ajax
                        $.ajax({
                            url: "/LastMileData/php/scripts/changePassword.php",
                            data: "pw_old=" + pw_old + "&pw_new_1=" + pw_new_1 + "&pw_new_2=" + pw_new_2 + "&username=" + sessionStorage.username,
                            dataType: "json",
                            success: function(data) {
                                
                                switch(data) {
                                    
                                    case 'old_pw_bad':
                                        errorMessage('Old password is incorrect. Please try again.');
                                        break;
                                        
                                    case 'new_pws_dont_match':
                                        errorMessage('New passwords don\'t match. Please try again.');
                                        break;
                                    
                                    case 'pw_success':
                                        errorMessage('Success! New password has been set.');
                                        break;
                                    
                                    default:
                                        errorMessage('Error. Please contact LMD@LastMileHealth.org for support.');
                                        break;
                                }
                                
                            },
                            error: function(request, status, error) {
                                errorMessage('Error. Please contact LMD@LastMileHealth.org for support.');
                            }
                        });
                    },500);
                });
            });
        });

        // Focus on first element when a modal form loads
        $('.modal').on('shown.bs.modal', function() {
            document.activeElement.blur();
            $(this).find(".modal-body :input:visible").first().focus();
        });

    });
});


function errorMessage(message) {
    $('#modal_changePW_message').slideUp(500, function(){
        $(this).html(message).slideDown(500, closeAndResetModal);
    });
}


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
