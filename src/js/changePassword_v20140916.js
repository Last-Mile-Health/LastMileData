
$(document).ready(function() {
    
    // This element is in the navbar
    $('#submit_changePW').click(function() {
        
        // Manipulate DOM; show loader GIF
        $('#pw_old,#pw_new_1,#pw_new_2,#submit_changePW').css('display','none');
        $('#modal_text').html("<img src='/LastMileData/res/ajax-loader_v20140916.gif'>");
        
        // Collect form data
        var pw_old = $('#pw_old').val();
        var pw_new_1 = $('#pw_new_1').val();
        var pw_new_2 = $('#pw_new_2').val();
        
        // Run password-change script ("changePassword.php") via ajax
        $.ajax({
            url: "/LastMileData/src/php/changePassword.php",
            data: "pw_old=" + pw_old + "&pw_new_1=" + pw_new_1 + "&pw_new_2=" + pw_new_2,
            dataType: "json",
            success: function(data) {
                
                // Script response: old password is incorrect
                if ( data == 'old_pw_bad' ) {
                    $('#modal_text').html('Old password is incorrect. Please try again.');
                }
                // Script response: new passwords don't match
                else if ( data == 'new_pws_dont_match' ) {
                    $('#modal_text').html('New passwords don\'t match. Please try again.');
                }
                // Script response: success
                else if ( data == 'pw_success' ) {
                    $('#modal_text').html('Success!');
                }
                // Error handler
                else {
                    $('#modal_text').html('Error. Please contact Avi Kenny for support.');
                }
                
                // Close modal; reset DOM
                closeAndResetModal();
            },
            error: function(request, status, error) {
                // Error handler
                $('#modal_text').html('Error. Please contact Avi Kenny for support.');
                
                // Close modal; reset DOM
                closeAndResetModal();
            }
        });
        
    });
    
    function closeAndResetModal() {
        
        setTimeout( function() {
            
            // Close dialog box
            $('#cancelModal').click();
            
            // Clear variables
            pw_old = ""; pw_new_1 = ""; pw_new_2 = "";
            
            setTimeout( function() {
                // Reset modal DOM
                $('#pw_old,#pw_new_1,#pw_new_2,#submit_changePW').css('display','');
                $('#pw_old,#pw_new_1,#pw_new_2').val('');
                $('#modal_text').html("");
            }, 200 );
            
        }, 1000 );
        
    }
    
});
