$(document).ready(function() {
    
    $('#generateHouseholdIDs').click(function() {
        
        var hhID = $('#householdID').val();
        
        // !!!!! Add error message if either field is NULL !!!!!
        
        $('.hhID').each(function() {
            $(this).text(hhID);
            hhID++;
        });
        
    });
    
});
