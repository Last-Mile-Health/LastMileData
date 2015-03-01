// Create new dimple SVG
var svg = dimple.newSvg("#dataportal_ebolaActivities_screenAndEducate", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"2014-11-01", "Activity":"Screened", "People reached":378 },
    { "Month":"2014-11-01", "Activity":"Educated", "People reached":416 },
    { "Month":"2014-12-01", "Activity":"Screened", "People reached":709 },
    { "Month":"2014-12-01", "Activity":"Educated", "People reached":775 },
    { "Month":"2015-01-01", "Activity":"Screened", "People reached":495 },
    { "Month":"2015-01-01", "Activity":"Educated", "People reached":519 },
    { "Month":"2015-02-01", "Activity":"Screened", "People reached":1108 },
    { "Month":"2015-02-01", "Activity":"Educated", "People reached":1217 }
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
x.timeInterval = 1;
var y = myChart.addMeasureAxis("y", "People reached");

// Add series; add legend; draw chart
myChart.addSeries("Activity", dimple.plot.line);
myChart.addLegend(65, 10, 510, 20, "right");
myChart.draw();
