<script>
$(document).ready(function(){

    <?php

        // !!!!! User sets "$indicatorIDs" manually for now !!!!!
        $indIDString = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15";
        echo "var indIDString = '$indIDString';". "\n\n";

        // Initiate/configure CURL session
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        // Echo JSON (indicator METADATA)
        $url1 = $_SERVER['HTTP_HOST'] . "/LastMileData/src/php/LMD_REST.php/indicators/$indIDString";
        curl_setopt($ch,CURLOPT_URL,$url1);
        $json1 = curl_exec($ch);

        // Echo JSON (indicator DATA)
        $url2 = $_SERVER['HTTP_HOST'] . "/LastMileData/src/php/LMD_REST.php/indicatorvalues/$indIDString";
        curl_setopt($ch,CURLOPT_URL,$url2);
        $json2 = curl_exec($ch);

        // Close CURL session and echo JSON
        curl_close($ch);
        echo "var data_indicators = $json1;". "\n\n";
        echo "var data_rawValues = $json2;". "\n\n";

    ?>
    
console.log("data_rawValues");
console.log(data_rawValues);
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

    // Transform data_rawValues (raw indicator value data)
    // !!!!! ENFORCE BUSINESS LOGIC HERE: filter out all dates that are before the 12th of the current month !!!!!
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
    for (var key in data_rawValues) {
        indicatorData.add(data_rawValues[key].indID, data_rawValues[key].month, data_rawValues[key].year, data_rawValues[key].indValue);
    }
console.log("indicatorData");
console.log(indicatorData);

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
console.log("data_indicators[key].recentData: " + key);
console.log(data_indicators[key].recentData);

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
    
    
    rivets.bind($('#dashboardContent'), {model_execDashboard: model_execDashboard});
    
    
    
    // !!!!! NEW CODE END !!!!!


    // Bind model to DIV
//    rivets.bind($('#dashboardContent'), {data_indicators: data_indicators});

    // Generate D3/Dimple Line graphs
//    for(var i=0; i<indicatorIDs.length; i++) {
    for(var key in model_execDashboard) {
        if (key>=0) {

    //        var indID = indicatorIDs[i];
            var RO = model_execDashboard[key];
            
            var indID = RO.indicatorMetadata.indID;

            var numDataPoints = indicatorData[indID].length; // !!!!!
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

<h1>Executive Dashboard <span style="font-size:60%">(updated: 6/12/2015)</span></h1>

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