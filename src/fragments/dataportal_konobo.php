<script>
$(document).ready(function(){

    <?php

        // !!!!! User sets "$indicatorIDs" manually for now !!!!!
        $indIDString = "16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45";
        echo "var indIDString = '$indIDString';". "\n\n";

        // Include file that interacts with LMD_REST.php
        // This file assigns two javascript objects: "data_rawValues" and "data_indicators"
        set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/src/php/includes" );
        $reportObjectString = "1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16";
        require_once("echoIndicatorsAndValues.php");

    ?>

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
    
    // !!!!! Static data model for now !!!!!
    // !!!!! Replace this with a backbone model !!!!!
//    var model_konobo = [
//        {
//            id: 100,
//            indicators: [16],
//            type: "oneOverTime", // !!!!! use this in an "rv-if" in outer blocks !!!!!
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            chart_timeInterval: 3,
//            displayOrder: 1,
//            reportID: 1 // !!!!! use this in REST call above !!!!!
//        },
//        {
//            id: 102,
//            indicators: [24],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            chart_timeInterval: 3,
//            displayOrder: 2,
//            reportID: 1
//        },
//        {
//            id: 103,
//            indicators: [25],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            chart_timeInterval: 3,
//            displayOrder: 3,
//            reportID: 1
//        },
//        {
//            id: 104,
//            indicators: [28],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            chart_tickFormat: "%",
//            displayOrder: 4,
//            reportID: 1
//        },
//        {
//            id: 105,
//            indicators: [17,18],
//            type: "multipleOverTime", // !!!!!
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 590,
//            chart_size_y: 380,
//            chart_tickFormat: "%",
//            chart_legend: "right",
//            roMetadata_name: "Antenatal care rates",
//            roMetadata_format: "percent",
//            roMetadata_description: "Percentage of women who received at least {one, four} ANC visits during the course of their pregnancy (out of all women who delivered in the past month)",
//            displayOrder: 5,
//            reportID: 1 // !!!!!
//        },
//        {
//            id: 106,
//            indicators: [19,20,21,22],
//            type: "pieOverTime", // !!!!!
//            table_numMonths: 1,
//            chart_type: "pie",
//            chart_size_x: 545,
//            chart_size_y: 300,
//            chart_tickFormat: "%",
//            chart_legend: "right",
//            roMetadata_name: "Sick child visit source",
//            roMetadata_format: "integer",
//            roMetadata_description: "Percentage of sick child visits that came from each source",
//            displayOrder: 6,
//            reportID: 1 // !!!!!
//        },
//        {
//            id: 107,
//            indicators: [29],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            chart_timeInterval: 3,
//            chart_tickFormat: "%",
//            displayOrder: 7,
//            reportID: 1
//        },
//        {
//            id: 108,
//            indicators: [30],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            displayOrder: 8,
//            reportID: 1
//        },
//        {
//            id: 109,
//            indicators: [31,32,33],
//            type: "multipleOverTime", // !!!!!
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 590,
//            chart_size_y: 380,
//            chart_timeInterval: 3,
//            chart_legend: "right",
//            roMetadata_name: "Number of sick children treated, by condition",
//            roMetadata_format: "integer",
//            roMetadata_description: "Total number of children treated for malaria, diarrhea, or ARI",
//            displayOrder: 9,
//            reportID: 1 // !!!!!
//        },
//        {
//            id: 110,
//            indicators: [34,35,36],
//            type: "multipleOverTime", // !!!!!
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 590,
//            chart_size_y: 380,
//            chart_timeInterval: 3,
//            chart_tickFormat: "%",
//            roMetadata_name: "Number of sick children treated, by condition (per 10,000 population)",
//            roMetadata_format: "integer",
//            roMetadata_description: "Total number of children treated for malaria, diarrhea, or ARI, per 10,000 population served",
//            displayOrder: 10,
//            reportID: 1 // !!!!!
//        },
//        {
//            id: 111,
//            indicators: [37],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            displayOrder: 11,
//            reportID: 1
//        },
//        {
//            id: 112,
//            indicators: [38,39,40],
//            type: "multipleOverTime", // !!!!!
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 590,
//            chart_size_y: 380,
//            chart_legend: "right",
//            roMetadata_name: "Number of deaths",
//            roMetadata_format: "integer",
//            roMetadata_description: "Total number of deaths recorded by CHWs, by age category",
//            displayOrder: 12,
//            reportID: 1 // !!!!!
//        },
//        {
//            id: 113,
//            indicators: [41,42,43],
//            type: "multipleOverTime", // !!!!!
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 590,
//            chart_size_y: 380,
//            chart_legend: "right",
//            roMetadata_name: "Number of deaths",
//            roMetadata_format: "integer",
//            roMetadata_description: "Total number of under-five deaths recorded by CHWs, by age category (neonatal = 0-28 days, post-neonatal = 29-364 days, child = 1-4 years)",
//            displayOrder: 13,
//            reportID: 1 // !!!!!
//        },
//        {
//            id: 114,
//            indicators: [44,45],
//            type: "multipleOverTime", // !!!!!
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 590,
//            chart_size_y: 380,
//            chart_legend: "right",
//            roMetadata_name: "Number of movements",
//            roMetadata_format: "integer",
//            roMetadata_description: "Total number of people who moved in or out of a community",
//            displayOrder: 14,
//            reportID: 1 // !!!!!
//        },
//        {
//            id: 115,
//            indicators: [27],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            displayOrder: 15,
//            reportID: 1
//        },
//        {
//            id: 116,
//            indicators: [26],
//            type: "oneOverTime",
//            table_numMonths: 4,
//            chart_type: "line",
//            chart_size_x: 505,
//            chart_size_y: 400,
//            chart_tickFormat: "%",
//            displayOrder: 16,
//            reportID: 1
//        }
//    ];
    
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
                    if (model_konobo[key].data.dates.indexOf(dataArray[i].Date) === -1) {
                        
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

// Pad numbers to two digits ( helper function for mysql_datetime() )
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
</script>

<h1>Konobo CHW Report <span style="font-size:60%">(updated: 6/12/2015)</span></h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_konobo">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.roMetadata_name}}</h3>
            <p><b>Definition</b>: {{report_object.roMetadata_description}}</p>
            <p rv-if="report_object.roMetadata_target"><b>FY15 Target</b>: {{report_object.roMetadata_target | format report_object.roMetadata_format}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-if="report_object.data.multiple">&nbsp;</th>
                    <th rv-each-date="report_object.data.dates">{{date | shortDate}}</th>
                </tr>
                <tr rv-each-values="report_object.data.values">
                    <td rv-if="report_object.data.multiple">{{values.name}}</td>
                    <td rv-each-value="values.values">{{value | format report_object.roMetadata_format}}</td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p rv-if="report_object.roMetadata_narrative"><b>Progress-to-goal</b>: {{report_object.roMetadata_narrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.chart_div"></div>
        </div>
    </div>
</div>