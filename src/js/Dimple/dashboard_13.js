var svg = dimple.newSvg("#dashboard_13", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Quarter":"Oct-Dec '12", "Treated":0, "Condition":"Malaria", "order_x":1 },
    { "Quarter":"Jan-Mar '13", "Treated":84, "Condition":"Malaria", "order_x":2 },
    { "Quarter":"Apr-Jun '13", "Treated":191, "Condition":"Malaria", "order_x":3 },
    { "Quarter":"Jul-Sep '13", "Treated":589, "Condition":"Malaria", "order_x":4 },
    { "Quarter":"Oct-Dec '13", "Treated":832, "Condition":"Malaria", "order_x":5 },
    { "Quarter":"Jan-Mar '14", "Treated":855, "Condition":"Malaria", "order_x":6 },
    { "Quarter":"Apr-Jun '14", "Treated":1149, "Condition":"Malaria", "order_x":7 },
    { "Quarter":"Jul-Sep '14", "Treated":1168, "Condition":"Malaria", "order_x":8 },
    { "Quarter":"Oct-Dec '12", "Treated":0, "Condition":"Diarrhea", "order_x":1 },
    { "Quarter":"Jan-Mar '13", "Treated":39, "Condition":"Diarrhea", "order_x":2 },
    { "Quarter":"Apr-Jun '13", "Treated":85, "Condition":"Diarrhea", "order_x":3 },
    { "Quarter":"Jul-Sep '13", "Treated":252, "Condition":"Diarrhea", "order_x":4 },
    { "Quarter":"Oct-Dec '13", "Treated":271, "Condition":"Diarrhea", "order_x":5 },
    { "Quarter":"Jan-Mar '14", "Treated":320, "Condition":"Diarrhea", "order_x":6 },
    { "Quarter":"Apr-Jun '14", "Treated":454, "Condition":"Diarrhea", "order_x":7 },
    { "Quarter":"Jul-Sep '14", "Treated":413, "Condition":"Diarrhea", "order_x":8 },
    { "Quarter":"Oct-Dec '12", "Treated":0, "Condition":"ARI", "order_x":1 },
    { "Quarter":"Jan-Mar '13", "Treated":49, "Condition":"ARI", "order_x":2 },
    { "Quarter":"Apr-Jun '13", "Treated":104, "Condition":"ARI", "order_x":3 },
    { "Quarter":"Jul-Sep '13", "Treated":322, "Condition":"ARI", "order_x":4 },
    { "Quarter":"Oct-Dec '13", "Treated":705, "Condition":"ARI", "order_x":5 },
    { "Quarter":"Jan-Mar '14", "Treated":565, "Condition":"ARI", "order_x":6 },
    { "Quarter":"Apr-Jun '14", "Treated":708, "Condition":"ARI", "order_x":7 },
    { "Quarter":"Jul-Sep '14", "Treated":661, "Condition":"ARI", "order_x":8 }
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
//var x = myChart.addCategoryAxis("x", ["Price Tier","Channel"]);
var x = myChart.addCategoryAxis("x", ["Quarter","Condition"]);
myChart.addMeasureAxis("y", "Treated");
myChart.addSeries("Condition", dimple.plot.bar);
x.addOrderRule("order_x");
myChart.addLegend(65, 10, 510, 20, "right");
myChart.draw();
