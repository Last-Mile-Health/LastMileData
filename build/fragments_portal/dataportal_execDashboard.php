<script>
$(document).ready(function(){

    <?php

        // !!!!! User sets "$indicatorIDs" manually for now !!!!!
        $indIDString = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15";
        echo "var indIDString = '$indIDString';". "\n\n";
        
        // Include file that interacts with LMD_REST.php
        set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php-html/includes" );
        require_once("echoIndicatorsAndValues.php");

    ?>
    
    // Sort "data_indicators" by indID
    // !!!!! replace this !!!!!
    data_indicators.sort(function(a,b){
        if (Number(a.indID) < Number(b.indID)) {
            return -1;
        }
        else if (Number(a.indID) > Number(b.indID)) {
            return 1;
        } else {
            return 0;
        }
    });

    // Create "indicatorData" object to hold data from data_rawValues
    var indicatorData = {
        add: function(indID, month, year, value) {
            var obj = {};
            obj.Date = year + "-" + twoDigits(month) + "-01";
            obj.Month = Number(month);
            obj.Year = Number(year);
            obj.Value = Number(value);
            this[indID] = this[indID] || [];
            this[indID].push(obj);
        }
    };

    // Add data to "indicatorData" (enforce business rule: don't display data from the previous month until the 12th of this month)
    var todayYear = moment().format('YYYY');
    var todayMonth = moment().format('M');
    var todayDay = moment().format('D');
    var latestTotalMonthAllowed = todayMonth + (12*todayYear) - 1;
    if (todayDay < 12) {
        latestTotalMonthAllowed--;
    }

    for (var key in data_rawValues) {
        
        var indID = data_rawValues[key].indID;
        var month = data_rawValues[key].month;
        var year = data_rawValues[key].year;
        var indValue = data_rawValues[key].indValue;
        var totalMonth = Number(month) + (12*Number(year));
        
        if (totalMonth <= latestTotalMonthAllowed) {
            indicatorData.add(indID, month, year, indValue);
        }
        
    }

    // Sort indicatorData by date
    for (var key in indicatorData) {
        if(key!=='add') {
            indicatorData[key].sort(function(a,b){
                if ( (a.Year*12)+a.Month > (b.Year*12)+b.Month ) {
                    return -1;
                }
                if ( (a.Year*12)+a.Month < (b.Year*12)+b.Month ) {
                    return 1;
                } else {
                    return 0;
                }
            });
        }
    }

    // Add recent data (last 4 months) to data_indicators
    for (var key in data_indicators){

        var indID = data_indicators[key].indID;

        // Add divID property, for Dimple graph
        data_indicators[key].divID = "chart_" + indID;

        // Add most recent 4 data values
        data_indicators[key].recentData = [];
        for(var i=0; i<4; i++) {
            if (indicatorData[indID] !== undefined && indicatorData[indID][i] !== undefined) {
                data_indicators[key].recentData.push({
                    date: indicatorData[indID][i].Date,
                    value: indicatorData[indID][i].Value
                });
            }
        }
        data_indicators[key].recentData.reverse();

    }

    // !!!!! NEW CODE START !!!!!

    // Build model_execDashboard
    var model_execDashboard = [];
    for (var key in data_indicators){
        
        var currentIndicator = data_indicators[key];
        
        model_execDashboard.push({
            type: "oneOverTime",
            chartSpecs: {
                div: "chart_" + currentIndicator.indID
            },
            indicatorMetadata: currentIndicator // !!!!! need to re-assign names of "indicator fields" to names of "RO fields" !!!!!
        });
        
    }
    
    // Bind model to DIV
    rivets.bind($('#dashboardContent'), {model_execDashboard: model_execDashboard});
    
    // Generate D3/Dimple Line graphs
    for(var key in model_execDashboard) {
        if (key>=0) {

            var RO = model_execDashboard[key];
            
            var indID = RO.indicatorMetadata.indID;

            var numDataPoints = indicatorData[indID].length || 1;
            var timeInterval = Math.ceil(numDataPoints/12);

            // !!!!! Temp tick format code: START !!!!!
            var tempTick = (indID==11||indID==13) ? "%" : "";
            // !!!!! Temp tick format code: END !!!!!

            LMD_dimpleHelper.createChart({
                type:"line",
                targetDiv: RO.chartSpecs.div,
                data: indicatorData[indID],
                colors: ["#F79646"],
                legend: RO.chartSpecs.legend || "",
                timeInterval: timeInterval,
                size: {x:505, y:400},
                xyVars: {x:"Date", y:"Value"},
                // !!!!! add y-axis label !!!!!

                // !!!!! Temp tick format code: START !!!!!
                tickFormat: {y:tempTick}
                // !!!!! Temp tick format code: END !!!!!
            });

        }
    }

});


// Pad numbers to two digits ( helper function for mysql_datetime() )
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
</script>

<h1>Executive Dashboard <span style="font-size:60%">(updated: 7/12/2015)</span></h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_execDashboard">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.indicatorMetadata.indName}}</h3>
            <p><b>Definition</b>: {{report_object.indicatorMetadata.indDefinition}}</p>
            <p rv-if="report_object.indicatorMetadata.indTarget"><b>FY15 Target</b>: {{report_object.indicatorMetadata.indTarget | format report_object.indicatorMetadata.indFormat}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-each-rdata="report_object.indicatorMetadata.recentData">{{rdata.date | shortDate}}</th>
                </tr>
                <tr>
                    <td rv-each-rdata="report_object.indicatorMetadata.recentData">{{rdata.value | format report_object.indicatorMetadata.indFormat}}</td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p rv-if="report_object.indicatorMetadata.indNarrative"><b>Progress-to-goal</b>: {{report_object.indicatorMetadata.indNarrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.indicatorMetadata.divID"></div>
        </div>
    </div>
</div>