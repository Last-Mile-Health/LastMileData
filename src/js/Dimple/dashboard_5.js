var svg = dimple.newSvg("#dashboard_5", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Jan", "Treated":50, "Order":1 },
    { "Month":"Feb", "Treated":40, "Order":2 },
    { "Month":"Mar", "Treated":30, "Order":3 },
    { "Month":"Apr", "Treated":30, "Order":4 },
    { "Month":"May", "Treated":40, "Order":5 },
    { "Month":"Jun", "Treated":50, "Order":6 },
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
myChart.addMeasureAxis("y", "Treated");
myChart.addSeries(null, dimple.plot.line);
myChart.draw();
