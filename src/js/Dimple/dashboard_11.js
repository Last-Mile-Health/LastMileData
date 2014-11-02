var svg = dimple.newSvg("#dashboard_11", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
    { "Cohort":"One", "Quarter":"Dec '12", "Treated":29, "Condition":"Big Belly", "order_x":2, "order_y":1 },
    { "Cohort":"One", "Quarter":"Dec '12", "Treated":29, "Condition":"Infant", "order_x":2, "order_y":1 },
    { "Cohort":"One", "Quarter":"Mar '12", "Treated":2, "Condition":"Big Belly", "order_x":3, "order_y":1 },
    { "Cohort":"One", "Quarter":"Mar '12", "Treated":4, "Condition":"Infant", "order_x":3, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jun '12", "Treated":10, "Condition":"Big Belly", "order_x":4, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jun '12", "Treated":196, "Condition":"Infant", "order_x":4, "order_y":1 },
    { "Cohort":"One", "Quarter":"Sep '13", "Treated":4, "Condition":"Big Belly", "order_x":5, "order_y":1 },
    { "Cohort":"One", "Quarter":"Sep '13", "Treated":114, "Condition":"Infant", "order_x":5, "order_y":1 },
    { "Cohort":"One", "Quarter":"Dec '13", "Treated":8, "Condition":"Big Belly", "order_x":6, "order_y":1 },
    { "Cohort":"One", "Quarter":"Dec '13", "Treated":33, "Condition":"Infant", "order_x":6, "order_y":1 },
    { "Cohort":"One", "Quarter":"Dec '13", "Treated":107, "Condition":"FP", "order_x":6, "order_y":1 },
    { "Cohort":"Two", "Quarter":"Dec '13", "Treated":6, "Condition":"Big Belly", "order_x":6, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Dec '13", "Treated":17, "Condition":"Infant", "order_x":6, "order_y":2 },
    { "Cohort":"One", "Quarter":"Mar '14", "Treated":9, "Condition":"Big Belly", "order_x":7, "order_y":1 },
    { "Cohort":"One", "Quarter":"Mar '14", "Treated":11, "Condition":"Infant", "order_x":7, "order_y":1 },
    { "Cohort":"One", "Quarter":"Mar '14", "Treated":42, "Condition":"NCD", "order_x":7, "order_y":1 },
    { "Cohort":"One", "Quarter":"Mar '14", "Treated":62, "Condition":"FP", "order_x":7, "order_y":1 },
    { "Cohort":"Two", "Quarter":"Mar '14", "Treated":10, "Condition":"Big Belly", "order_x":7, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Mar '14", "Treated":17, "Condition":"Infant", "order_x":7, "order_y":2 },
    { "Cohort":"One", "Quarter":"Jun '14", "Treated":14, "Condition":"Infant", "order_x":8, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jun '14", "Treated":10, "Condition":"NCD", "order_x":8, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jun '14", "Treated":11, "Condition":"FP", "order_x":8, "order_y":1 },
    { "Cohort":"Two", "Quarter":"Jun '14", "Treated":1, "Condition":"Big Belly", "order_x":8, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Jun '14", "Treated":4, "Condition":"Infant", "order_x":8, "order_y":2 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":8, "Condition":"Big Belly", "order_x":9, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":4, "Condition":"Infant", "order_x":9, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":1, "Condition":"FP", "order_x":9, "order_y":1 },
    { "Cohort":"Two", "Quarter":"Jul-Sep '14", "Treated":7, "Condition":"Big Belly", "order_x":9, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Jul-Sep '14", "Treated":8, "Condition":"Infant", "order_x":9, "order_y":2 }
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
