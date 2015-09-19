$(document).ready(function(){

    // Generate indicatorData object
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
    for (var key in data_rawValues) {
        indicatorData.add(data_rawValues[key].indID, data_rawValues[key].month, data_rawValues[key].year, data_rawValues[key].indValue);
    }
    
    var model_ebola = [
        {
            id: 99,
            indicators: [46,47],
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                timeInterval: 1,
                legend: "right",
                axisTitles: {y:"# of people"}
            },
            roMetadata: {
                indName:"Ebola screening and education",
                indFormat:"integer", // !!!!!
                indDefinition:"Total number of people screened for Ebola and educated about Ebola"
            },
            displayOrder: 9,
            reports: ['ebola'] // !!!!!
        }
    ];
    
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

    // Sort model_ebola by "displayOrder"
    model_ebola.sort(function(a,b){
        if (Number(a.displayOrder) < Number(b.displayOrder)) {
            return -1;
        }
        else if (Number(a.displayOrder) > Number(b.displayOrder)) {
            return 1;
        } else {
            return 0;
        }
    });


    // Replace keys of "data_indicators" with indIDs; create new object "indicatorMetadata"
    indicatorMetadata = {};
    for (var key in data_indicators) {
        indicatorMetadata[data_indicators[key].indID] = data_indicators[key];
    }

    // Merge data into model_ebola
    for (var key in model_ebola) {

        var multiple = model_ebola[key].indicators.length > 1 ? true : false;

        // Add blank "data" property
        model_ebola[key].data = { multiple:multiple, points:[], dates:[], values:[] };
        
        // Add "chartSpecs.div" property
        model_ebola[key].chartSpecs.div = "chart_" + model_ebola[key].id;
        
        // !!!!! add option to take in passed data ?????
        
        // If roMetadata is not specified, get metadata from indicator
        if ( model_ebola[key].roMetadata === undefined ) {
            model_ebola[key].roMetadata = indicatorMetadata[model_ebola[key].indicators[0]];
        }

        for (var key2 in model_ebola[key].indicators) {
            
            var indID = model_ebola[key].indicators[key2];
            var dataArray = indicatorData[indID];
            var valuesArray = [];

            // Pull in recent data (for table)
            for(var i=0; i<model_ebola[key].tableSpecs.numMonths; i++) {
                
                if (dataArray !== undefined && dataArray[i] !== undefined) {
                    
                    // Create "recent data" array
                    valuesArray.push(dataArray[i].Value);
                    
                    // !!!!! this code will break if there are missing data points !!!!!
                    // !!!!! also modify this code to manually truncate the dataset (e.g. last 12 months) !!!!!
                    if (model_ebola[key].data.dates.indexOf(dataArray[i].Date) === -1) {
                        
                        // Create "recent data dates" array
                        model_ebola[key].data.dates.push(dataArray[i].Date);
                    }
                    
                }
                
            }

            // Reverse "recent data" array
            valuesArray.reverse();

            // Populate data points array for chart
            for(var i=0; i<dataArray.length; i++) {
                model_ebola[key].data.points.push({
                    Month:dataArray[i].Date,
                    Value:dataArray[i].Value,
                    Cut: multiple ? indicatorMetadata[indID].indShortName : 1
                });
            }
            model_ebola[key].data.values.push({name:indicatorMetadata[indID].indShortName, values:valuesArray}); // !!!!!

        }
        
        // Reverse "recent data dates" array
        model_ebola[key].data.dates.reverse();

    }

    // Bind model to DIV
    rivets.bind($('#dashboardContent'), {model_ebola: model_ebola});
    
    // Create charts
    for(var key in model_ebola) {
        if (key>=0) {

            var RO = model_ebola[key];

            LMD_dimpleHelper.createChart({
                type:RO.chartSpecs.type,
                targetDiv: RO.chartSpecs.div,
                data: RO.data.points,
                colors: RO.chartSpecs.colors || "default",
                timeInterval: RO.chartSpecs.timeInterval || 1, // !!!!! calculate this automatically
                size: RO.chartSpecs.size,
                xyVars: {x:"Month", y:"Value"},
                axisTitles: RO.chartSpecs.axisTitles,
                cut: "Cut",
                legend: RO.chartSpecs.legend || "",
                tickFormat: RO.chartSpecs.tickFormat
            });

        }
    }

});
