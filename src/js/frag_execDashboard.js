$(document).ready(function(){

    // Sort "data_indicators" by indID
    // !!!!! replace this !!!!!
    data_indicators.sort(function(a,b){
        var order = [1,2,3,4,7,8,56,57,58,59,60,14,15];
        if ( order.indexOf(Number(a.indID)) < order.indexOf(Number(b.indID)) ) {
            return -1;
        } else {
            return 1;
        }
//        if (Number(a.indID) < Number(b.indID)) {
//            return -1;
//        }
//        else if (Number(a.indID) > Number(b.indID)) {
//            return 1;
//        } else {
//            return 0;
//        }
    });

    // Create "indicatorData" object to hold data from data_rawValues
    var indicatorData = {
        add: function(indID, month, year, value) {
            var obj = {};
            obj.Date = year + "-" + LMD_utilities.twoDigits(month) + "-01";
            obj.Month = Number(month);
            obj.Year = Number(year);
            obj.Value = Number(value);
            this[indID] = this[indID] || [];
            this[indID].push(obj);
        }
    };

    // Add data to "indicatorData" (enforce business rule: don't display data from the previous month until the 12th of this month)
    var todayYear = moment().format('YYYY'),
        todayMonth = moment().format('M'),
        todayDay = moment().format('D'),
        latestTotalMonthAllowed = Number(todayMonth) + (12*Number(todayYear)) - 1;
    if (todayDay < 12) {
        latestTotalMonthAllowed--;
    }
    for (var key in data_rawValues) {
        var indID = data_rawValues[key].indID,
            month = data_rawValues[key].month,
            year = data_rawValues[key].year,
            indValue = data_rawValues[key].indValue,
            totalMonth = Number(month) + (12*Number(year));
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
            var tempTick = (indID==58||indID==60) ? "%" : "";
//            var tempTick = (indID==11||indID==13) ? "%" : "";
            // !!!!! Temp tick format code: END !!!!!
            
            // !!!!! Temp min dates: START !!!!!
            if (indID==56||indID==57||indID==58||indID==59||indID==60) {
                var overrideMin = "2015-06-15";
                var overrideMax = "2015-08-01";
            } else {
                var overrideMin = "",
                    overrideMax = "";
            }
            // !!!!! Temp min dates: END !!!!!
            
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

                // !!!!! Temp code: START !!!!!
                tickFormat: {y:tempTick},
                overrideMin: overrideMin,
                overrideMax: overrideMax
                // !!!!! Temp code: END !!!!!
            });

        }
    }

});
