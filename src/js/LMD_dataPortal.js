// Module:          LMD_dataPortal.js
// Author:          Avi Kenny
// Last update:     2014-10-11
// Dependencies:    LMD_utilities.js, LMD_dimpleHelper, Knockout.js, Dimple.js, D3.js
// Purpose:         Used by frag_indicatorReport.php to dynamically generate indicator reports
// Notes:           An "indicator" is an individual metric that can be implemented repeatedly (e.g. ANC4+)
//                  An "indicator instance" (II) is an "implementations" of an indicator in a specific geographic region (e.g. ANC4+ in Konobo)
//                  An "instance value" is the numeric value of a particular II (e.g. 54%, for the ANC4+ rate in Konobo in March of 2015)

var LMD_dataPortal = (function(){


    // PRIVATE VARS
    var chartData = {};             // Used for Dimple charts
    var tableData = {};             // Used for data tables
    var csvData = [];               // Used for CSV-formatted data (for downloading)
    var instanceMetadata = {};      // Used for both
    
    
    // PRIVATE: Sets the dates for the Data Portal to display
    //          If it is the 15th of the month or later, display the previous 4 months; otherwise, display the four months before the previous month
    //          Example:
    //              Before June 15th, the months would be Jan -- Apr
    //              After June 15th, the months would be Feb -- May
    //          This a business rule to account for the fact that the Data Portal is "updated" on the 15th of each month with the previous month's data
    function setDates() {
        
        // Generate dates (last 4 months)
        var todayDay = moment().format('D'),
            todayMinus1m = moment().subtract(1 + ( todayDay < 15 ? 1 : 0 ),'months'),
            todayMinus2m = moment().subtract(2 + ( todayDay < 15 ? 1 : 0 ),'months'),
            todayMinus3m = moment().subtract(3 + ( todayDay < 15 ? 1 : 0 ),'months'),
            todayMinus4m = moment().subtract(4 + ( todayDay < 15 ? 1 : 0 ),'months');

        // Create object to hold formatted dates
        var lastFourMonths = [
            { yearMonth: todayMinus4m.format("YYYY-M"), shortMonth: todayMinus4m.format("MMM 'YY") },
            { yearMonth: todayMinus3m.format("YYYY-M"), shortMonth: todayMinus3m.format("MMM 'YY") },
            { yearMonth: todayMinus2m.format("YYYY-M"), shortMonth: todayMinus2m.format("MMM 'YY") },
            { yearMonth: todayMinus1m.format("YYYY-M"), shortMonth: todayMinus1m.format("MMM 'YY") }
        ];
        
        return lastFourMonths;
        
    }


    // PRIVATE: Stores "instance value" data (in "chartData" and "tableData" objects), to be used in charts and tables
    //          The single parameter comes from LMD_REST.php/instanceValues
    //          For chartData, keys are instance IDs and values are objects containing two properties, a MySQL-formatted date and the indicator value
    //          For tableData, keys are "instID-monthyear" hashes (e.g. "i_33_m_2015-4", for instID "33" on April, 2015) and values are indicator values
    function setData(dataObject) {
        for (var key in dataObject) {
            
            var d = dataObject[key];
            
            // Set chart data
            chartData[d.instID] = chartData[d.instID] || [];
            chartData[d.instID].push({
                date: d.year + "-" + LMD_utilities.twoDigits(d.month) + "-01",
                value: Number(d.instValue)
            });
            
            // Set table data
            tableData["i_" + d.instID + "_m_" + d.year + "-" + d.month] = d.instValue;
            
        }
    }


    // PUBLIC: Stores indicator instance metadata (in "instanceMetadata" object)
    //          Input object comes from LMD_REST.php/indicatorInstances
    //          Keys are instance IDs, values are objects containing metadata for a given indicator instance
    function setMetadata(dataObject) {
        for (var key in dataObject) {
            var instID = dataObject[key].instID;
            var metadata = dataObject[key];
            instanceMetadata[instID] = metadata;
        }
    }
    
    
    // PUBLIC: Returns "instanceMetadata" object
    function getMetadata() {
        return instanceMetadata;
    }
    
    
    // PRIVATE: Configures the "report model" (i.e. a single report within the data portal)
    //          Input object comes from LMD_REST.php/reportobjects
    //          Must be called AFTER setData and setMetadata
    function configureReportModel(dataObject) {

        // 1. Transform indicator strings into arrays
        for (var key in dataObject) {
            dataObject[key].instIDs = dataObject[key].instIDs.split(",");
            dataObject[key].chart_instIDs = dataObject[key].chart_instIDs.split(",");
            dataObject[key].instIDs_shortNames = dataObject[key].instIDs_shortNames ? dataObject[key].instIDs_shortNames.split(",") : null;
            dataObject[key].chart_instIDs_shortNames = dataObject[key].chart_instIDs_shortNames ? dataObject[key].chart_instIDs_shortNames.split(",") : null;
        }

        // 2. Sort by "displayOrder"
        dataObject.sort(function(a,b){
            if (Number(a.displayOrder) < Number(b.displayOrder)) {
                return -1;
            }
            else if (Number(a.displayOrder) > Number(b.displayOrder)) {
                return 1;
            } else {
                return 0;
            }
        });

        // 3. Clear csvData array
        csvData = [];

        // 4. Merge in data
        for (var key in dataObject) {

            var d = dataObject[key];

            // Add "multiple" property, which denotes whether this report object contains a single indicator or multiple indicators (for data tables; used by knockout)
            d.multiple = d.instIDs.length > 1 ? true : false;

            // Add "multiple" property, which denotes whether this report object contains a single indicator or multiple indicators (for charts)
            d.chartMultiple = d.chart_instIDs.length > 1 ? true : false;

            // Add "chart_div" property
            d.chart_div = "chart_" + d.id;

            // Add chart_points array (for Dimple charts)
            d.chart_points = [];

            // If roMetadata fields are not specified, get them from instanceMetadata (only practical for report objects with a single indicator instance)
            // !!!!! eventually phase this out !!!!!
            var instID = d.instIDs[0];
            var metadata = instanceMetadata[instID];
            if ( d.ro_name == null || d.ro_name == '' ) {
                d.ro_name = metadata.indName;
            }
            if ( d.ro_description == null || d.ro_description == '' ) { // !!!!! eventually phase this out !!!!!
                d.ro_description = metadata.indDefinition;
            }
            
            // If d.instIDs_shortNames exists (from report object), overwrite instanceMetadata
            if ( d.instIDs_shortNames != null) {
                for (var key2 in d.instIDs_shortNames) {
                    var instID2 = d.instIDs[key2];
                    instanceMetadata[instID2].instShortName = d.instIDs_shortNames[key2];
                }
            }
            
            // Create "dates" array, for CSV data
            var dates = [];
            
            // Populate chart_points array (for Dimple charts)
            for (var key2 in d.chart_instIDs) {

                var instID = d.chart_instIDs[key2];
                var dataArray = chartData[instID];
                
                if (dataArray) {
                    for(var i=0; i<dataArray.length; i++) {
                        
                        // Get instShortName from report object if it exists; otherwise, get it from instanceMetadata
                        var instShortName = d.chart_instIDs_shortNames ? d.chart_instIDs_shortNames[key2] : instanceMetadata[instID].instShortName;
                        
                        // Add chart point
                        // Chart point only added if its date is not "too new" (a business rule to account for the fact that the Data Portal is "updated" on the 15th of each month with the previous month's data)
                        var data_totalMonth = (12*Number(dataArray[i].date.split('-')[0]))+Number(dataArray[i].date.split('-')[1]);
                        var latestAllowed_date = todayMinus1m = moment().subtract(1 + ( moment().format('D') < 15 ? 1 : 0 ),'months');
                        var latestAllowed_totalMonth = (12*latestAllowed_date.year())+(latestAllowed_date.month()+1);
                        if (data_totalMonth <= latestAllowed_totalMonth) {
                            d.chart_points.push({
                                Month:dataArray[i].date,
                                Value:dataArray[i].value,
                                Cut: d.chartMultiple ? instShortName : '(none)'
                            });
                        }
                        
                        // Add date to date array (for CSV data)
                        dates.push(dataArray[i].date);
                    }
                }
                
            }
            
            // Remove duplicates from "dates" array; sort
            d.uniqueDates = [];
            $.each(dates, function(i, el){
                if($.inArray(el, d.uniqueDates) === -1) d.uniqueDates.push(el);
            });
            d.uniqueDates.sort();
            
            // Populate "CSV" object (for "download data" function)
            var csvFile = '"' + d.ro_name + '"' + '\n' + 'month,';
            for (var key2 in d.chart_instIDs) {
                // Get instShortNames from report object if they exist; otherwise, get it from instanceMetadata
                csvFile += d.chart_instIDs_shortNames ? d.chart_instIDs_shortNames[key2] + ',' : instanceMetadata[d.chart_instIDs[key2]].instShortName + ',';
            }
            csvFile = csvFile.slice(0, -1);
            csvFile += '\n';
            for (var key2 in d.uniqueDates) {
                csvFile += d.uniqueDates[key2] + ',';
                for (var key3 in d.chart_instIDs) {
                    var yearMonth = d.uniqueDates[key2].slice(0,-3);
                    yearMonth = yearMonth.charAt(5)==='1' ? yearMonth : yearMonth.slice(0, 5) + yearMonth.slice(6);
                    csvFile += tableData['i_' + d.chart_instIDs[key3] + '_m_' + yearMonth] + ',';
                }
                csvFile = csvFile.slice(0, -1);
                csvFile += '\n';
            }
            csvFile = csvFile.slice(0, -1);
            csvFile = csvFile.replace(/undefined/g, "");
            csvData.push(csvFile);
        
        }
        
        // 5. Return transformed report object
        return dataObject;
        
    };


    // PRIVATE: Dynamically populate instance values into html tables
    //          Table cells must have the class "instValue" and the following data attributes:
    //               data-instid:        indicator ID
    //               data-yearmonth:    A string of the form "yyyy-m" (e.g. "2015-4", for April, 2015)
    //               data-format:       A number format, as specified in LMD_utilities.format_number()
    function populateTableData() {
        $(".instValue").each(function(){
            var instID = $(this).attr("data-instid");
            var yearmonth = $(this).attr("data-yearmonth");
            var format = instanceMetadata[instID].indFormat || 'integer';
            var instValue = tableData["i_" + instID + "_m_" + yearmonth];
            instValue = LMD_utilities.format_number(instValue, format);
            $(this).html(instValue);
        });
    }


    // PRIVATE: Dynamically populate indicator short names into html tables
    //          Table cells must have the class "instShortName" and the "data-indid" data attribute:
    //          !!!!! Potentially modify this in the future to populate other metadata !!!!!
    function populateTableMetadata() {
        $(".instShortName").each(function(){
            var instID = $(this).attr("data-instid");
            var shortName = instanceMetadata[instID].instShortName;
            $(this).html(shortName);
        });
    }


    // PRIVATE: Activate "download data" links, each of which downloads a CSV of all of the data in the Dimple chart
    function setDownloadLinks_data() {
        $('.downloadData').each(function() {
            var roNumber = $(this).attr('id').slice(9);
            var data = 'text;charset=utf-8,' + encodeURIComponent(csvData[roNumber]);
            $(this).attr('href',"data:" + data);
        });
    }


    // PRIVATE: Activate "download chart" links, each of which downloads the Dimple chart as a PNG image
    function setDownloadLinks_charts() {
        
        $('.downloadChart').click(function(){
            
            // Code adapted from: http://techslides.com/save-svg-as-an-image
            
            var chart_id = $(this).closest('.row').find('svg').parent().attr('id');
            var html = d3.select("#" + chart_id + " svg")
                    .attr("version", 1.1)
                    .attr("xmlns", "http://www.w3.org/2000/svg")
                    .attr('style','background:white')
                    .node().parentNode.innerHTML;

            var imgsrc = 'data:image/svg+xml;base64,'+ btoa(html);
            var img = '<img src="'+imgsrc+'">'; 
            d3.select("#svgdataurl").html(img);

            var canvas = document.querySelector("canvas")
            var context = canvas.getContext("2d");

            var image = new Image;
            image.src = imgsrc;
            image.onload = function() {
                context.drawImage(image, 0, 0);

                var canvasdata = canvas.toDataURL("image/png");

                var pngimg = '<img src="'+canvasdata+'">'; 
                d3.select("#pngdataurl").html(pngimg);

                var a = document.createElement("a");
                a.download = "chart.png";
                a.href = canvasdata;
                a.click();
            };
        });
    }


    // PRIVATE: Render D3/Dimple charts
    //          Parameter is a "report object", as returned by configureReportModel()
    function renderCharts(reportObjects) {
        for(var key in reportObjects) {
            if (key >= 0) {

                var d = reportObjects[key];

                if (d.chart_points.length > 0) {
                    LMD_dimpleHelper.createChart({
                        type:d.chart_type,
                        targetDiv: d.chart_div,
                        data: d.chart_points,
                        colors: d.chart_colors || "default",
                        timeInterval: Math.ceil(d.uniqueDates.length/24),
                        size: { x:Number(d.chart_size_x), y:Number(d.chart_size_y) },
                        xyVars: { x:"Month", y:"Value" },
        //                axisTitles: d.chartSpecs.axisTitles, // !!!!! new attribute for this (specified in edit "reports interface") ?????
                        cut: "Cut",
                        legend: d.chartMultiple ? "right" : "",
                        tickFormat: { y:d.chart_tickFormat },
                        axisValues: { min:d.chart_yAxis_min, max:d.chart_yAxis_max }
                    });
                }
            }
        }
    }


    // PRIVATE: Clear all data and metadata
    function clearData() {
        chartData = {};
        tableData = {};
        instanceMetadata = {};
    }


    // PUBLIC:  Bootstrap the page
    //          This function is called from frag_indicatorReport.php
    function bootstrap(instanceValues, indicatorInstances, reportObjects) {
        
        // Clear data
        clearData();
        
        // Set data and metadata
        setData(instanceValues);
        setMetadata(indicatorInstances);

        if (reportObjects) {
            // Configure report model
            reportObjects = configureReportModel(reportObjects);

            // Initialize knockout.js; bind model to DIV
            ko.applyBindings({
                reportObjects: reportObjects,
                lastFourMonths: setDates()
            }, $('#reportContent')[0]);
        }

        // Populate data tables
        populateTableData();
        populateTableMetadata();

        // Create charts
        if (reportObjects) {
            renderCharts(reportObjects);
        }
        
        // Activate "download data" links
        setDownloadLinks_data();
        setDownloadLinks_charts();
    }


    // LMD_dataPortal API
    return {
        bootstrap: bootstrap,
        setMetadata: setMetadata,
        getMetadata: getMetadata
    };
    

})();
