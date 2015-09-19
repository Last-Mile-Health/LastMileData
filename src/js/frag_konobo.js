$(document).ready(function(){
    
    // Add "raw data" from data_rawValues to LMD_dataPortal
    for (var key in data_rawValues) {
        var indID = data_rawValues[key].indID;
        var month = data_rawValues[key].month;
        var year = data_rawValues[key].year;
        var value = data_rawValues[key].indValue;
        LMD_dataPortal.addIndicatorData(indID, month, year, value);
    }

    // Sort indicator data by date
     LMD_dataPortal.sortByDate();

    // Populate "LMD_dataPortal.indicatorMetadata"
    for (var key in data_indicators) {
        var indID = data_indicators[key].indID;
        var metadata = data_indicators[key];
        delete metadata.indID;
        LMD_dataPortal.addIndicatorMetadata(indID, metadata);
    }

    // Transform indicator strings into arrays
    for (var key in model_konobo) {
        model_konobo[key].indicators = model_konobo[key].indicators.split(",");
    }
    
    // Sort model_konobo by "displayOrder"
    model_konobo.sort(function(a,b){
        if (Number(a.displayOrder) < Number(b.displayOrder)) {
            return -1;
        }
        else if (Number(a.displayOrder) > Number(b.displayOrder)) {
            return 1;
        } else {
            return 0;
        }
    });


    // Merge data into model_konobo
    for (var key in model_konobo) {

        var multiple = model_konobo[key].indicators.length > 1 ? true : false;

        // Add blank "data" property
        model_konobo[key].data = { multiple:multiple, points:[], dates:[], values:[] };
        
        // Add "chart_div" property
        model_konobo[key].chart_div = "chart_" + model_konobo[key].id;
        
        // !!!!! add option to take in passed data ?????
        
        // If roMetadata fields are not specified, get them from getIndicatorMetadata()
        var indID = model_konobo[key].indicators[0];
        var metadata = LMD_dataPortal.getIndicatorMetadata(indID);
        if ( model_konobo[key].roMetadata_name == null || model_konobo[key].roMetadata_name == '' ) {
            model_konobo[key].roMetadata_name = metadata.indName;
        }
        if ( model_konobo[key].roMetadata_format == null || model_konobo[key].roMetadata_format == '' ) {
            model_konobo[key].roMetadata_format = metadata.indFormat;
        }
        if ( model_konobo[key].roMetadata_description == null || model_konobo[key].roMetadata_description == '' ) {
            model_konobo[key].roMetadata_description = metadata.indDefinition;
        }

        for (var key2 in model_konobo[key].indicators) {
            
            var indID = model_konobo[key].indicators[key2];
            var dataArray = LMD_dataPortal.getIndicatorData(indID);
            var valuesArray = [];

            // Pull in recent data (for table)
            for(var i=0; i<model_konobo[key].table_numMonths; i++) {
                
                if (dataArray !== undefined && dataArray[i] !== undefined) {
                    
                    // Create "recent data" array
                    valuesArray.push(dataArray[i].Value);
                    
                    // !!!!! this code will break if there are missing data points !!!!!
                    // !!!!! also modify this code to manually truncate the dataset (e.g. last 12 months) !!!!!
//if (i===0) {
//console.log('before');
//console.log(model_konobo[key].data.dates.indexOf(dataArray[i].Date));
//}
                    if (model_konobo[key].data.dates.indexOf(dataArray[i].Date) === -1) {
//if (i===0) {
//console.log(dataArray[i].Date);
//}
                        
                        // Create "recent data dates" array
                        model_konobo[key].data.dates.push(dataArray[i].Date);
                    }
                    
                }
                
            }

            // Reverse "recent data" array
            valuesArray.reverse();

            // Populate data points array for chart
            for(var i=0; i<dataArray.length; i++) {
                model_konobo[key].data.points.push({
                    Month:dataArray[i].Date,
                    Value:dataArray[i].Value,
                    Cut: multiple ? LMD_dataPortal.getIndicatorMetadata(indID).indShortName : 1
                });
            }
            model_konobo[key].data.values.push({
                name:LMD_dataPortal.getIndicatorMetadata(indID).indShortName,
                values:valuesArray
            });

        }
        
        // Reverse "recent data dates" array
        model_konobo[key].data.dates.reverse();

    }

    // Bind model to DIV
    rivets.bind($('#dashboardContent'), {model_konobo: model_konobo});
    console.log(model_konobo);
    
    // Create charts
    for(var key in model_konobo) {
        if (key>=0) {

            var RO = model_konobo[key];

            LMD_dimpleHelper.createChart({
                type:RO.chart_type,
                targetDiv: RO.chart_div,
                data: RO.data.points,
                colors: RO.chart_colors || "default",
                timeInterval: RO.chart_timeInterval || 1, // !!!!! calculate this automatically
                size: { x:RO.chart_size_x, y:RO.chart_size_y },
                xyVars: { x:"Month", y:"Value" },
//                axisTitles: RO.chartSpecs.axisTitles, // !!!!! use indNameShort !!!!!
                cut: "Cut",
                legend: RO.chart_legend || "",
                tickFormat: { y:RO.chart_tickFormat }
            });

        }
    }

});
