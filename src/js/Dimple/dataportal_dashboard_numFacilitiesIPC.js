// Create new dimple SVG
var svg = dimple.newSvg("#dataportal_dashboard_numFacilitiesIPC", 500, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"2014-12-01", "Facilities":36 },
    { "Month":"2015-01-01", "Facilities":36 }
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
x.timeInterval = 1;
var y = myChart.addMeasureAxis("y", "Facilities");

// Add series; draw chart
myChart.addSeries("", dimple.plot.line);
myChart.draw();
