var svg = dimple.newSvg("#dashboard_10", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Quarter":"Jan-Mar '12", "Treated":140, "Condition":"IMCI", "order_x":3 },
    { "Quarter":"Jan-Mar '12", "Treated":2, "Condition":"Big Belly", "order_x":3 },
    { "Quarter":"Jan-Mar '12", "Treated":4, "Condition":"Infant", "order_x":3 },
    { "Quarter":"Apr-Jun '12", "Treated":241, "Condition":"IMCI", "order_x":4 },
    { "Quarter":"Apr-Jun '12", "Treated":10, "Condition":"Big Belly", "order_x":4 },
    { "Quarter":"Apr-Jun '12", "Treated":196, "Condition":"Infant", "order_x":4 },
    { "Quarter":"Jul-Sep '13", "Treated":753, "Condition":"IMCI", "order_x":5 },
    { "Quarter":"Jul-Sep '13", "Treated":4, "Condition":"Big Belly", "order_x":5 },
    { "Quarter":"Jul-Sep '13", "Treated":114, "Condition":"Infant", "order_x":5 },
    { "Quarter":"Oct-Dec '13", "Treated":1164, "Condition":"IMCI", "order_x":6 },
    { "Quarter":"Oct-Dec '13", "Treated":14, "Condition":"Big Belly", "order_x":6 },
    { "Quarter":"Oct-Dec '13", "Treated":50, "Condition":"Infant", "order_x":6 },
    { "Quarter":"Oct-Dec '13", "Treated":107, "Condition":"FP", "order_x":6 },
    { "Quarter":"Jan-Mar '14", "Treated":1122, "Condition":"IMCI", "order_x":7 },
    { "Quarter":"Jan-Mar '14", "Treated":33, "Condition":"Big Belly", "order_x":7 },
    { "Quarter":"Jan-Mar '14", "Treated":35, "Condition":"Infant", "order_x":7 },
    { "Quarter":"Jan-Mar '14", "Treated":77, "Condition":"NCD", "order_x":7 },
    { "Quarter":"Jan-Mar '14", "Treated":66, "Condition":"FP", "order_x":7 },
    { "Quarter":"Apr-Jun '14", "Treated":1463, "Condition":"IMCI", "order_x":8 },
    { "Quarter":"Apr-Jun '14", "Treated":26, "Condition":"Big Belly", "order_x":8 },
    { "Quarter":"Apr-Jun '14", "Treated":30, "Condition":"Infant", "order_x":8 },
    { "Quarter":"Apr-Jun '14", "Treated":10, "Condition":"NCD", "order_x":8 },
    { "Quarter":"Apr-Jun '14", "Treated":13, "Condition":"FP", "order_x":8 },
    { "Quarter":"Jul-Sep '14", "Treated":1515, "Condition":"IMCI", "order_x":9 },
    { "Quarter":"Jul-Sep '14", "Treated":36, "Condition":"Big Belly", "order_x":9 },
    { "Quarter":"Jul-Sep '14", "Treated":27, "Condition":"Infant", "order_x":9 },
    { "Quarter":"Jul-Sep '14", "Treated":2, "Condition":"NCD", "order_x":9 },
    { "Quarter":"Jul-Sep '14", "Treated":1, "Condition":"FP", "order_x":9 },
    { "Quarter":"Oct-Dec '14", "Treated":980, "Condition":"IMCI", "order_x":9 },
    { "Quarter":"Oct-Dec '14", "Treated":17, "Condition":"Big Belly", "order_x":9 },
    { "Quarter":"Oct-Dec '14", "Treated":24, "Condition":"Infant", "order_x":9 },
    { "Quarter":"Oct-Dec '14", "Treated":0, "Condition":"NCD", "order_x":9 },
    { "Quarter":"Oct-Dec '14", "Treated":0, "Condition":"FP", "order_x":9 }
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
