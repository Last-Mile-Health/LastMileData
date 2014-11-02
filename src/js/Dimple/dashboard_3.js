var svg = dimple.newSvg("#dashboard_3", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Month":"Jan", "Treated":50, "Condition":"Malaria" },
    { "Month":"Jan", "Treated":10, "Condition":"Pneumonia" },
    { "Month":"Jan", "Treated":30, "Condition":"Diarrhea" },
];

var myChart = new dimple.chart(svg, data);

// Set color scheme: green
myChart.defaultColors = [
    new dimple.color("#9BBB59"),
    new dimple.color("#4BACC6"),
    new dimple.color("#F79646")
];

myChart.setBounds(20, 20, 460, 360)
myChart.addMeasureAxis("p", "Treated");
myChart.addSeries("Condition", dimple.plot.pie);
myChart.addLegend(500, 20, 90, 300, "left");
myChart.draw();
