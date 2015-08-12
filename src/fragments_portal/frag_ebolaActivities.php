<script>
$(document).ready(function(){

    <?php

        // !!!!! User sets "$indicatorIDs" manually for now !!!!!
        $indIDString = "46,47";
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


// Pad numbers to two digits ( helper function for mysql_datetime() )
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
</script>

<h1>Ebola activities <span style="font-size:60%">(updated: 8/12/2015)</span></h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_ebola">
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

<h3><b>2</b>. Ebola health worker trainings</h3>
<table class="table table-striped table-hover">
    <tr>
        <th rowspan='2'>Health Worker</th>
        <th colspan='5'>Grand Gedeh</th>
        <th colspan='5'>Rivercess</th>
        <th colspan='5'>Cumulative Total</th>
    </tr>
    <tr>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Apr '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Apr '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Feb '15</th>
        <th>Mar '15</th>
        <th>Apr '15</th>
    </tr>
    <tr>
        <td>CHWs</td>
        <td>55</td>
        <td></td>
        <td></td>
        <td></td>
        <td>13</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>55</td>
        <td>55</td>
        <td>55</td>
        <td>55</td>
        <td>68</td>
    </tr>
    <tr>
        <td>CHW Leaders</td>
        <td>4</td>
        <td></td>
        <td></td>
        <td></td>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>4</td>
        <td>4</td>
        <td>4</td>
        <td>4</td>
        <td>6</td>
    </tr>
    <tr>
        <td>Community Clinical Supervisors</td>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>2</td>
        <td>2</td>
        <td>2</td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td>gCHVs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>77</td>
        <td>36</td>
        <td></td>
        <td></td>
        <td></td>
        <td>77</td>
        <td>113</td>
        <td>113</td>
        <td>113</td>
        <td>113</td>
    </tr>
    <tr>
        <td>TTMs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>60</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>60</td>
        <td>60</td>
    </tr>
    <tr>
        <td>CHSSs</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>10</td>
        <td>8</td>
        <td></td>
        <td></td>
        <td></td>
        <td>10</td>
        <td>18</td>
        <td>18</td>
        <td>18</td>
        <td>18</td>
    </tr>
    <tr>
        <td>Facility staff</td>
        <td>118</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>222</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>340</td>
        <td>340</td>
        <td>340</td>
        <td>340</td>
        <td>340</td>
    </tr>
    <tr>
        <td>CHT staff</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>11</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>11</td>
        <td>11</td>
        <td>11</td>
        <td>11</td>
        <td>11</td>
    </tr>
    <tr>
        <td>CHC Members</td>
        <td>46</td>
        <td>108</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>573</td>
        <td></td>
        <td></td>
        <td>46</td>
        <td>154</td>
        <td>727</td>
        <td>727</td>
        <td>727</td>
    </tr>
    <tr>
        <td>Community members</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>32</td>
        <td>5</td>
        <td></td>
        <td></td>
        <td></td>
        <td>32</td>
        <td>37</td>
        <td>37</td>
        <td>37</td>
        <td>37</td>
    </tr>
    <tr>
        <td colspan='11'><b>TOTAL: </b></td>
        <td><b>577</b></td>
        <td><b>734</b></td>
        <td><b>1,307</b></td>
        <td><b>1,367</b></td>
        <td><b>1,382</b></td>
    </tr>
</table>
<hr>

<h3><b>3</b>. Ebola facility trainings</h3>
<table class="table table-striped table-hover">
    <tr>
        <th rowspan='2'>Facility type</th>
        <th colspan='2'>Grand Gedeh</th>
        <th colspan='2'>Rivercess</th>
        <th colspan='2'>Cumulative Total</th>
    </tr>
    <tr>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
        <th>Dec '14</th>
        <th>Jan '15</th>
    </tr>
    <tr>
        <td>Government PHC-1</td>
        <td>16</td>
        <td></td>
        <td>16</td>
        <td></td>
        <td>32</td>
        <td>32</td>
    </tr>
    <tr>
        <td>Government PHC-2</td>
        <td>2</td>
        <td></td>
        <td></td>
        <td></td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td>Government PHC-3</td>
        <td>1</td>
        <td></td>
        <td>1</td>
        <td></td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td>Private clinics</td>
        <td></td>
        <td></td>
        <td>2</td>
        <td></td>
        <td>2</td>
        <td>2</td>
    </tr>
    <tr>
        <td colspan='5'><b>TOTAL: </b></td>
        <td><b>38</b></td>
        <td><b>38</b></td>
    </tr>
</table>
<hr>

<h3><b>4</b>. Ebola IPC supplies</h3>
<p>22 tons of IPC supplies delivered to health centers in Grand Gedeh and Rivercess Counties.</p>
<div class="whitespace"></div>
