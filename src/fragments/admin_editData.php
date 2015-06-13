<script>
$(document).ready(function(){
    
    $table = $('#admin_editData_table');
    var numRows = 1, indicatorList = 0, indicatorValues = 0;
    
    // Send first AJAX request (list of indicators)
    $.ajax({
        type: "POST",
        url: "/LastMileData/src/php/getJSON.php",
        data: {'queryString': 'SELECT `indID`, `indName`, `indCategory`, `indFormat` FROM lastmile_dataportal.tbl_indicators;'},
        dataType: "json",
        success: function(data){ indicatorList = data; },
        error: handleAJAXErrors
    });
    
    // Send second AJAX request (indicators values)
    $.ajax({
        type: "POST",
        url: "/LastMileData/src/php/getJSON.php",
        data: {'queryString': 'SELECT `month`, `year`, `indID`, `indValue` FROM lastmile_dataportal.tbl_values;'},
        dataType: "json",
        success: function(data){ indicatorValues = data; },
        error: handleAJAXErrors
    });
    
    // Run the code below when both AJAX requests complete
    var myTimer = setInterval(function(){

        if(indicatorList!==0 && indicatorValues!==0) {
            
            // !!!!! Sort object by indCategory, indName !!!!!
            
            // Append rows to table
            for (key in indicatorList) {
                var x = indicatorList[key];
                var rowString = "<tr data-row='" + numRows + "' data-indID='" + x.indID + "' data-indFormat='" + x.indFormat + "'>";
                rowString += "<td class='pad'>" + x.indCategory + "</td>";
                rowString += "<td class='pad'>" + x.indName + "</td></tr>";
                $table.append(rowString);
                numRows++;
            }
            
            // Generate date columns
            var minDate = { year:2014, month:12 }; // !!!!! Generate this based on business rules !!!!!

            var today = new Date();
            var year = today.getFullYear();
            var month = today.getMonth()+1;
            var maxDate = { year:year, month:month };
            var numMonths = (12*(maxDate.year-minDate.year))+(maxDate.month-minDate.month)+1;

            for(i=1;i<=numMonths;i++) {
                var myDate = new Date(month + "-" + "1-" + year);
                var myDate = myDate.toDateString().slice(4,7) + " '" + year%100;

                // Set header row
                $('#admin_editData_header').append("<th data-col='" + i + "' data-month='" + month + "' data-year='" + year + "'>&nbsp;" + myDate + "</th>");

                // Set subsequent rows
                for (var j=1; j<=numRows; j++) {
                    $("tr[data-row='" + j + "']").append("<td data-col='" + i + "'><input class='admin_input'></td>");
                }

                // Decrement date
                if (month===1) {
                    month = 12;
                    year = year-1;
                } else {
                    month = month-1;
                }
            }
            
            // Write MySQL data to table
            for (key in indicatorValues) {
                var x = indicatorValues[key];
                
                // !!!!! Format value !!!!!
                var formattedValue = x.indValue;
                
                var col = $("#admin_editData_header th[data-year=" + x.year + "][data-month=" + x.month + "]").attr('data-col');
                $("#admin_editData_table tr[data-indID=" + x.indID + "]").find('td[data-col=' + col + ']').find('input').val(formattedValue);
            }
            
            $('#admin_editData_table input').change(function(){
                var col = $(this).parent().attr('data-col');
                var mth = $('th[data-col=' + col + ']').attr('data-month');
                var yr = $('th[data-col=' + col + ']').attr('data-year');
                var indID = $(this).closest('tr').attr('data-indID');
                var indFormat = $(this).closest('tr').attr('data-indFormat');
                var oldValue = $(this).val();
                
                // Validate field
                switch(indFormat) {
                    case "integer":
                        var newValue = oldValue; // !!!!! validate !!!!!
                        break;
                    case "dollars":
                        var newValue = oldValue; // !!!!! validate !!!!!
                        break;
                    case "percent":
                        var newValue = oldValue; // !!!!! validate !!!!!
                        break;
                    default:
                        var newValue = oldValue;
                }
                
                queryData.add(mth, yr, indID, newValue);
            });

            clearInterval(myTimer);
        }

    },500);
    
    
    
    var queryData = {
        data: {},
        add: function(month, year, indID, value){
            this.data[month + '-' + year + '-' + indID] = {month: month, year: year, indID: indID, value: value};
        }
    };
    
    $('#admin_editData_submit').click(function(){
        
        // Manipulate DOM
        $submit = $(this);
        $cancel = $('#admin_editData_cancel');
        $submit.prop('disabled','disabled');
        $submit.html("<img src='/LastMileData/res/ajax-loader_v20140916.gif'>")
        $cancel.prop('disabled','disabled');
        
        // Parse queryString
        var queryString = "";
        for(key in queryData.data) {
            var x = queryData.data[key];
            queryString += "REPLACE INTO lastmile_dataportal.tbl_values (`month`,`year`,`indID`,`indValue`) VALUES ";
            queryString += "('" + x.month + "','" + x.year + "','" + x.indID + "','" + x.value + "'" + ");";
        }
        
        var myData = {'queryString': queryString, 'rKey': 1, 'transaction': 1} ; // !!!!! rKey is not used functionally here; can ajaxSendQuery work without it ?????
        
        // Send AJAX request
        $.ajax({
            type: "POST",
            url: "/LastMileData/src/php/ajaxSendQuery.php",
            data: myData,
            dataType: "json",
            success: function(data) {
                setTimeout(function(){
                    // Reset DOM
                    $cancel.prop('disabled','');
                    $submit.prop('disabled','');
                    $submit.html("Submit");
                },500)
            },
            error: function(request, status, error) {
                setTimeout(function(){
                    // Error message
                    alert('Error. Could not reach the database. Please try again.');

                    // Reset DOM
                    $cancel.prop('disabled','');
                    $submit.prop('disabled','');
                    $submit.html("Submit");
                },500)
              }
        });
        
    });
    
    $('#admin_editData_cancel').click(function(){
        // Clear data
        $('#admin_editData_table input').each(function(){
            $(this).val('');
        });
        
        // Write MySQL data to table
        for (key in indicatorValues) {
            var x = indicatorValues[key];
            var col = $("#admin_editData_header th[data-year=" + x.year + "][data-month=" + x.month + "]").attr('data-col');
            $("#admin_editData_table tr[data-indID=" + x.indID + "]").find('td[data-col=' + col + ']').find('input').val(x.indValue);
        }
    });
    
});


