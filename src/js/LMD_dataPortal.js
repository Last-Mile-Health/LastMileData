// Module:          LMD_dataPortal.js
// Author:          Avi Kenny
// Last update:     2014-10-11
// Dependencies:    LMD_utilities.js, LMD_dimpleHelper, Knockout.js
// Purpose:         Used by frag_indicatorReport.php to dynamically generate indicator reports
// Notes:           An "indicator" is an individual metric that can be implemented repeatedly (e.g. ANC4+)
//                  An "indicator instance" (II) is an "implementations" of an indicator in a specific geographic region (e.g. ANC4+ in Konobo)
//                  An "instance value" is the numeric value of a particular II (e.g. 54%, for the ANC4+ rate in Konobo in March of 2015)

var LMD_dataPortal = (function(){


    // PRIVATE VARS
    var chartData = {};             // Used for Dimple charts
    var tableData = {};             // Used for data tables
    var instanceMetadata = {};     // Used for both
    
    
    // PRIVATE: Sets the dates for the Data Portal to display
    //          If it is the 15th of the month or later, display the previous 4 months; otherwise, display the four months before the previous month
    //          Example:
    //              Before June 15th, the months would be Jan -- Apr
    //              After June 15th, the months would be Feb -- May
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


    // PRIVATE: Stores indicator instance metadata (in "instanceMetadata" object)
    //          Input object comes from LMD_REST.php/indicatorInstances
    //          Keys are instance IDs, values are objects containing metadata for a given indicator instance
    function setMetadata(dataObject) {
        for (var key in dataObject) {
            var instID = dataObject[key].instID;
            var metadata = dataObject[key];
            instanceMetadata[instID] = metadata;
        }
    }


    // PRIVATE: Configures the "report model" (i.e. a single report within the data portal)
    //          Input object comes from LMD_REST.php/reportobjects
    //          Must be called AFTER setData and setMetadata
    function configureReportModel(dataObject) {
        
        // 1. Transform indicator strings into arrays
        for (var key in dataObject) {
            dataObject[key].instIDs = dataObject[key].instIDs.split(",");
            dataObject[key].chart_instIDs = dataObject[key].chart_instIDs.split(",");
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

        // 3. Merge in data
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
            var instID = d.instIDs[0];
            var metadata = instanceMetadata[instID];
            
            if ( d.roMetadata_name == null || d.roMetadata_name == '' ) {
                d.roMetadata_name = metadata.indName;
            }
            if ( d.roMetadata_targetFormat == null || d.roMetadata_targetFormat == '' ) {
                d.roMetadata_targetFormat = metadata.indFormat;
            }
            if ( d.roMetadata_description == null || d.roMetadata_description == '' ) {
                d.roMetadata_description = metadata.indDefinition;
            }
            if ( d.roMetadata_target == null || d.roMetadata_target == '' ) {
                d.roMetadata_target = metadata.indTarget;
            }

            // Populate chart_points array (for Dimple charts)
            for (var key2 in d.chart_instIDs) {

                var instID = d.chart_instIDs[key2];
                var dataArray = chartData[instID];
                
                if (dataArray) {
                    for(var i=0; i<dataArray.length; i++) {
                        d.chart_points.push({
                            Month:dataArray[i].date,
                            Value:dataArray[i].value,
                            Cut: d.chartMultiple ? instanceMetadata[instID].instShortName : 1
                        });
                    }
                }
            }
        }
        
        // 4. Return transformed report object
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


    // PRIVATE: Render D3/Dimple charts
    //          Parameter is a "report object", as returned by configureReportModel()
    function renderCharts(dataObject) {
        for(var key in dataObject) {
            if (key >= 0) {

                var d = dataObject[key];

                if (d.chart_points.length > 0) {
                    LMD_dimpleHelper.createChart({
                        type:d.chart_type,
                        targetDiv: d.chart_div,
                        data: d.chart_points,
                        colors: d.chart_colors || "default",
                        timeInterval: d.chart_timeInterval || 1, // !!!!! calculate this automatically
                        size: { x:d.chart_size_x, y:d.chart_size_y },
                        xyVars: { x:"Month", y:"Value" },
        //                axisTitles: d.chartSpecs.axisTitles, // !!!!! use indNameShort !!!!!
                        cut: "Cut",
                        legend: d.chart_legend || "",
                        tickFormat: { y:d.chart_tickFormat }
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

        // Configure report model
        reportObjects = configureReportModel(reportObjects);

        // Initialize knockout.js; bind model to DIV
        ko.applyBindings({
            reportObjects: reportObjects,
            lastFourMonths: setDates()
        }, $('#reportContent')[0]);

        // Populate data tables
        populateTableData();
        populateTableMetadata();

        // Create charts
        renderCharts(reportObjects);
    }


    // LMD_dataPortal API
    return {
        bootstrap: bootstrap
    };
    

})();
