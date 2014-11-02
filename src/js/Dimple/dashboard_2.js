var svg = dimple.newSvg("#dashboard_2", 800, 300);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Jan", "Treated":50, "Condition":"Malaria", "Order":1 },
    { "Month":"Jan", "Treated":10, "Condition":"Pneumonia", "Order":1 },
    { "Month":"Jan", "Treated":30, "Condition":"Diarrhea", "Order":1 },
    { "Month":"Feb", "Treated":40, "Condition":"Malaria", "Order":2 },
    { "Month":"Feb", "Treated":10, "Condition":"Pneumonia", "Order":2 },
    { "Month":"Feb", "Treated":30, "Condition":"Diarrhea", "Order":2 },
    { "Month":"Mar", "Treated":30, "Condition":"Malaria", "Order":3 },
    { "Month":"Mar", "Treated":20, "Condition":"Pneumonia", "Order":3 },
    { "Month":"Mar", "Treated":10, "Condition":"Diarrhea", "Order":3 },
    { "Month":"Apr", "Treated":30, "Condition":"Malaria", "Order":4 },
    { "Month":"Apr", "Treated":20, "Condition":"Pneumonia", "Order":4 },
    { "Month":"Apr", "Treated":40, "Condition":"Diarrhea", "Order":4 },
    { "Month":"May", "Treated":40, "Condition":"Malaria", "Order":5 },
    { "Month":"May", "Treated":10, "Condition":"Pneumonia", "Order":5 },
    { "Month":"May", "Treated":30, "Condition":"Diarrhea", "Order":5 },
    { "Month":"Jun", "Treated":50, "Condition":"Malaria", "Order":6 },
    { "Month":"Jun", "Treated":30, "Condition":"Pneumonia", "Order":6 },
    { "Month":"Jun", "Treated":50, "Condition":"Diarrhea", "Order":6 }
];

var myChart = new dimple.chart(svg, data);

// Set color scheme: green
myChart.defaultColors = [
    new dimple.color("#9BBB59"),
    new dimple.color("#4BACC6"),
    new dimple.color("#F79646")
];

myChart.setBounds(60, 30, 610, 205);
var x = myChart.addCategoryAxis("x", "Month");
x.addOrderRule("Order");
var y = myChart.addMeasureAxis("y", "Treated");
x.title = "x-axis title";
y.title = "y-axis title";
myChart.addSeries("Condition", dimple.plot.bar);
myChart.addLegend(60, 10, 510, 20, "right");
myChart.draw();

