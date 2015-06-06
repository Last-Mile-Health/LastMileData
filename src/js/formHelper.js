$(document).ready(function() {
    
    
    // Apply jQueryUI autocomplete
    // e.g.     (static)            <input class="autocomplete" data-lmd-valid-autoC='["one","two","three"]'>
    //          (static, sorted)    <input class="autocomplete" data-lmd-valid-autoC='["one","two","three"]' data-lmd-valid-sortList="yes">
    //          (dynamic)           <input class="autocomplete" data-lmd-valid-autoC="villages"> // !!!!! villages must be deifned in... !!!!!
    $(".autocomplete").each(function() {
        
        // Get source from data attribute
        mySource = $(this).attr('data-lmd-valid-autoC');
        
        if (mySource.substring(0,1)=="[") {
            // Parse "static" (inline) arrays
            myList = JSON.parse(mySource);
        } else {
            // Parse "dynamic" (localStorage) arrays
            myList = JSON.parse(localStorage[mySource]);
            
            if (typeof localStorage[mySource] !== "undefined" && localStorage[mySource] !== "undefined") {
                myList = JSON.parse(localStorage[mySource]);
            }
        }
        if ($(this).attr('data-lmd-valid-sortList')=='yes') {
            // Sort list alphabetically
            myList.sort();
        }
        
        // Apply jQueryUI autocomplete
        $(this).autocomplete({
            source: myList,
            autoFocus: true,
            delay: 100
        });
    });
    
    
    // Apply dynamic selects
    // e.g.     (static)            <input data-lmd-valid-select='["one","two","three"]'>
    //          (static, sorted)    <input data-lmd-valid-select='["one","two","three"]' data-lmd-valid-sortList="yes">
    //          (dynamic)           <input data-lmd-valid-select='villages'> // !!!!! villages must be deifned in... !!!!!
    $("input[data-lmd-valid-select]").each(function() {
        
        // Get source from data attribute
        mySource = $(this).attr('data-lmd-valid-select');

        if (mySource.substring(0,1)=="[") {
            // Parse "static" (inline) arrays
            myList = JSON.parse(mySource);
        } else {
            // Parse "dynamic" (localStorage) arrays
            myList = JSON.parse(localStorage[mySource]);

            if (typeof localStorage[mySource] !== "undefined" && localStorage[mySource] !== "undefined") {
                myList = JSON.parse(localStorage[mySource]);
            }

        }
        if ($(this).attr('data-lmd-valid-sortList')=='yes') {
            // Sort list alphabetically
            myList.sort();
        }

        // Create select element
        myNewInput = "<select class='dynamicSelect " + $(this).attr('class') + "' id='" + $(this).attr('id') + "' style='" + $(this).attr('style') + "'>";
        myNewInput += "<option value=''></option>";
        for (var i=0; i<myList.length; i++) {
            myNewInput += "<option value='" + myList[i] + "'>" + myList[i] + "</option>";
        }
        myNewInput += "</select>";

        // Insert select element after textbox; remove textbox
        $(this).after(myNewInput);
        $(this).remove();
        
    });
    
    
});


// Apply MySQL-formatted datepicker
// e.g.     $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
//          $(".datepicker").blur(datepickerBlur);
function datepickerBlur(){
    myDate = $(this).val();
    dateRegExp = /[12]\d\d\d-[0-1]\d-[0-3]\d/;
    if ( !dateRegExp.test(myDate) && myDate!="" ) {
        var $mySel = $(this);
        $mySel.val( "" );
        $mySel.attr( "title", "Dates must be in yyyy-mm-dd format." );
        $mySel.tooltip( "show" );
        setTimeout(function(){
            $mySel.tooltip( "destroy" );
        }, 2000);
        $mySel.focus();
    }
}


// Read fields in selection into the return object
function readFieldsIntoObject($inputs){
    
    var myRecord = {};
    
    $($inputs).each(function() {
        if ($(this).attr('type') == 'checkbox') {
            // Handle checkboxes; !!!!! if multiple checkboxes with the same name attribute are selected, only the last selected one is recorded; prevent double selection within formValidate.js !!!!!
            if ( $(this).is(':checked') ) {
                myRecord[$(this).attr('name')] = $(this).attr('value');
            }
        } else {
            // Handle textboxes
            if ( $(this).val() !== '' && $(this).val() !== undefined ) {
                myRecord[$(this).attr('name')] = $(this).val();
            }
        }
    });
    
    return myRecord;
    
}
