var svg = dimple.newSvg("#dashboard_4", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "District":"Konobo", "Month":"Jan", "Treated":50, "Condition":"Malaria", "Order":1 },
    { "District":"Konobo", "Month":"Jan", "Treated":10, "Condition":"Pneumonia", "Order":1 },
    { "District":"Konobo", "Month":"Jan", "Treated":30, "Condition":"Diarrhea", "Order":1 },
    { "District":"Glio-Twarbo", "Month":"Jan", "Treated":50, "Condition":"Malaria", "Order":1 },
    { "District":"Glio-Twarbo", "Month":"Jan", "Treated":10, "Condition":"Pneumonia", "Order":1 },
    { "District":"Glio-Twarbo", "Month":"Jan", "Treated":30, "Condition":"Diarrhea", "Order":1 },
    { "District":"Konobo", "Month":"Feb", "Treated":40, "Condition":"Malaria", "Order":2 },
    { "District":"Konobo", "Month":"Feb", "Treated":10, "Condition":"Pneumonia", "Order":2 },
    { "District":"Konobo", "Month":"Feb", "Treated":30, "Condition":"Diarrhea", "Order":2 },
    { "District":"Glio-Twarbo", "Month":"Feb", "Treated":40, "Condition":"Malaria", "Order":2 },
    { "District":"Glio-Twarbo", "Month":"Feb", "Treated":10, "Condition":"Pneumonia", "Order":2 },
    { "District":"Glio-Twarbo", "Month":"Feb", "Treated":30, "Condition":"Diarrhea", "Order":2 },
    { "District":"Konobo", "Month":"Mar", "Treated":30, "Condition":"Malaria", "Order":3 },
    { "District":"Konobo", "Month":"Mar", "Treated":20, "Condition":"Pneumonia", "Order":3 },
    { "District":"Konobo", "Month":"Mar", "Treated":10, "Condition":"Diarrhea", "Order":3 },
    { "District":"Glio-Twarbo", "Month":"Mar", "Treated":30, "Condition":"Malaria", "Order":3 },
    { "District":"Glio-Twarbo", "Month":"Mar", "Treated":20, "Condition":"Pneumonia", "Order":3 },
    { "District":"Glio-Twarbo", "Month":"Mar", "Treated":10, "Condition":"Diarrhea", "Order":3 },
    { "District":"Konobo", "Month":"Apr", "Treated":30, "Condition":"Malaria", "Order":4 },
    { "District":"Konobo", "Month":"Apr", "Treated":20, "Condition":"Pneumonia", "Order":4 },
    { "District":"Konobo", "Month":"Apr", "Treated":40, "Condition":"Diarrhea", "Order":4 },
    { "District":"Glio-Twarbo", "Month":"Apr", "Treated":30, "Condition":"Malaria", "Order":4 },
    { "District":"Glio-Twarbo", "Month":"Apr", "Treated":20, "Condition":"Pneumonia", "Order":4 },
    { "District":"Glio-Twarbo", "Month":"Apr", "Treated":40, "Condition":"Diarrhea", "Order":4 },
    { "District":"Konobo", "Month":"May", "Treated":40, "Condition":"Malaria", "Order":5 },
    { "District":"Konobo", "Month":"May", "Treated":10, "Condition":"Pneumonia", "Order":5 },
    { "District":"Konobo", "Month":"May", "Treated":30, "Condition":"Diarrhea", "Order":5 },
    { "District":"Glio-Twarbo", "Month":"May", "Treated":40, "Condition":"Malaria", "Order":5 },
    { "District":"Glio-Twarbo", "Month":"May", "Treated":10, "Condition":"Pneumonia", "Order":5 },
    { "District":"Glio-Twarbo", "Month":"May", "Treated":30, "Condition":"Diarrhea", "Order":5 },
    { "District":"Konobo", "Month":"Jun", "Treated":50, "Condition":"Malaria", "Order":6 },
    { "District":"Konobo", "Month":"Jun", "Treated":30, "Condition":"Pneumonia", "Order":6 },
    { "District":"Konobo", "Month":"Jun", "Treated":50, "Condition":"Diarrhea", "Order":6 },
    { "District":"Glio-Twarbo", "Month":"Jun", "Treated":50, "Condition":"Malaria", "Order":6 },
    { "District":"Glio-Twarbo", "Month":"Jun", "Treated":30, "Condition":"Pneumonia", "Order":6 },
    { "District":"Glio-Twarbo", "Month":"Jun", "Treated":50, "Condition":"Diarrhea", "Order":6 }
];

var myChart = new dimple.chart(svg, data);

// Set color scheme: green
myChart.defaultColors = [
    new dimple.color("#9BBB59"),
    new dimple.color("#4BACC6"),
    new dimple.color("#F79646")
];

myChart.setBounds(95, 25, 475, 335)
var x = myChart.addCategoryAxis("x", "Month");
x.addOrderRule("Order");
myChart.addCategoryAxis("y", "District");
myChart.addMeasureAxis("p", "Treated");
var pies = myChart.addSeries("Condition", dimple.plot.pie);
pies.radius = 25;
myChart.addLegend(240, 10, 330, 20, "right");
myChart.draw();
