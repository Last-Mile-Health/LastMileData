<script>
// Asynchronously load data, then generate charts
$.getScript("/LastMileData/src/js/data_execDashboard.js", function(){
    
    // Set model (reports)
    var model_reportObjects = [
        {
            indID: 1,
            indName: "Number of FHWs deployed",
            indDefinition: "A deployed Frontline Health Worker (FHW) is receiving a monetary incentive, has received LMH training in at least one healthcare module, and is actively visiting patients within his or her community",
            indTarget: "378",
            indType: "integer",
            indNarrative: "OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.",
            indData: [
                {date:"Jan '15", value:55},
                {date:"Feb '15", value:55},
                {date:"Mar '15", value:58},
                {date:"Apr '15", value:71} ]
        },
        {
            indID: 2,
            indName: "Number of FHW supervisors",
            indDefinition: "Number of active FHW leaders and Clinical Mentors (on payroll)",
            indTarget: "42",
            indType: "integer",
            indNarrative: "OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.",
            indData: [
                {date:"Jan '15", value:5},
                {date:"Feb '15", value:5},
                {date:"Mar '15", value:5},
                {date:"Apr '15", value:7} ]
        },
        {
            indID: 3,
            indName: "Number of people served",
            indDefinition: "Number of people living in a village that is served by an active FHW, based on LMH registration data",
            indTarget: "92,919",
            indType: "integer",
            indNarrative: "OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.",
            indData: [
                {date:"Jan '15", value:14298},
                {date:"Feb '15", value:14397},
                {date:"Mar '15", value:14517},
                {date:"Apr '15", value:14673} ]
        },
        {
            indID: 4,
            indName: "Number of villages served",
            indDefinition: "Number of villages (geographically distinct rural communities) that are served by an active FHW",
            indTarget: 400,
            indType: "integer",
            indNarrative: "OFF TRACK. Project planning process underway to produce revised projections for FY15, CY15, and FY16 by April 1.",
            indData: [
                {date:"Jan '15", value:52},
                {date:"Feb '15", value:52},
                {date:"Mar '15", value:55},
                {date:"Apr '15", value:64} ]
        },
        {
            indID: 5,
            indName: "Number of health workers trained in Ebola-specific services",
            indDefinition: "Cumulative number of health workers/volunteers (FHWs, gCHVs, supervisors, facility staff, CHT staff, community members) who received training in one or more of the following: contact tracing, surveillance, IPC, education, case management",
            indTarget: 518,
            indType: "integer",
            indNarrative: "ON TRACK. Target exceeded, due to larger-than-expected number of facility staff, gCHVs, and CHC members who were trained.",
            indData: [
                {date:"Jan '15", value:734},
                {date:"Feb '15", value:1307},
                {date:"Mar '15", value:1367},
                {date:"Apr '15", value:1382} ]
        },
        {
            indID: 6,
            indName: "Number of health centers trained to respond to Ebola",
            indDefinition: "Number of government health facilities (PHC-1, PHC-2, or PHC-3) that received an infection prevention and control (IPC) training organized by LMH",
            indTarget: "35 (100%)",
            indType: "integer",
            indNarrative: "ON TRACK. Target of 100% of health facilities was reached in December.",
            indData: [
                {date:"Jan '15", value:36},
                {date:"Feb '15", value:36},
                {date:"Mar '15", value:36},
                {date:"Apr '15", value:36} ]
        },
        {
            indID: 7,
            indName: "Total number of staff in Liberia",
            indDefinition: "Total number of full-time staff in Liberia (excludes Frontline Health Workers)",
            indTarget: "n/a",
            indType: "integer",
            indNarrative: "",
            indData: [
                {date:"Jan '15", value:143},
                {date:"Feb '15", value:149},
                {date:"Mar '15", value:160},
                {date:"Apr '15", value:170} ]
        },
        {
            indID: 8,
            indName: "Total number of staff in U.S.",
            indDefinition: "Total number of full and part-time staff in USA (excludes consultants)",
            indTarget: "n/a",
            indType: "integer",
            indNarrative: "",
            indData: [
                {date:"Jan '15", value:13},
                {date:"Feb '15", value:13},
                {date:"Mar '15", value:13},
                {date:"Apr '15", value:13} ]
        },
        {
            indID: 9,
            indName: "Total funds received",
            indDefinition: "Total money received",
            indTarget: "$8,500,000",
            indType: "dollars",
            indNarrative: "ON TRACK. LMH is on track to acheive goal. In addtion to the $8.4m in financing that has been received to date, approximately $600,000 in committed financing can be counted as FY15 receivables, with an additional $1.7m in receivables for FY17 & FY18.",
            indData: [
                {date:"Jan '15", value:6529994},
                {date:"Feb '15", value:7424971},
                {date:"Mar '15", value:8404584},
                {date:"Apr '15", value:8500808} ]
        },
        {
            indID: 10,
            indName: "Total funds received and committed",
            indDefinition: "Total money received and committed",
            indTarget: "$8,500,000",
            indType: "dollars",
            indNarrative: "ON TRACK. LMH has exceeded goal. In addition to the $9.1 million funds received and committed, approximately $200,000 in highly likely philanthropy is currently pipelined.",
            indData: [
                {date:"Jan '15", value:8359411},
                {date:"Feb '15", value:8561521},
                {date:"Mar '15", value:9133923},
                {date:"Apr '15", value:9202778} ]
        },
        {
            indID: 11,
            indName: "Percent of FY15 budget raised to date",
            indDefinition: "Total percent of FY15 budget raised based on funds received to date",
            indTarget: "$6,978,616 (100%)",
            indType: "percent",
            indNarrative: "ON TRACK. LMH has achieved this goal.",
            indData: [
                {date:"Jan '15", value:.94},
                {date:"Feb '15", value:1.06},
                {date:"Mar '15", value:1.20},
                {date:"Apr '15", value:1.22} ]
        },
        {
            indID: 12,
            indName: "Total FY15 expenses",
            indDefinition: "Total money spent and committed",
            indTarget: "$6,978,616",
            indType: "dollars",
            indNarrative: "OFF TRACK. Likely to be under budget by end of FY due to deferred expenses in Q3.",
            indData: [
                {date:"Jan '15", value:2767124},
                {date:"Feb '15", value:3268932},
                {date:"Mar '15", value:4290787},
                {date:"Apr '15", value:5197595} ]
        },
        {
            indID: 13,
            indName: "Percent of FY15 budget spent",
            indDefinition: "Total percent of FY15 Budget spent to date",
            indTarget: "n/a",
            indType: "percent",
            indNarrative: "OFF TRACK. Likely to be under budget by end of FY due to defferred expenses in Q3.",
            indData: [
                {date:"Jan '15", value:.40},
                {date:"Feb '15", value:.49},
                {date:"Mar '15", value:.61},
                {date:"Apr '15", value:.74} ]
        },
        {
            indID: 14,
            indName: "Cash on hand",
            indDefinition: "The amount of money in the form of cash that LMH has on hand after it has covered its costs",
            indTarget: "n/a",
            indType: "dollars",
            indNarrative: "Represents 6 months' worth operating cash.",
            indData: [
                {date:"Jan '15", value:4623701},
                {date:"Feb '15", value:4791627},
                {date:"Mar '15", value:4245777},
                {date:"Apr '15", value:3954646} ]
        },
        {
            indID: 15,
            indName: "Cash burn rate",
            indDefinition: "Average monthly cash spent [Updated quarterly]",
            indTarget: "n/a",
            indType: "dollars",
            indNarrative: "Spike due to quarterly procurements.",
            indData: [
                {date:"Dec '14", value:329000},
                {date:"Jan '15", value:420000},
                {date:"Mar '15", value:667000} ]
        }
    ]
    
    // Add divID property to model_reportObjects
    for (var key in model_reportObjects){
        model_reportObjects[key].divID = "lineGraph_" + model_reportObjects[key].indID;
    }
    
    // Bind sidebar model to accordion DIV
    rivets.bind($('#dashboardContent'), {model_reportObjects: model_reportObjects});
    
    // Format data (one-way)
    rivets.formatters.format = function(x, type) {
        switch(type) {
            case 'integer':
                return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                break;
            case 'percent':
                return (x*100).toFixed(1) + "%";
                break;
            case 'dollars':
                return "$" + x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                break;
            default:
                return x;
        }
    };

    // !!!!! User sets this manually (for now) !!!!!
    var indicatorIDs = [1,2,3,4,6];
    var indIDString = "";
    for(var i=0; i<indicatorIDs.length; i++) {
        indIDString += indicatorIDs[i] + ",";
    }
    indIDString = indIDString.slice(0,-1);

    // Send second AJAX request (indicator values)
    var rawData;
    var indicators = {};
    var indicatorData = {
        add: function(indID, month, year, value) {
            var obj = {};
            obj.Date = year + "-" + twoDigits(month) + "-01";
            obj.Month = month;
            obj.Year = year;
            obj.Value = value;
            this[indID] = this[indID] || [];
            this[indID].push(obj);
        }
    };

    // Query data (based on indicatorIDs)
    $.ajax({
        type: "POST",
        url: "/LastMileData/src/php/getJSON.php",
        data: {'queryString': 'SELECT `month`, `year`, `indID`, `indValue` FROM lastmile_db.tbl_kpi_values WHERE indID IN (' + indIDString + ');'},
        dataType: "json",
        success: function(data){

            // Generate indicatorData object
            rawData = data;
            for (var key in rawData) {
                indicatorData.add(rawData[key].indID, rawData[key].month, rawData[key].year, rawData[key].indValue);
            }

            for(var i=0; i<indicatorIDs.length; i++) {
                indIDString += indicatorIDs[i] + ",";
            }
            
//for (var key in indicatorData[1]) {
//    var mth = indicatorData[1][key].Month;
//    var yr = indicatorData[1][key].Year;
//}


// !!!!! START: Testing block; generate dashboard elements !!!!!
    var indicatorParameters = {
        indID: 1,
        indName: "Number of FHWs deployed",
        indDefinition: "A deployed Frontline Health Worker...",
        indTarget: 378,
        indNarrative: "OFF TRACK. Project planning..."
    }
    var lineGraphParameters = {
        data: indicatorData[1], // !!!!! 1 is the indicator ID !!!!!
        colors: ["#F79646"],
        timeInterval: 3,
        size: {x:505, y:400},
        xyVars: {x:"Date", y:"Value"}
        // !!!!! add y-axis label !!!!!
        }





            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_1",
//                data: myData.numFHWs,
                    data: indicatorData[1], // !!!!! 1 is the indicator ID !!!!!
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
//                    xyVars: {x:"Month", y:"FHWs"}
                xyVars: {x:"Date", y:"Value"}
                // !!!!! add y-axis label !!!!!
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_2",
                data: myData.numSupervisors,
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Supervisors"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_3",
                data: myData.numPeopleServed,
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"People"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_4",
                data: myData.numVillagesServed,
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Villages"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_5",
                data: myData.numHealthWorkersTrainedEbola,
                colors: ["#F79646"],
                timeInterval: 3,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Health workers"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_6",
                data: myData.numFacilitiesIPC,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Facilities"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_7",
                data: myData.numStaffLiberia,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Staff"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_8",
                data: myData.numStaffUS,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Staff"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_9",
                data: myData.fundsReceived,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"USD"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_10",
                data: myData.fundsReceivedAndCommitted,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"USD"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_11",
                data: myData.percentOfBudgetRaised,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Percent"},
                tickFormat: {y:"%"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_12",
                data: myData.fy15Expenses,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"USD"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_13",
                data: myData.fy15Spent,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"Percent"},
                tickFormat: {y:"%"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_14",
                data: myData.cashOnHand,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"USD"}
            });

            LMD_dimpleHelper.lineGraph_monthly({
                targetDiv: "lineGraph_15",
                data: myData.cashBurnRate,
                colors: ["#F79646"],
                timeInterval: 1,
                size: {x:505, y:400},
                xyVars: {x:"Month", y:"USD"}
            });

        },
        error: function(request) { console.log('ajax error :/'); console.log(request); }
    });

});

