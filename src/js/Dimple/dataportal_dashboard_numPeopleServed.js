// Create new dimple SVG
var svg = dimple.newSvg("#dataportal_dashboard_numPeopleServed", 500, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"2012-09-01", "People":0 },
    { "Month":"2012-10-01", "People":1300 },
    { "Month":"2012-11-01", "People":1300 },
    { "Month":"2012-12-01", "People":1292 },
    { "Month":"2013-01-01", "People":1308 },
    { "Month":"2013-02-01", "People":1321 },
    { "Month":"2013-03-01", "People":1372 },
    { "Month":"2013-04-01", "People":1377 },
    { "Month":"2013-05-01", "People":1419 },
    { "Month":"2013-06-01", "People":1434 },
    { "Month":"2013-07-01", "People":5169 },
    { "Month":"2013-08-01", "People":5302 },
    { "Month":"2013-09-01", "People":5357 },
    { "Month":"2013-10-01", "People":5383 },
    { "Month":"2013-11-01", "People":5430 },
    { "Month":"2013-12-01", "People":5449 },
    { "Month":"2014-01-01", "People":7532 },
    { "Month":"2014-02-01", "People":9280 },
    { "Month":"2014-03-01", "People":9378 },
    { "Month":"2014-04-01", "People":9534 },
    { "Month":"2014-05-01", "People":9639 },
    { "Month":"2014-06-01", "People":9971 },
    { "Month":"2014-07-01", "People":10212 },
    { "Month":"2014-08-01", "People":10396 },
    { "Month":"2014-09-01", "People":10621 },
    { "Month":"2014-10-01", "People":11027 },
    { "Month":"2014-11-01", "People":13868 },
    { "Month":"2014-12-01", "People":14217 },
    { "Month":"2015-01-01", "People":14298 }
];

// Create new chart object
var myChart = new dimple.chart(svg, data);

// Set color scheme: orange
myChart.defaultColors = [
    new dimple.color("#F79646")
];

// Format chart
myChart.setBounds(50, 30, 430, 305);
var x = myChart.addTimeAxis("x", "Month", "%Y-%m-%d", "%b '%y");
x.timePeriod = d3.time.months;
x.timeInterval = 3;
var y = myChart.addMeasureAxis("y", "People");

// Add series; draw chart
myChart.addSeries("", dimple.plot.line);
myChart.draw();
