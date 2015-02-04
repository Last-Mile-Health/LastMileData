var svg = dimple.newSvg("#dashboard_14", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "People":0, "Order":1 },
    { "Month":"Oct '12", "People":1318, "Order":2 },
    { "Month":"Nov '12", "People":1318, "Order":3 },
    { "Month":"Dec '12", "People":1318, "Order":4 },
    { "Month":"Jan '12", "People":1384, "Order":5 },
    { "Month":"Feb '12", "People":1384, "Order":6 },
    { "Month":"Mar '12", "People":1384, "Order":7 },
    { "Month":"Apr '12", "People":1384, "Order":8 },
    { "Month":"May '12", "People":1384, "Order":9 },
    { "Month":"Jun '12", "People":5324, "Order":10 },
    { "Month":"Jul '13", "People":5324, "Order":11 },
    { "Month":"Aug '13", "People":5324, "Order":12 },
    { "Month":"Sep '13", "People":5324, "Order":13 },
    { "Month":"Oct '13", "People":5324, "Order":14 },
    { "Month":"Nov '13", "People":5324, "Order":15 },
    { "Month":"Dec '13", "People":9173, "Order":16 },
    { "Month":"Jan '14", "People":9173, "Order":17 },
    { "Month":"Feb '14", "People":9173, "Order":18 },
    { "Month":"Mar '14", "People":9173, "Order":19 },
    { "Month":"Apr '14", "People":9173, "Order":20 },
    { "Month":"May '14", "People":9173, "Order":21 },
    { "Month":"Jun '14", "People":9173, "Order":22 },
    { "Month":"Jul '14", "People":9173, "Order":23 },
    { "Month":"Aug '14", "People":9173, "Order":24 },
    { "Month":"Sep '14", "People":9173, "Order":25 },
    { "Month":"Oct '14", "People":9173, "Order":26 },
    { "Month":"Nov '14", "People":9173, "Order":27 },
    { "Month":"Dec '14", "People":15200, "Order":28 },
    { "Month":"Jan '15", "People":15200, "Order":29 }
];

var myChart = new dimple.chart(svg, data);

// Set color scheme: green
myChart.defaultColors = [
    new dimple.color("#9BBB59"),
    new dimple.color("#4BACC6"),
    new dimple.color("#F79646")
];

myChart.setBounds(60, 30, 505, 305);
var x = myChart.addCategoryAxis("x", "Month");
x.addOrderRule("Order");
myChart.addMeasureAxis("y", "People");
myChart.addSeries(null, dimple.plot.line);
myChart.draw();
