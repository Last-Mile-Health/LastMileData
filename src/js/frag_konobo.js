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


        
        
    // !!!!! NEW CODE !!!!!
    // Merge data into model_konobo
    for (var key in model_konobo) {
        
        var M = model_konobo[key];
        
        // Add "multiple" property, which denotes whether this report object contains a single indicator or multiple indicators
        M.multiple = M.indicators.length > 1 ? true : false;

        // Add "chart_div" property
        M.chart_div = "chart_" + M.id;
        
        // Add chart_points array (for Dimple charts)
        M.chart_points = [];
        
        // If roMetadata fields are not specified, get them from getIndicatorMetadata()
        var indID = M.indicators[0];
        var metadata = LMD_dataPortal.getIndicatorMetadata(indID);
        if ( M.roMetadata_name == null || M.roMetadata_name == '' ) {
            M.roMetadata_name = metadata.indName;
        }
        if ( M.roMetadata_format == null || M.roMetadata_format == '' ) {
            M.roMetadata_format = metadata.indFormat;
        }
        if ( M.roMetadata_description == null || M.roMetadata_description == '' ) {
            M.roMetadata_description = metadata.indDefinition;
        }

        for (var key2 in M.indicators) {

            var indID = M.indicators[key2];
            var dataArray = LMD_dataPortal.getIndicatorData(indID);

            // Populate chart_points array (for Dimple charts)
            for(var i=0; i<dataArray.length; i++) {
                M.chart_points.push({
                    Month:dataArray[i].Date,
                    Value:dataArray[i].Value,
                    Cut: M.multiple ? LMD_dataPortal.getIndicatorMetadata(indID).indShortName : 1
                });
            }
        }
    }
    // !!!!! NEW CODE !!!!!
    
    
    // !!!!! NEW CODE !!!!!
    var lastFourMonths = [
        { yearMonth: "2015-4", shortMonth: "Apr '15" },
        { yearMonth: "2015-5", shortMonth: "May '15" },
        { yearMonth: "2015-6", shortMonth: "Jun '15" },
        { yearMonth: "2015-7", shortMonth: "Jul '15" }
    ];
    // !!!!! NEW CODE !!!!!


    // Bind model to DIV
    // !!!!! NEW CODE !!!!!
    rivets.bind($('#dashboardContent'), {
        model_konobo: model_konobo,
        lastFourMonths: lastFourMonths
    });
    // !!!!! NEW CODE !!!!!
    
    
    // !!!!! NEW CODE !!!!!
    var _newDataContainer = {};
    for (var key in data_rawValues) {
        var V = data_rawValues[key];
        _newDataContainer["i_" + V.indID + "_m_" + V.year + "-" + V.month] = V.indValue;
    }
    
    var _newIndContainer = {};
    for (var key in data_indicators) {
        var V = data_indicators[key];
        _newIndContainer[V.indID] = V.indShortName;
    }
    
    // Dynamically populate indicator values
    $(".indValue").each(function(){
        var indID = $(this).attr("data-indid");
        var yearmonth = $(this).attr("data-yearmonth");
        var format = $(this).attr("data-format");
        var indValue = _newDataContainer["i_" + indID + "_m_" + yearmonth];
        indValue = LMD_utilities.format_number(indValue, format, 1) // !!!!! decimal places (currently "1") ?????
        $(this).html(indValue);
    });
    
    // Dynamically populate indicator short names
    $(".indShortName").each(function(){
        var indID = $(this).attr("data-indid");
        var shortName = _newIndContainer[indID];
        $(this).html(shortName);
    });
    // !!!!! NEW CODE !!!!!


    // Create charts
    for(var key in model_konobo) {
        if (key>=0) {

            var RO = model_konobo[key];

            LMD_dimpleHelper.createChart({
                type:RO.chart_type,
                targetDiv: RO.chart_div,
                data: RO.chart_points,
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
