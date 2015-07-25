<script>
$(document).ready(function(){

    <?php

        // !!!!! User sets "$indicatorIDs" manually for now !!!!!
        $indIDString = "23,48,49,50,51,52,53,54,55";
        echo "var indIDString = '$indIDString';". "\n\n";

        // Include file that interacts with LMD_REST.php
        set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . "/LastMileData/php/includes" );
        require_once("echoIndicatorsAndValues.php");

    ?>

    // Generate indicatorData object
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

    // !!!!! Static data model for now !!!!!
    var model_konobo = [
        {
            id: 201,
            indicators: [23],
            type: "oneOverTime", // !!!!! use this in an "rv-if" in outer blocks !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 3,
                axisTitles: {y:"Number of CHWLs"} // !!!!! Replace this with "indNameShort" ?????
            },
            displayOrder: 1,
            reports: ['konoboSupervision']
        },
        {
            id: 202,
            indicators: [48],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 1,
                axisTitles: {y:"# visits"}
            },
            displayOrder: 2,
            reports: ['konoboSupervision']
        },
        {
            id: 203,
            indicators: [49],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 1,
                axisTitles: {y:"% attendance"},
                tickFormat: {y:"%"}
            },
            displayOrder: 3,
            reports: ['konoboSupervision']
        },
        {
            id: 204,
            indicators: [50],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 1,
                axisTitles: {y:"# absences"}
            },
            displayOrder: 4,
            reports: ['konoboSupervision']
        },
        {
            id: 205,
            indicators: [51],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 1,
                axisTitles: {y:"% of visits"},
                tickFormat: {y:"%"}
            },
            displayOrder: 5,
            reports: ['konoboSupervision']
        },
        {
            id: 206,
            indicators: [52],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 1,
                axisTitles: {y:"% correct treatment"},
                tickFormat: {y:"%"}
            },
            displayOrder: 6,
            reports: ['konoboSupervision']
        },
        {
            id: 207,
            indicators: [54,55,53], // !!!!! broken because of missing data; doesn't work if 53 is placed first !!!!!
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                legend: "right",
                axisTitles: {y:"Percent of women"}
            },
            roMetadata: {
                indName_short:"Scores", // !!!!! unused
                indName:"Field practical test scores",
                indFormat:"decimal-1",
                indDefinition:"Average scores on practical field tests (out of 15)"
            },
            displayOrder: 7,
            reports: ['konoboSupervision'] // !!!!!
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


    // Replace keys of "data_indicators" with indIDs; create new object "indicatorMetadata"
    indicatorMetadata = {};
    for (var key in data_indicators) {
        indicatorMetadata[data_indicators[key].indID] = data_indicators[key];
    }

    // Merge data into model_konobo
    for (var key in model_konobo) {

        var multiple = model_konobo[key].indicators.length > 1 ? true : false;

        // Add blank "data" property
        model_konobo[key].data = { multiple:multiple, points:[], dates:[], values:[] };
        
        // Add "chartSpecs.div" property
        model_konobo[key].chartSpecs.div = "chart_" + model_konobo[key].id;
        
        // !!!!! add option to take in passed data ?????
        
        // If roMetadata is not specified, get metadata from indicator
        if ( model_konobo[key].roMetadata === undefined ) {
            model_konobo[key].roMetadata = indicatorMetadata[model_konobo[key].indicators[0]];
        }

        for (var key2 in model_konobo[key].indicators) {
            
            var indID = model_konobo[key].indicators[key2];
            var dataArray = indicatorData[indID];
            var valuesArray = [];

            // Pull in recent data (for table)
            for(var i=0; i<model_konobo[key].tableSpecs.numMonths; i++) {
                
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
                    Cut: multiple ? indicatorMetadata[indID].indShortName : 1
                });
            }
            model_konobo[key].data.values.push({name:indicatorMetadata[indID].indShortName, values:valuesArray}); // !!!!!

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

// Pad numbers to two digits ( helper function for mysql_datetime() )
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
</script>

<h1>Konobo Supervision Report <span style="font-size:60%">(updated: 7/12/2015)</span></h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_konobo">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{index | plusOne}}</b>. {{report_object.roMetadata.indName}}</h3>
            <p><b>Definition</b>: {{report_object.roMetadata.indDefinition}}</p>
            <p rv-if="report_object.roMetadata.indTarget"><b>FY15 Target</b>: {{report_object.roMetadata.indTarget | format report_object.roMetadata.indFormat}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-if="report_object.data.multiple">&nbsp;</th>
                    <th rv-each-date="report_object.data.dates">{{date | shortDate}}</th>
                </tr>
                <tr rv-each-values="report_object.data.values">
                    <td rv-if="report_object.data.multiple">{{values.name}}</td>
                    <td rv-each-value="values.values">{{value | format report_object.roMetadata.indFormat}}</td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p rv-if="report_object.roMetadata.indNarrative"><b>Progress-to-goal</b>: {{report_object.roMetadata.indNarrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.chartSpecs.div"></div>
        </div>
    </div>
</div>