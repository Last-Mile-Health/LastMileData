var svg = dimple.newSvg("#dashboard_7", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "FHWs":0, "Order":1 },
    { "Month":"Dec '12", "FHWs":10, "Order":2 },
    { "Month":"Mar '12", "FHWs":10, "Order":3 },
    { "Month":"Jun '12", "FHWs":26, "Order":4 },
    { "Month":"Sep '13", "FHWs":26, "Order":5 },
    { "Month":"Dec '13", "FHWs":42, "Order":6 },
    { "Month":"Mar '14", "FHWs":42, "Order":7 },
    { "Month":"Jun '14", "FHWs":42, "Order":8 }
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
myChart.addMeasureAxis("y", "FHWs");
myChart.addSeries(null, dimple.plot.line);
myChart.draw();
