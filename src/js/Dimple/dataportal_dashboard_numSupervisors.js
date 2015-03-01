// Create new dimple SVG
var svg = dimple.newSvg("#dataportal_dashboard_numSupervisors", 500, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"2012-09-01", "Supervisors":0 },
    { "Month":"2012-10-01", "Supervisors":2 },
    { "Month":"2012-11-01", "Supervisors":2 },
    { "Month":"2012-12-01", "Supervisors":2 },
    { "Month":"2013-01-01", "Supervisors":2 },
    { "Month":"2013-02-01", "Supervisors":2 },
    { "Month":"2013-03-01", "Supervisors":2 },
    { "Month":"2013-04-01", "Supervisors":2 },
    { "Month":"2013-05-01", "Supervisors":2 },
    { "Month":"2013-06-01", "Supervisors":3 },
    { "Month":"2013-07-01", "Supervisors":3 },
    { "Month":"2013-08-01", "Supervisors":3 },
    { "Month":"2013-09-01", "Supervisors":3 },
    { "Month":"2013-10-01", "Supervisors":3 },
    { "Month":"2013-11-01", "Supervisors":3 },
    { "Month":"2013-12-01", "Supervisors":4 },
    { "Month":"2014-01-01", "Supervisors":4 },
    { "Month":"2014-02-01", "Supervisors":4 },
    { "Month":"2014-03-01", "Supervisors":4 },
    { "Month":"2014-04-01", "Supervisors":4 },
    { "Month":"2014-05-01", "Supervisors":4 },
    { "Month":"2014-06-01", "Supervisors":4 },
    { "Month":"2014-07-01", "Supervisors":4 },
    { "Month":"2014-08-01", "Supervisors":4 },
    { "Month":"2014-09-01", "Supervisors":4 },
    { "Month":"2014-10-01", "Supervisors":4 },
    { "Month":"2014-11-01", "Supervisors":4 },
    { "Month":"2014-12-01", "Supervisors":5 },
    { "Month":"2015-01-01", "Supervisors":5 }
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
var y = myChart.addMeasureAxis("y", "Supervisors");
y.tickFormat = "0";
y.ticks = "5";

// Add series; draw chart
myChart.addSeries("", dimple.plot.line);
myChart.draw();
