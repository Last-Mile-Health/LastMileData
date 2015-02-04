var svg = dimple.newSvg("#dashboard_9", 590, 400);

// Data (!!!!! fill manually for now !!!!!)
var data = [
//    { "Cohort":"One", "Quarter":"Oct-Dec '12", "Treated":29, "Condition":"Big Belly", "order_x":2, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Oct-Dec '12", "Treated":29, "Condition":"Infant", "order_x":2, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Jan-Mar '12", "Treated":140, "Condition":"IMCI", "order_x":3, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Jan-Mar '12", "Treated":2, "Condition":"Big Belly", "order_x":3, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Jan-Mar '12", "Treated":4, "Condition":"Infant", "order_x":3, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Apr-Jun '12", "Treated":241, "Condition":"IMCI", "order_x":4, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Apr-Jun '12", "Treated":10, "Condition":"Big Belly", "order_x":4, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Apr-Jun '12", "Treated":196, "Condition":"Infant", "order_x":4, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Jul-Sep '13", "Treated":302, "Condition":"IMCI", "order_x":5, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Jul-Sep '13", "Treated":4, "Condition":"Big Belly", "order_x":5, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Jul-Sep '13", "Treated":114, "Condition":"Infant", "order_x":5, "order_y":1 },
//    { "Cohort":"Two", "Quarter":"Jul-Sep '13", "Treated":451, "Condition":"IMCI", "order_x":5, "order_y":2 },
//    { "Cohort":"One", "Quarter":"Oct-Dec '13", "Treated":376, "Condition":"IMCI", "order_x":6, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Oct-Dec '13", "Treated":8, "Condition":"Big Belly", "order_x":6, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Oct-Dec '13", "Treated":33, "Condition":"Infant", "order_x":6, "order_y":1 },
//    { "Cohort":"One", "Quarter":"Oct-Dec '13", "Treated":107, "Condition":"FP", "order_x":6, "order_y":1 },
//    { "Cohort":"Two", "Quarter":"Oct-Dec '13", "Treated":788, "Condition":"IMCI", "order_x":6, "order_y":2 },
//    { "Cohort":"Two", "Quarter":"Oct-Dec '13", "Treated":6, "Condition":"Big Belly", "order_x":6, "order_y":2 },
//    { "Cohort":"Two", "Quarter":"Oct-Dec '13", "Treated":17, "Condition":"Infant", "order_x":6, "order_y":2 },
    { "Cohort":"One", "Quarter":"Jan-Mar '14", "Treated":346, "Condition":"IMCI", "order_x":1, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jan-Mar '14", "Treated":9, "Condition":"Big Belly", "order_x":1, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jan-Mar '14", "Treated":11, "Condition":"Infant", "order_x":1, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jan-Mar '14", "Treated":42, "Condition":"NCD", "order_x":1, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jan-Mar '14", "Treated":62, "Condition":"FP", "order_x":1, "order_y":1 },
    { "Cohort":"Two", "Quarter":"Jan-Mar '14", "Treated":584, "Condition":"IMCI", "order_x":1, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Jan-Mar '14", "Treated":10, "Condition":"Big Belly", "order_x":1, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Jan-Mar '14", "Treated":17, "Condition":"Infant", "order_x":1, "order_y":2 },
    { "Cohort":"Three", "Quarter":"Jan-Mar '14", "Treated":190, "Condition":"IMCI", "order_x":1, "order_y":3 },
    { "Cohort":"One", "Quarter":"Apr-Jun '14", "Treated":323, "Condition":"IMCI", "order_x":2, "order_y":1 },
    { "Cohort":"One", "Quarter":"Apr-Jun '14", "Treated":14, "Condition":"Infant", "order_x":2, "order_y":1 },
    { "Cohort":"One", "Quarter":"Apr-Jun '14", "Treated":10, "Condition":"NCD", "order_x":2, "order_y":1 },
    { "Cohort":"One", "Quarter":"Apr-Jun '14", "Treated":11, "Condition":"FP", "order_x":2, "order_y":1 },
    { "Cohort":"Two", "Quarter":"Apr-Jun '14", "Treated":473, "Condition":"IMCI", "order_x":2, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Apr-Jun '14", "Treated":1, "Condition":"Big Belly", "order_x":2, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Apr-Jun '14", "Treated":4, "Condition":"Infant", "order_x":2, "order_y":2 },
    { "Cohort":"Three", "Quarter":"Apr-Jun '14", "Treated":656, "Condition":"IMCI", "order_x":2, "order_y":3 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":307, "Condition":"IMCI", "order_x":3, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":8, "Condition":"Big Belly", "order_x":3, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":4, "Condition":"Infant", "order_x":3, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":0, "Condition":"NCD", "order_x":3, "order_y":1 },
    { "Cohort":"One", "Quarter":"Jul-Sep '14", "Treated":1, "Condition":"FP", "order_x":3, "order_y":1 },
    { "Cohort":"Two", "Quarter":"Jul-Sep '14", "Treated":511, "Condition":"IMCI", "order_x":3, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Jul-Sep '14", "Treated":7, "Condition":"Big Belly", "order_x":3, "order_y":2 },
    { "Cohort":"Two", "Quarter":"Jul-Sep '14", "Treated":8, "Condition":"Infant", "order_x":3, "order_y":2 },
    { "Cohort":"Three", "Quarter":"Jul-Sep '14", "Treated":523, "Condition":"IMCI", "order_x":3, "order_y":3 }
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

myChart.setBounds(95, 25, 475, 290)
var x = myChart.addCategoryAxis("x", "Quarter");
var y = myChart.addCategoryAxis("y", "Cohort");
x.addOrderRule("order_x");
y.addOrderRule("order_y");
myChart.addMeasureAxis("p", "Treated");
var pies = myChart.addSeries("Condition", dimple.plot.pie);
pies.radius = 45;
myChart.addLegend(150, 10, 450, 20, "right");
myChart.draw();
