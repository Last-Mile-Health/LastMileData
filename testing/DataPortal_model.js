
var reportObjectCollection = [
    
    {                           // CONCISE:
        type: "",               // report object types: "one # over time", "multiple # over time", "one CAT over time", "multiple CAT over time", "data table"
        chartSpecs: {},         // chart specifications; passed to LMD_dimpleHelper
        data: [{},{}],          // indicator data; pulled in from lastmile_dataportal.tbl_values
        indicatorMetadata: {},  // indicator metadata; pulled in from lastmile_dataportal.tbl_indicators
        displayOrder: 1,        // order in which to display chart objects within report
        reports: []             // which reports is the report object used in
    },
    
    {                           // DETAILED:
        type: "oneNumericOverTime",
        chartSpecs: {},
        data: [{},{}],
        indicatorMetadata: {
            "indID":"11",
            "indName":"Percent of FY15 budget raised",
            "indName_short":"% raised",
            "indCut":"Konobo",
            "indCategory":"P&D",
            "indFormat":"percent",
            "indDefinition":"Total percent of...",
            "indTarget":"1",
            "indNarrative":"ON TRACK. LMH has achieved this goal."
        },
        displayOrder: 1,
        reports: []
    }

];