function handleAJAXErrors(request) {
    // !!!!! Build this out !!!!!
    console.log('ajax error :/'); console.log(request);
}

</script>

<style>
    #admin_editData_table {
        padding:5px;
        margin:20px;
    }
    #admin_editData_table th {
        border:2px solid white;
        color:white;
        background:darkcyan;
    }
    #admin_editData_table td {
        border:2px solid white;
        background:#eee;
    }
    .pad {
        padding-left:10px;
        padding-right:10px;
    }
    .admin_input {
        width:70px;
        border:none;
        padding:1px;
        margin-right:1px;
        margin-left:1px;
        background:#eee;
    }
    .admin_input:hover {
        background:lightcyan;
        cursor:pointer;
    }
    .admin_input:focus {
        background:lightgreen;
    }
</style>

<div style='overflow:scroll; height:85%;'>
    <table id='admin_editData_table'>
        <tr id='admin_editData_header'> <!-- !!!!! will need to visually freeze top row !!!!! -->
            <th class='pad'>Category</th>
            <th class='pad'>Indicator name</th>
        </tr>
    </table>
</div>

<div style="margin:20px; text-align:center">
    <button id="admin_editData_submit" class="btn btn-success" style="width:250px">Submit</button>
    <button id="admin_editData_cancel" class="btn btn-danger" style="width:250px">Cancel</button>
</div>
