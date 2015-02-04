$(document).ready(function(){
    
    var lmd_indicators;
    
    // Send AJAX request
    $.ajax({
        type: "POST",
        url: "/LastMileData/src/php/getJSON.php",
        data: {'queryString': 'SELECT * FROM lastmile_db.tbl_data_indicators;'},
        dataType: "json",
        success: begin,
        error: function(request, status, error) {
            console.log('ajax error :/');
            console.log(request);
        }
    });
    
    function begin(data) {
        
        lmd_indicators = data;
        updateIndicatorSet(lmd_indicators);
        selectFirstIndicator();
        
        // Handle sidebar clicks
        $('.sidebar ul li').click(function(){
            
            // Switch active sidebar element
            $('.nav-sidebar li').each(function(){
                $(this).removeClass('active');
            });
            $(this).addClass('active');
            
            var indID = $(this).find('a').attr('id');
            displayIndicator(indID.substring(4));

        });
        
        // Handle searches
        
        
        // Change handler for name attribute
        // !!!!! code !!!!!
        
    }
    
    
    // Click handler: Update a record
    $('#ind_update').click(function(){
        
        var vResult = LMD_formValidate.validate($('.stored'));
        
        if ( vResult.result === "fail" ) {
            alert(vResult.errorMessages);
        } else {
            var myRecord = readFieldsIntoObject($('.stored'));
            var queryString = "UPDATE tbl_data_indicators SET ";
            for (field in myRecord) {
                queryString += field + "='"+addslashes(myRecord[field])+"', ";
            }
            queryString = queryString.slice(0,-2);
            queryString += " WHERE pk='" + $('#pk').val() + "';";
            // !!!!! currently giving the wrong pk !!!!!
            console.log(queryString);
        }
        
    });
    
    
    // Click handler: Delete a record
    $('#ind_delete').click(function(){
        $queryString = "DELETE FROM tbl_data_indicators WHERE pk='7';";
        
    });
    
    
    // Displays the indicator in the main page
    function displayIndicator(indID) {

        $('#ind_name2').val(lmd_indicators[indID]['ind_name']);

        for ( field in lmd_indicators[indID] ) {
            $('#' + field).val(lmd_indicators[indID][field]);
        }

    }
    
    
    // Displays the indicator in the main page
    function selectFirstIndicator() {

        // Remove current selection
        $('.nav-sidebar li').each(function(){
            $(this).removeClass('active');
        });

        // Make new selection
        var $sel = $('#searchResults').children(':first');
        $sel.addClass('active');
        displayIndicator($sel.find('a').attr('id').substr(4));

    }
    
    
    // Displays elements in sidebar
    // !!!!! Figure out most efficient argument to pass; currently an array of indIDs !!!!!
    function updateIndicatorSet(indicatorObject) {
        
        $('#searchResults').html('');
        
        for(ind in indicatorObject) {
            
            // !!!!! sort by name !!!!!
            
            var indID = indicatorObject[ind]['pk'];
            var indName = indicatorObject[ind]['ind_name'];
            $('#searchResults').append('<li><a id="ind_' + indID + '">' + indName + '</a></li>');
            
        }
    }
    
    
    // WET with deqa.js
    function addslashes( str ) {
        return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
    }
    
});