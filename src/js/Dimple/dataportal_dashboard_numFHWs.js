var svg = dimple.newSvg("#dataportal_dashboard_numFHWs", 575, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "Measure":"FHWs", "Number":0, "Order":1 },
    { "Month":"Oct '12", "Measure":"FHWs", "Number":10, "Order":2 },
    { "Month":"Nov '12", "Measure":"FHWs", "Number":10, "Order":3 },
    { "Month":"Dec '12", "Measure":"FHWs", "Number":10, "Order":4 },
    { "Month":"Jan '13", "Measure":"FHWs", "Number":10, "Order":5 },
    { "Month":"Feb '13", "Measure":"FHWs", "Number":10, "Order":6 },
    { "Month":"Mar '13", "Measure":"FHWs", "Number":10, "Order":7 },
    { "Month":"Apr '13", "Measure":"FHWs", "Number":10, "Order":8 },
    { "Month":"May '13", "Measure":"FHWs", "Number":10, "Order":9 },
    { "Month":"Jun '13", "Measure":"FHWs", "Number":26, "Order":10 },
    { "Month":"Jul '13", "Measure":"FHWs", "Number":26, "Order":11 },
    { "Month":"Aug '13", "Measure":"FHWs", "Number":26, "Order":12 },
    { "Month":"Sep '13", "Measure":"FHWs", "Number":26, "Order":13 },
    { "Month":"Oct '13", "Measure":"FHWs", "Number":26, "Order":14 },
    { "Month":"Nov '13", "Measure":"FHWs", "Number":26, "Order":15 },
    { "Month":"Dec '13", "Measure":"FHWs", "Number":42, "Order":16 },
    { "Month":"Jan '14", "Measure":"FHWs", "Number":42, "Order":17 },
    { "Month":"Feb '14", "Measure":"FHWs", "Number":42, "Order":18 },
    { "Month":"Mar '14", "Measure":"FHWs", "Number":42, "Order":19 },
    { "Month":"Apr '14", "Measure":"FHWs", "Number":42, "Order":20 },
    { "Month":"May '14", "Measure":"FHWs", "Number":42, "Order":21 },
    { "Month":"Jun '14", "Measure":"FHWs", "Number":42, "Order":22 },
    { "Month":"Jul '14", "Measure":"FHWs", "Number":42, "Order":23 },
    { "Month":"Aug '14", "Measure":"FHWs", "Number":42, "Order":24 },
    { "Month":"Sep '14", "Measure":"FHWs", "Number":42, "Order":25 },
    { "Month":"Oct '14", "Measure":"FHWs", "Number":42, "Order":26 },
    { "Month":"Nov '14", "Measure":"FHWs", "Number":42, "Order":27 },
    { "Month":"Dec '14", "Measure":"FHWs", "Number":56, "Order":28 },
    { "Month":"Jan '15", "Measure":"FHWs", "Number":56, "Order":29 },
];



//var data = [
//    { "Month":"2012-09-01", "Measure":"FHWs", "Number":0, "Order":1 },
//    { "Month":"2012-10-01", "Measure":"FHWs", "Number":10, "Order":2 },
//    { "Month":"2012-11-01", "Measure":"FHWs", "Number":10, "Order":3 },
//    { "Month":"2012-12-01", "Measure":"FHWs", "Number":10, "Order":4 },
//    { "Month":"2013-01-01", "Measure":"FHWs", "Number":10, "Order":5 },
//    { "Month":"2013-02-01", "Measure":"FHWs", "Number":10, "Order":6 },
//    { "Month":"2012-09-01", "Measure":"Villages", "Number":0, "Order":1 },
//    { "Month":"2012-10-01", "Measure":"Villages", "Number":12, "Order":2 },
//    { "Month":"2012-11-01", "Measure":"Villages", "Number":12, "Order":3 },
//    { "Month":"2012-12-01", "Measure":"Villages", "Number":12, "Order":4 },
//    { "Month":"2013-01-01", "Measure":"Villages", "Number":12, "Order":5 },
//    { "Month":"2013-02-01", "Measure":"Villages", "Number":12, "Order":6 }
//];

var myChart = new dimple.chart(svg, data);

// Set color scheme: orange
myChart.defaultColors = [
    new dimple.color("#F79646")
];

myChart.setBounds(40, 30, 430, 305);
var x = myChart.addCategoryAxis("x", "Month");
x.addOrderRule("Order");
//x.timeField = "Date";
//x.dateParseFormat = "%Y-%m-%d";
//x.tickFormat = "%m/%Y";
//x.ticks=20;
//x.timePeriod = d3.time.months;
//x.timeInterval = 4;
myChart.addMeasureAxis("y", "Number");
myChart.addSeries("Measure", dimple.plot.line);
myChart.draw();
