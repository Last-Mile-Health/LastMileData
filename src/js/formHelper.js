$(document).ready(function() {
    
    
    // Apply jQueryUI autocomplete
    // e.g.     (static)                <input data-lmd-valid-autoC='["one","two","three"]'>
    //          (static, not sorted)    <input data-lmd-valid-autoC='["one","two","three"]' data-lmd-valid-sortList="no">
    //          (dynamic)               <input data-lmd-valid-autoC="villages"> // villages is a key in localStorage
    //          (dynamic)               <input data-lmd-valid-autoC="villages" data-lmd-valid-restrict> // user cannot enter an item that is not on the list
    $("input[data-lmd-valid-autoC]").each(function() {
        
        // Get source from data attribute
        mySource = $(this).attr('data-lmd-valid-autoC');
        
        if (mySource.substring(0,1)=="[") {
            // Parse "static" (inline) arrays
            var myList = JSON.parse(mySource);
        } else {
            // Parse "dynamic" (localStorage) arrays
            myList = JSON.parse(localStorage[mySource]);
            
            if (typeof localStorage[mySource] !== "undefined" && localStorage[mySource] !== "undefined") {
                myList = JSON.parse(localStorage[mySource]);
            }
        }
        if (!$(this).attr('data-lmd-valid-sortList')=='no') {
            // Sort list alphabetically
            myList.sort();
        }
        
        // Apply jQueryUI autocomplete
        $(this).autocomplete({
            source: myList,
            autoFocus: true,
            delay: 0,
            change: function(ev) {
                if ( $(this)[0].attributes['data-lmd-valid-restrict']!==undefined ) {
                    var key = $(ev.target.attributes['data-lmd-valid-autoC']).val();
                    var myList = key.substring(0,1)=="[" ? JSON.parse(key) : JSON.parse(localStorage[key]);
                    if (myList.indexOf($(this)[0].value)===-1) {
                        alert('Please select an item from the list.')
                        $(this).val('');
                        $(this).focus();
                    }
                }
            }
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
            var myList = JSON.parse(mySource);
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
        var required = $(this).attr('data-lmd-valid-required') === undefined ? '' : 'data-lmd-valid-required';
        myNewInput = "<select class='dynamicSelect " + $(this).attr('class') + "' name='" + $(this).attr('name') + "' " + required + ">";
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
