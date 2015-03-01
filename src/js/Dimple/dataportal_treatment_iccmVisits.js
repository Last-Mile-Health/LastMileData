// Create new dimple SVG
var svg = dimple.newSvg("#dataportal_treatment_iccmVisits", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"2013-01-01", "Condition":"Malaria", "Treated":0 },
    { "Month":"2013-01-01", "Condition":"Diarrhea", "Treated":0 },
    { "Month":"2013-01-01", "Condition":"ARI", "Treated":0 },
    { "Month":"2013-02-01", "Condition":"Malaria", "Treated":23 },
    { "Month":"2013-02-01", "Condition":"Diarrhea", "Treated":6 },
    { "Month":"2013-02-01", "Condition":"ARI", "Treated":13 },
    { "Month":"2013-03-01", "Condition":"Malaria", "Treated":60 },
    { "Month":"2013-03-01", "Condition":"Diarrhea", "Treated":27 },
    { "Month":"2013-03-01", "Condition":"ARI", "Treated":32 },
    { "Month":"2013-04-01", "Condition":"Malaria", "Treated":55 },
    { "Month":"2013-04-01", "Condition":"Diarrhea", "Treated":21 },
    { "Month":"2013-04-01", "Condition":"ARI", "Treated":21 },
    { "Month":"2013-05-01", "Condition":"Malaria", "Treated":70 },
    { "Month":"2013-05-01", "Condition":"Diarrhea", "Treated":40 },
    { "Month":"2013-05-01", "Condition":"ARI", "Treated":51 },
    { "Month":"2013-06-01", "Condition":"Malaria", "Treated":66 },
    { "Month":"2013-06-01", "Condition":"Diarrhea", "Treated":24 },
    { "Month":"2013-06-01", "Condition":"ARI", "Treated":32 },
    { "Month":"2013-07-01", "Condition":"Malaria", "Treated":79 },
    { "Month":"2013-07-01", "Condition":"Diarrhea", "Treated":30 },
    { "Month":"2013-07-01", "Condition":"ARI", "Treated":67 },
    { "Month":"2013-08-01", "Condition":"Malaria", "Treated":254 },
    { "Month":"2013-08-01", "Condition":"Diarrhea", "Treated":124 },
    { "Month":"2013-08-01", "Condition":"ARI", "Treated":136 },
    { "Month":"2013-09-01", "Condition":"Malaria", "Treated":252 },
    { "Month":"2013-09-01", "Condition":"Diarrhea", "Treated":96 },
    { "Month":"2013-09-01", "Condition":"ARI", "Treated":119 },
    { "Month":"2013-10-01", "Condition":"Malaria", "Treated":332 },
    { "Month":"2013-10-01", "Condition":"Diarrhea", "Treated":113 },
    { "Month":"2013-10-01", "Condition":"ARI", "Treated":283 },
    { "Month":"2013-11-01", "Condition":"Malaria", "Treated":242 },
    { "Month":"2013-11-01", "Condition":"Diarrhea", "Treated":75 },
    { "Month":"2013-11-01", "Condition":"ARI", "Treated":204 },
    { "Month":"2013-12-01", "Condition":"Malaria", "Treated":255 },
    { "Month":"2013-12-01", "Condition":"Diarrhea", "Treated":80 },
    { "Month":"2013-12-01", "Condition":"ARI", "Treated":218 },
    { "Month":"2014-01-01", "Condition":"Malaria", "Treated":274 },
    { "Month":"2014-01-01", "Condition":"Diarrhea", "Treated":110 },
    { "Month":"2014-01-01", "Condition":"ARI", "Treated":198 },
    { "Month":"2014-02-01", "Condition":"Malaria", "Treated":228 },
    { "Month":"2014-02-01", "Condition":"Diarrhea", "Treated":74 },
    { "Month":"2014-02-01", "Condition":"ARI", "Treated":147 },
    { "Month":"2014-03-01", "Condition":"Malaria", "Treated":352 },
    { "Month":"2014-03-01", "Condition":"Diarrhea", "Treated":136 },
    { "Month":"2014-03-01", "Condition":"ARI", "Treated":220 },
    { "Month":"2014-04-01", "Condition":"Malaria", "Treated":333 },
    { "Month":"2014-04-01", "Condition":"Diarrhea", "Treated":138 },
    { "Month":"2014-04-01", "Condition":"ARI", "Treated":189 },
    { "Month":"2014-05-01", "Condition":"Malaria", "Treated":334 },
    { "Month":"2014-05-01", "Condition":"Diarrhea", "Treated":134 },
    { "Month":"2014-05-01", "Condition":"ARI", "Treated":206 },
    { "Month":"2014-06-01", "Condition":"Malaria", "Treated":489 },
    { "Month":"2014-06-01", "Condition":"Diarrhea", "Treated":184 },
    { "Month":"2014-06-01", "Condition":"ARI", "Treated":316 },
    { "Month":"2014-07-01", "Condition":"Malaria", "Treated":434 },
    { "Month":"2014-07-01", "Condition":"Diarrhea", "Treated":134 },
    { "Month":"2014-07-01", "Condition":"ARI", "Treated":230 },
    { "Month":"2014-08-01", "Condition":"Malaria", "Treated":420 },
    { "Month":"2014-08-01", "Condition":"Diarrhea", "Treated":152 },
    { "Month":"2014-08-01", "Condition":"ARI", "Treated":236 },
    { "Month":"2014-09-01", "Condition":"Malaria", "Treated":367 },
    { "Month":"2014-09-01", "Condition":"Diarrhea", "Treated":129 },
    { "Month":"2014-09-01", "Condition":"ARI", "Treated":202 },
    { "Month":"2014-10-01", "Condition":"Malaria", "Treated":449 },
    { "Month":"2014-10-01", "Condition":"Diarrhea", "Treated":137 },
    { "Month":"2014-10-01", "Condition":"ARI", "Treated":234 },
    { "Month":"2014-11-01", "Condition":"Malaria", "Treated":201 },
    { "Month":"2014-11-01", "Condition":"Diarrhea", "Treated":58 },
    { "Month":"2014-11-01", "Condition":"ARI", "Treated":78 },
    { "Month":"2014-12-01", "Condition":"Malaria", "Treated":184 },
    { "Month":"2014-12-01", "Condition":"Diarrhea", "Treated":89 },
    { "Month":"2014-12-01", "Condition":"ARI", "Treated":113 },
    { "Month":"2015-01-01", "Condition":"Malaria", "Treated":232 },
    { "Month":"2015-01-01", "Condition":"Diarrhea", "Treated":92 },
    { "Month":"2015-01-01", "Condition":"ARI", "Treated":162 }
    ];

// Create new chart object
var myChart = new dimple.chart(svg, data);

// Set color scheme
myChart.defaultColors = [
    new dimple.color("#9BBB59"),
    new dimple.color("#4BACC6"),
    new dimple.color("#F79646"),
    new dimple.color("#C0504D"),
    new dimple.color("#8064A2")
];

// Format chart
myChart.setBounds(60, 30, 510, 330)
var x = myChart.addTimeAxis("x", "Month", "%Y-%m-%d", "%b '%y");
x.timePeriod = d3.time.months;
x.timeInterval = 3;
var y = myChart.addMeasureAxis("y", "Treated");

// Add series; add legend; draw chart
myChart.addSeries("Condition", dimple.plot.line);
myChart.addLegend(65, 10, 510, 20, "right");
myChart.draw();
