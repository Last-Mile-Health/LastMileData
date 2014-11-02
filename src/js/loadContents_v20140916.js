// This page loads site content via AJAX. Allows for easier site maintenance.

$(document).ready(function() {
    
    // Check to see if the navbar container element is present
    if( $('#load_navbar').length == 1 ) {
        
        // Load the navbar HTLM; run callback
        $('#load_navbar').load('/LastMileData/src/fragments/header_bootstrap.html',function() {
            
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
            
        });
        
    }
    else {
        
        // Fade in page body
        $('body').fadeIn(1000);
    }
    
    
});
