<script>
$(document).ready(function(){

    <?php

        // !!!!! User sets "$indicatorIDs" manually for now !!!!!
        // !!!!! Figure out a way to "hoist" this from indIDs specified in "report objects" ?????
        $indIDString = "16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45";
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
            id: 100,
            indicators: [16],
            type: "oneOverTime", // !!!!! use this in an "rv-if" in outer blocks !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 3,
                axisTitles: {y:"Number of CHWs"} // !!!!! Replace this with "indNameShort" ?????
            },
            displayOrder: 1,
            reports: ['konobo'] // !!!!! use this in REST call above !!!!!
        },
        {
            id: 102,
            indicators: [24],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 3,
                axisTitles: {y:"Number of People"}
            },
            displayOrder: 2,
            reports: ['konobo']
        },
        {
            id: 103,
            indicators: [25],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 3,
                axisTitles: {y:"Number of Villages"}
            },
            displayOrder: 3,
            reports: ['konobo']
        },
        {
            id: 104,
            indicators: [28],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 1,
                axisTitles: {y:"% FBD"},
                tickFormat: {y:"%"}
            },
            displayOrder: 4,
            reports: ['konobo']
        },
        {
            id: 105,
            indicators: [17,18],
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                legend: "right",
                axisTitles: {y:"Percent of women"},
                tickFormat: {y:"%"}
            },
            roMetadata: {
                indName_short:"ANC", // !!!!! unused
                indName:"Antenatal care rates",
                indFormat:"percent",
                indDefinition:"Percentage of women who received at least {one, four} ANC visits during the course of their pregnancy (out of all women who delivered in the past month)"
            },
            displayOrder: 5,
            reports: ['konobo'] // !!!!!
        },
        {
            id: 106,
            indicators: [19,20,21,22],
            type: "pieOverTime", // !!!!!
            tableSpecs: {
                numMonths: 1
            },
            chartSpecs: {
                type: "pie",
                size: {x:545, y:300},
                legend: "right",
                xyVars: {x:"Month", y:"Value"},
                axisTitles: {y:"Percent of women"},
                cut: "Cut"
            },
            roMetadata: {
                indName_short:"Source", // !!!!! unused
                indName:"Sick child visit source",
                indFormat:"integer",
                indDefinition:"Percentage of sick child visits that came from each source"
            },
            displayOrder: 6,
            reports: ['konobo'] // !!!!!
        },
        {
            id: 107,
            indicators: [29],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                timeInterval: 3,
                axisTitles: {y:"Percent"},
                tickFormat: {y:"%"}
            },
            displayOrder: 7,
            reports: ['konobo']
        },
        {
            id: 108,
            indicators: [30],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                axisTitles: {y:"# of children"}
            },
            displayOrder: 8,
            reports: ['konobo']
        },
        {
            id: 109,
            indicators: [31,32,33],
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                timeInterval: 3,
                legend: "right",
                axisTitles: {y:"# of children"}
            },
            roMetadata: {
                indName:"Number of sick children treated, by condition",
                indFormat:"integer", // !!!!!
                indDefinition:"Total number of children treated for malaria, diarrhea, or ARI"
            },
            displayOrder: 9,
            reports: ['konobo'] // !!!!!
        },
        {
            id: 110,
            indicators: [34,35,36],
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                timeInterval: 3,
                legend: "right",
                axisTitles: {y:"# of children"}
            },
            roMetadata: {
                indName:"Number of sick children treated, by condition (per 10,000 population)",
                indFormat:"integer", // !!!!!
                indDefinition:"Total number of children treated for malaria, diarrhea, or ARI, per 10,000 population served"
            },
            displayOrder: 10,
            reports: ['konobo'] // !!!!!
        },
        {
            id: 111,
            indicators: [37],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                axisTitles: {y:"# of births"}
            },
            displayOrder: 11,
            reports: ['konobo']
        },
        {
            id: 112,
            indicators: [38,39,40],
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                timeInterval: 1,
                legend: "right",
                axisTitles: {y:"# of deaths"}
            },
            roMetadata: {
                indName:"Number of deaths",
                indFormat:"integer", // !!!!!
                indDefinition:"Total number of deaths recorded by CHWs, by age category"
            },
            displayOrder: 12,
            reports: ['konobo'] // !!!!!
        },
        {
            id: 113,
            indicators: [41,42,43],
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                timeInterval: 1,
                legend: "right",
                axisTitles: {y:"# of deaths"}
            },
            roMetadata: {
                indName:"Number of deaths",
                indFormat:"integer", // !!!!!
                indDefinition:"Total number of under-five deaths recorded by CHWs, by age category (neonatal = 0-28 days, post-neonatal = 29-364 days, child = 1-4 years)"
            },
            displayOrder: 13,
            reports: ['konobo'] // !!!!!
        },
        {
            id: 114,
            indicators: [44,45],
            type: "multipleOverTime", // !!!!!
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:590, y:380},
                timeInterval: 1,
                legend: "right",
                axisTitles: {y:"# of deaths"}
            },
            roMetadata: {
                indName:"Number of movements",
                indFormat:"integer", // !!!!!
                indDefinition:"Total number of people who moved in or out of a community"
            },
            displayOrder: 14,
            reports: ['konobo'] // !!!!!
        },
        {
            id: 115,
            indicators: [27],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                axisTitles: {y:"# entered"}
            },
            displayOrder: 15,
            reports: ['konobo']
        },
        {
            id: 116,
                indicators: [26],
            type: "oneOverTime",
            tableSpecs: {
                numMonths: 4
            },
            chartSpecs: {
                type: "line",
                size: {x:505, y:400},
                axisTitles: {y:"# QA'd"},
                tickFormat: {y:"%"}
            },
            displayOrder: 16,
            reports: ['konobo']
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

<h1>Konobo CHW Report <span style="font-size:60%">(updated: 6/12/2015)</span></h1>

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