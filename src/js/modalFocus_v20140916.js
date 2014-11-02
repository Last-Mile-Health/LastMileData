$(document).ready(function(){
    
    // Focus on first element when modal form loads
    $('.modal').on('shown.bs.modal', function() {
        document.activeElement.blur();
        $(this).find(".modal-body :input:visible").first().focus();
    });
    
});