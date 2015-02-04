var svg = dimple.newSvg("#dashboard_13", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Quarter":"Jan '14", "Treated":274, "Condition":"Malaria", "order_x":1 },
    { "Quarter":"Feb '14", "Treated":228, "Condition":"Malaria", "order_x":2 },
    { "Quarter":"Mar '14", "Treated":352, "Condition":"Malaria", "order_x":3 },
    { "Quarter":"Apr '14", "Treated":333, "Condition":"Malaria", "order_x":4 },
    { "Quarter":"May '14", "Treated":334, "Condition":"Malaria", "order_x":5 },
    { "Quarter":"Jun '14", "Treated":489, "Condition":"Malaria", "order_x":6 },
    { "Quarter":"Jul '14", "Treated":434, "Condition":"Malaria", "order_x":7 },
    { "Quarter":"Aug '14", "Treated":420, "Condition":"Malaria", "order_x":8 },
    { "Quarter":"Sep '14", "Treated":367, "Condition":"Malaria", "order_x":9 },
    { "Quarter":"Oct '14", "Treated":449, "Condition":"Malaria", "order_x":10 },
    { "Quarter":"Nov '14", "Treated":201, "Condition":"Malaria", "order_x":11 },
    { "Quarter":"Dec '14", "Treated":175, "Condition":"Malaria", "order_x":12 },
    { "Quarter":"Jan '14", "Treated":110, "Condition":"Diarrhea", "order_x":1 },
    { "Quarter":"Feb '14", "Treated":74, "Condition":"Diarrhea", "order_x":2 },
    { "Quarter":"Mar '14", "Treated":136, "Condition":"Diarrhea", "order_x":3 },
    { "Quarter":"Apr '14", "Treated":138, "Condition":"Diarrhea", "order_x":4 },
    { "Quarter":"May '14", "Treated":134, "Condition":"Diarrhea", "order_x":5 },
    { "Quarter":"Jun '14", "Treated":184, "Condition":"Diarrhea", "order_x":6 },
    { "Quarter":"Jul '14", "Treated":134, "Condition":"Diarrhea", "order_x":7 },
    { "Quarter":"Aug '14", "Treated":152, "Condition":"Diarrhea", "order_x":8 },
    { "Quarter":"Sep '14", "Treated":129, "Condition":"Diarrhea", "order_x":9 },
    { "Quarter":"Oct '14", "Treated":137, "Condition":"Diarrhea", "order_x":10 },
    { "Quarter":"Nov '14", "Treated":62, "Condition":"Diarrhea", "order_x":11 },
    { "Quarter":"Dec '14", "Treated":75, "Condition":"Diarrhea", "order_x":12 },
    { "Quarter":"Jan '14", "Treated":198, "Condition":"ARI", "order_x":1 },
    { "Quarter":"Feb '14", "Treated":147, "Condition":"ARI", "order_x":2 },
    { "Quarter":"Mar '14", "Treated":220, "Condition":"ARI", "order_x":3 },
    { "Quarter":"Apr '14", "Treated":189, "Condition":"ARI", "order_x":4 },
    { "Quarter":"May '14", "Treated":206, "Condition":"ARI", "order_x":5 },
    { "Quarter":"Jun '14", "Treated":316, "Condition":"ARI", "order_x":6 },
    { "Quarter":"Jul '14", "Treated":230, "Condition":"ARI", "order_x":7 },
    { "Quarter":"Aug '14", "Treated":236, "Condition":"ARI", "order_x":8 },
    { "Quarter":"Sep '14", "Treated":202, "Condition":"ARI", "order_x":9 },
    { "Quarter":"Oct '14", "Treated":234, "Condition":"ARI", "order_x":10 },
    { "Quarter":"Nov '14", "Treated":80, "Condition":"ARI", "order_x":11 },
    { "Quarter":"Dec '14", "Treated":89, "Condition":"ARI", "order_x":12 }
    ];

var myChart = new dimple.chart(svg, data);

// Set color scheme: green
myChart.defaultColors = [
    new dimple.color("#9BBB59"),
    new dimple.color("#4BACC6"),
    new dimple.color("#F79646"),
    new dimple.color("#C0504D"),
    new dimple.color("#8064A2")
];


myChart.setBounds(60, 30, 510, 330)
var x = myChart.addCategoryAxis("x", ["Quarter","Condition"]);
myChart.addMeasureAxis("y", "Treated");
myChart.addSeries("Condition", dimple.plot.line);
x.addOrderRule("order_x");
myChart.addLegend(65, 10, 510, 20, "right");
myChart.draw();
