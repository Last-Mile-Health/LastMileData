// Create new dimple SVG
var svg = dimple.newSvg("#dataportal_treatment_iccmVisitsPerPopulation", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"2013-01-01", "Condition":"Malaria", "TreatedPerPop":0 },
    { "Month":"2013-01-01", "Condition":"Diarrhea", "TreatedPerPop":0 },
    { "Month":"2013-01-01", "Condition":"ARI", "TreatedPerPop":0 },
    { "Month":"2013-02-01", "Condition":"Malaria", "TreatedPerPop":174 },
    { "Month":"2013-02-01", "Condition":"Diarrhea", "TreatedPerPop":45 },
    { "Month":"2013-02-01", "Condition":"ARI", "TreatedPerPop":98 },
    { "Month":"2013-03-01", "Condition":"Malaria", "TreatedPerPop":437 },
    { "Month":"2013-03-01", "Condition":"Diarrhea", "TreatedPerPop":197 },
    { "Month":"2013-03-01", "Condition":"ARI", "TreatedPerPop":233 },
    { "Month":"2013-04-01", "Condition":"Malaria", "TreatedPerPop":399 },
    { "Month":"2013-04-01", "Condition":"Diarrhea", "TreatedPerPop":153 },
    { "Month":"2013-04-01", "Condition":"ARI", "TreatedPerPop":153 },
    { "Month":"2013-05-01", "Condition":"Malaria", "TreatedPerPop":493 },
    { "Month":"2013-05-01", "Condition":"Diarrhea", "TreatedPerPop":282 },
    { "Month":"2013-05-01", "Condition":"ARI", "TreatedPerPop":359 },
    { "Month":"2013-06-01", "Condition":"Malaria", "TreatedPerPop":460 },
    { "Month":"2013-06-01", "Condition":"Diarrhea", "TreatedPerPop":167 },
    { "Month":"2013-06-01", "Condition":"ARI", "TreatedPerPop":223 },
    { "Month":"2013-07-01", "Condition":"Malaria", "TreatedPerPop":153 },
    { "Month":"2013-07-01", "Condition":"Diarrhea", "TreatedPerPop":58 },
    { "Month":"2013-07-01", "Condition":"ARI", "TreatedPerPop":130 },
    { "Month":"2013-08-01", "Condition":"Malaria", "TreatedPerPop":479 },
    { "Month":"2013-08-01", "Condition":"Diarrhea", "TreatedPerPop":234 },
    { "Month":"2013-08-01", "Condition":"ARI", "TreatedPerPop":257 },
    { "Month":"2013-09-01", "Condition":"Malaria", "TreatedPerPop":470 },
    { "Month":"2013-09-01", "Condition":"Diarrhea", "TreatedPerPop":179 },
    { "Month":"2013-09-01", "Condition":"ARI", "TreatedPerPop":222 },
    { "Month":"2013-10-01", "Condition":"Malaria", "TreatedPerPop":617 },
    { "Month":"2013-10-01", "Condition":"Diarrhea", "TreatedPerPop":210 },
    { "Month":"2013-10-01", "Condition":"ARI", "TreatedPerPop":526 },
    { "Month":"2013-11-01", "Condition":"Malaria", "TreatedPerPop":446 },
    { "Month":"2013-11-01", "Condition":"Diarrhea", "TreatedPerPop":138 },
    { "Month":"2013-11-01", "Condition":"ARI", "TreatedPerPop":376 },
    { "Month":"2013-12-01", "Condition":"Malaria", "TreatedPerPop":468 },
    { "Month":"2013-12-01", "Condition":"Diarrhea", "TreatedPerPop":147 },
    { "Month":"2013-12-01", "Condition":"ARI", "TreatedPerPop":400 },
    { "Month":"2014-01-01", "Condition":"Malaria", "TreatedPerPop":364 },
    { "Month":"2014-01-01", "Condition":"Diarrhea", "TreatedPerPop":146 },
    { "Month":"2014-01-01", "Condition":"ARI", "TreatedPerPop":263 },
    { "Month":"2014-02-01", "Condition":"Malaria", "TreatedPerPop":246 },
    { "Month":"2014-02-01", "Condition":"Diarrhea", "TreatedPerPop":80 },
    { "Month":"2014-02-01", "Condition":"ARI", "TreatedPerPop":158 },
    { "Month":"2014-03-01", "Condition":"Malaria", "TreatedPerPop":375 },
    { "Month":"2014-03-01", "Condition":"Diarrhea", "TreatedPerPop":145 },
    { "Month":"2014-03-01", "Condition":"ARI", "TreatedPerPop":235 },
    { "Month":"2014-04-01", "Condition":"Malaria", "TreatedPerPop":349 },
    { "Month":"2014-04-01", "Condition":"Diarrhea", "TreatedPerPop":145 },
    { "Month":"2014-04-01", "Condition":"ARI", "TreatedPerPop":198 },
    { "Month":"2014-05-01", "Condition":"Malaria", "TreatedPerPop":347 },
    { "Month":"2014-05-01", "Condition":"Diarrhea", "TreatedPerPop":139 },
    { "Month":"2014-05-01", "Condition":"ARI", "TreatedPerPop":214 },
    { "Month":"2014-06-01", "Condition":"Malaria", "TreatedPerPop":490 },
    { "Month":"2014-06-01", "Condition":"Diarrhea", "TreatedPerPop":185 },
    { "Month":"2014-06-01", "Condition":"ARI", "TreatedPerPop":317 },
    { "Month":"2014-07-01", "Condition":"Malaria", "TreatedPerPop":425 },
    { "Month":"2014-07-01", "Condition":"Diarrhea", "TreatedPerPop":131 },
    { "Month":"2014-07-01", "Condition":"ARI", "TreatedPerPop":225 },
    { "Month":"2014-08-01", "Condition":"Malaria", "TreatedPerPop":404 },
    { "Month":"2014-08-01", "Condition":"Diarrhea", "TreatedPerPop":146 },
    { "Month":"2014-08-01", "Condition":"ARI", "TreatedPerPop":227 },
    { "Month":"2014-09-01", "Condition":"Malaria", "TreatedPerPop":346 },
    { "Month":"2014-09-01", "Condition":"Diarrhea", "TreatedPerPop":121 },
    { "Month":"2014-09-01", "Condition":"ARI", "TreatedPerPop":190 },
    { "Month":"2014-10-01", "Condition":"Malaria", "TreatedPerPop":407 },
    { "Month":"2014-10-01", "Condition":"Diarrhea", "TreatedPerPop":124 },
    { "Month":"2014-10-01", "Condition":"ARI", "TreatedPerPop":212 },
    { "Month":"2014-11-01", "Condition":"Malaria", "TreatedPerPop":145 },
    { "Month":"2014-11-01", "Condition":"Diarrhea", "TreatedPerPop":42 },
    { "Month":"2014-11-01", "Condition":"ARI", "TreatedPerPop":56 },
    { "Month":"2014-12-01", "Condition":"Malaria", "TreatedPerPop":129 },
    { "Month":"2014-12-01", "Condition":"Diarrhea", "TreatedPerPop":63 },
    { "Month":"2014-12-01", "Condition":"ARI", "TreatedPerPop":79 },
    { "Month":"2015-01-01", "Condition":"Malaria", "TreatedPerPop":162 },
    { "Month":"2015-01-01", "Condition":"Diarrhea", "TreatedPerPop":64 },
    { "Month":"2015-01-01", "Condition":"ARI", "TreatedPerPop":113 },
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
var y = myChart.addMeasureAxis("y", "TreatedPerPop");
y.title = "Treated per 10K people served";

// Add series; add legend; draw chart
myChart.addSeries("Condition", dimple.plot.line);
myChart.addLegend(65, 10, 510, 20, "right");
myChart.draw();