// Pad numbers to two digits ( helper function for mysql_datetime() )
// !!!!! Refactor into "utility library"; This is duplicated (fhwForms.js, deqa.js) !!!!!
function twoDigits(d) {
    if(0 <= d && d < 10) return "0" + d.toString();
    if(-10 < d && d < 0) return "-0" + (-1*d).toString();
    return d.toString();
}
</script>

<h1>Executive Dashboard <span style="font-size:60%">(updated: 4/12/2015)</span></h1>

<div id='dashboardContent'>
    <div class='row' rv-each-report_object="model_reportObjects">
        <hr style="margin:15px; border:1px solid #eee;">
        <div class='col-md-4'>
            <h3><b>{{report_object.indID}}</b>. {{report_object.indName}}</h3>
            <p><b>Definition</b>: {{report_object.indDefinition}}</p>
            <p><b>FY15 Target</b>: {{report_object.indTarget}}</p>
            <table class='ptg_data'>
                <tr>
                    <th rv-each-data="report_object.indData">{{data.date}}</th>
                </tr>
                <tr>
                    <td rv-each-data="report_object.indData">{{data.value | format report_object.indType}}</td>
                </tr>
            </table>
            <hr class='smallHR'>
            <p><b>Progress-to-goal</b>: {{report_object.indNarrative}}</p>
        </div>
        <div class='col-md-7'>
            <div rv-id="report_object.divID"></div>
        </div>
    </div>
</div>