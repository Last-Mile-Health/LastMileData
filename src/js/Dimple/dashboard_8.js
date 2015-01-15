var svg = dimple.newSvg("#dashboard_8", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Sep '12", "Measure":"FHWs", "Scale":0, "Order":1 },
    { "Month":"Dec '12", "Measure":"FHWs", "Scale":10, "Order":2 },
    { "Month":"Mar '12", "Measure":"FHWs", "Scale":10, "Order":3 },
    { "Month":"Jun '12", "Measure":"FHWs", "Scale":26, "Order":4 },
    { "Month":"Sep '13", "Measure":"FHWs", "Scale":26, "Order":5 },
    { "Month":"Dec '13", "Measure":"FHWs", "Scale":42, "Order":6 },
    { "Month":"Mar '14", "Measure":"FHWs", "Scale":42, "Order":7 },
    { "Month":"Jun '14", "Measure":"FHWs", "Scale":42, "Order":8 },
    { "Month":"Sep '14", "Measure":"FHWs", "Scale":42, "Order":9 },
    { "Month":"Dec '14", "Measure":"FHWs", "Scale":56, "Order":10 },
    { "Month":"Sep '12", "Measure":"Villages", "Scale":0, "Order":1 },
    { "Month":"Dec '12", "Measure":"Villages", "Scale":12, "Order":2 },
    { "Month":"Mar '12", "Measure":"Villages", "Scale":12, "Order":3 },
    { "Month":"Jun '12", "Measure":"Villages", "Scale":25, "Order":4 },
    { "Month":"Sep '13", "Measure":"Villages", "Scale":25, "Order":5 },
    { "Month":"Dec '13", "Measure":"Villages", "Scale":40, "Order":6 },
    { "Month":"Mar '14", "Measure":"Villages", "Scale":40, "Order":7 },
    { "Month":"Jun '14", "Measure":"Villages", "Scale":40, "Order":8 },
    { "Month":"Sep '14", "Measure":"Villages", "Scale":40, "Order":9 },
    { "Month":"Dec '14", "Measure":"Villages", "Scale":44, "Order":10 }
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
myChart.addMeasureAxis("y", "Scale");
myChart.addSeries("Measure", dimple.plot.line);
myChart.addLegend(60, 10, 500, 20, "right");
myChart.draw();
