var svg = dimple.newSvg("#dashboard_14", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "People":0, "Order":1 },
    { "Month":"Dec '12", "People":1318, "Order":2 },
    { "Month":"Mar '12", "People":1384, "Order":3 },
    { "Month":"Jun '12", "People":1415, "Order":4 },
    { "Month":"Sep '13", "People":5324, "Order":5 },
    { "Month":"Dec '13", "People":5324, "Order":6 },
    { "Month":"Mar '14", "People":9173, "Order":7 },
    { "Month":"Jun '14", "People":9173, "Order":8 },
    { "Month":"Sep '14", "People":9173, "Order":9 },
    { "Month":"Dec '14", "People":14000, "Order":10 }
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
