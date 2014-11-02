$(document).ready(function(){
    
    $('#generateMemIDs').click(function() {
        
        // Set vilID and memID variables
        var vilID = $('#villageID').val();
        var memID = $('#memberID').val();
        
        // !!!!! Add error message if either field is NULL !!!!!
        
        $('.memID').each(function() {
            $(this).text(vilID + "-" + fourDigits(memID));
            memID++;
        });
        
    });
    
});


// Pad numbers to five digits
function fourDigits(d) {
    if(0 <= d && d < 10) return "000" + d.toString();
    if(10 <= d && d < 100) return "00" + d.toString();
    if(100 <= d && d < 1000) return "0" + d.toString();
    return d.toString();
}
