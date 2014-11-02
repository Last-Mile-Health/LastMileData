var svg = dimple.newSvg("#dashboard_1", 800, 300);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Jan", "Value":20, "Order":1 },
    { "Month":"Feb", "Value":30, "Order":2 },
    { "Month":"Mar", "Value":40, "Order":3 },
    { "Month":"Apr", "Value":10, "Order":4 },
    { "Month":"May", "Value":30, "Order":5 },
    { "Month":"Jun", "Value":40, "Order":6 },
    { "Month":"Jul", "Value":50, "Order":7 }
];

var myChart = new dimple.chart(svg, data);

// Set color scheme: green
myChart.defaultColors = [
    new dimple.color("#9BBB59")
];

myChart.setBounds(60, 30, 610, 205);
var x = myChart.addCategoryAxis("x", "Month");
x.addOrderRule("Order");
var y = myChart.addMeasureAxis("y", "Value");
x.title = "x-axis title";
y.title = "y-axis title";
myChart.addSeries(null, dimple.plot.bar);
myChart.draw();
