$(document).ready(function() {
    
    $('#folderName').change(function() {
        
        // Get user-typed folder name
        var folderName = $(this).val();
        
        // Allow only alphanumeric characters, hyphens, and spaces
        if (folderName.search(/^[A-Za-z0-9_-\s]+$/) != 0) {
            
            // Display an error message
            // !!!!! Switch this out for code similar to datepicker error tooptip; functionize this code !!!!!
            alert('Only alphanumeric characters, hyphens, and spaces are allowed in filenames');
            $(this).val('');
            $(this).focus();
        }
        
    });
    
});
